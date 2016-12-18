<?php
/*

    Data access object for reviews.

*/
require_once('BaseDao.php');
if(!defined('__CORE_ROOT__')) {
    //get one dir up - use it when require_once classes
    define('__CORE_ROOT__', dirname(dirname(__FILE__))); 
}
require_once(__CORE_ROOT__.'/classes/Review.class.php');
require_once(__CORE_ROOT__.'/classes/Article.class.php');

class ReviewDao extends BaseDao {
    
    function __construct() {
        parent::__construct(Review::TABLE_NAME);
    }
    
    /*
        Returns the review object with this id or null if not found.
    */
    function get($id) {
        $row = parent::get($id);
        if( $row == null) {
            return null;
        }
        
        $r = new Review();
        $r->fill($row);
        
        return $r;
    }
    
    /*
        Returns 1 if the article wass assigned and 0 if error occurs.
        If the article was correctly assigned, it's state will change to 'TO_BE_REVIEWED'
    */
    function assignReview($articleId, $reviewerId, $assignedById) {
        $query = "INSERT INTO ".Review::TABLE_NAME."(article_id, reviewer_id,assigned_by_id) VALUES(:articleId, :reviewerId,:assignedById)";
        $artQuery = "UPDATE ".Article::TABLE_NAME." SET state=:tbr WHERE id=:aid";
        
        try {
            $db = getConnection();
            
            $db->beginTransaction();
            
            $stmt = $db->prepare($query);
            $stmt->execute(array("articleId"=>$articleId,
                                 "reviewerId"=>$reviewerId,
                                 "assignedById"=>$assignedById));
            
            $rowCount = $stmt->rowCount();
            
            if($rowCount != 1) {
                $db->rollBack();
                $db = null;
                return 0;
            }
            
            $stmt = $db->prepare($artQuery);
            $stmt->execute(array("tbr" => ArticleState::TO_BE_REVIEWED,
                                 "aid" => $articleId));
            $rowCount = $stmt->rowCount();
            
            $db->commit();
            
            return $rowCount;
        } catch (Exception $e) {
            /* error occured */
            $db->rollBack();
            $db = null;
            return 0;
        }
    }
    
    /*
        Returns a list of arrays. Each array will contain ["review"] => Review and ["article"] => Article.
        
        The Article object will contain title.
    */
    function getArticlesToReview($reviewerId) {
        $query = "SELECT article.title, review.id, review.article_id, review.reviewer_id, review.assigned_by_id, review.review_result_id 
                  FROM review LEFT JOIN article ON review.article_id=article.id WHERE review.reviewer_id=:reviewerId AND (review.review_result_id IS NULL OR article.state = :rejState)";
        
        $res = [];
        
        $db = getConnection();

        $rows = $this->executeSelectStatement($db, $query, array(":reviewerId" => $reviewerId, ":rejState" => ArticleState::REJECTED));
        foreach($rows as $row) {
            $article = new Article();
            $article->setTitle($row["title"]);
            
            $review = new Review();
            $review->fill($row);
            
            $ent = array("review" => $review, "article" => $article);
            
            $res[] = $ent;
        }
        
        $db = null;
        
        return $res;
    }
    
    /*
        Saves a review result of article.
        Changes the state of article to 'REVIEWED'

        returns 0 if error occurs and 1 if everything is ok.
    */
    function reviewArticle($reviewId, $reviewResult) {
        $query = "INSERT INTO ".ReviewResult::TABLE_NAME."(id,crit_1,crit_2,crit_3,crit_4) VALUES(:id,:c1,:c2,:c3,:c4)";
        $changeStateQ = "UPDATE ".Article::TABLE_NAME." SET state=:state WHERE id IN (SELECT article_id FROM ".Review::TABLE_NAME." WHERE id=:revId)";

        $db = getConnection();
        $db->beginTransaction();
        
        //first, get the review_result highest id
        $id = null;
        $maxIdQ = "SELECT MAX(id) AS max FROM ".ReviewResult::TABLE_NAME;
        $rows = $this->executeSelectStatement($db, $maxIdQ, array());
        foreach ($rows as $row) {
            $id = $row["max"];
        }
        //increment the max id
        if($id == null) {
            $id = 1;
        } else {
            $id = $id + 1;
        }
        
        //second, save the result
        $rowCount = $this->executeModifyStatement($db, $query,array(":id"=>$id,
                                                ":c1"=>$reviewResult->getCrit1(),
                                                ":c2"=>$reviewResult->getCrit2(),
                                                ":c3"=>$reviewResult->getCrit3(),
                                                ":c4"=>$reviewResult->getCrit4()));
        if($rowCount != 1) {
            $db->rollBack();
            return 0;
        }

        // third, change the article state
        $this->executeModifyStatement($db, $changeStateQ, array(":state" => ArticleState::REVIEWED, "revId" => $reviewId));
        $db->commit();
        
        //fourth update the review row
        $query = "UPDATE ".Review::TABLE_NAME." SET review_result_id=:id WHERE id=:reviewId";
        $rowCount = $this->executeModifyStatement($db, $query, array(":id" => $id, ":reviewId" => $reviewId));
        if($rowCount != 1) {
            $db = null;
            return 0;
        }

        $db = null;
        
        return 1;
    }
    
    /*
        Returns true if the review is assigned to the reviewer.
    */
    function correctReviewer($reviewId, $reviewerId) {
        $query = "SELECT id FROM ".Review::TABLE_NAME." WHERE id=:rId AND reviewer_id=:revId";

        $db = getConnection();

        $stmt = $db->prepare($query);
        $stmt->execute(array(":rId" => $reviewId,
                             ":revId" => $reviewerId));
        $rowCount = $stmt->rowCount();
        $db = null;
        return $rowCount == 1;
    }

    /*
     * Returns articles reviewed by a certain reviewer.
     */
    function getReviewedArticles($reviewerId) {
        $query = "SELECT article.* FROM review left join article on article.id=article_id where review.reviewer_id=:reviewerId";
        $articles = [];

        $db = getConnection();
        $rows = $this->executeSelectStatement($db,$query,array(":reviewerId"=>$reviewerId));
        foreach ($rows as $row) {
            $a = new Article();
            $a->fill($row);
            $articles[] = $a;
        }

        $db = null;

        return $articles;
    }

    /*
     * Returns articles and their reviews.
     *
     * One item from the returned list will contain ["article"] and ["reviewResult1"],["reviewResult2"],["reviewResult3"],["reviewResult4"].
     * The items will be indexed by article id.
     *
     */
    function getAllReviewedArticles() {
        $query = "SELECT article.id as aid, article.title as title, review_result.* 
                  FROM review 
                    LEFT JOIN review_result on review_result.id=review.review_result_id 
                    LEFT JOIN article on article.id=review.article_id  
                  WHERE review.review_result_id IS NOT NULL AND article.state != :pubState";
        $reviewedArticles = [];

        $db = getConnection();

        $rows = $this->executeSelectStatement($db, $query, array(":pubState" => ArticleState::PUBLISHED));
        foreach ($rows as $row) {
            $article = new Article();
            $article->setId($row["aid"]);
            $article->setTitle($row["title"]);

            $review = new ReviewResult();
            $review->fill($row);

            if(isset($reviewedArticles[$article->getId()])) {
                //article is in the tmp array, add next review result
                if(!isset($reviewedArticles[$article->getId()]["reviewResult2"])) {
                    $reviewedArticles[$article->getId()]["reviewResult2"] = $review;
                } else if(!isset($reviewedArticles[$article->getId()]["reviewResult3"])) {
                    $reviewedArticles[$article->getId()]["reviewResult3"] = $review;
                }
            } else {
                //article isn't in the temp array yet
                $reviewedArticles[$article->getId()] = array("article" => $article, "reviewResult1" => $review);
            }
        }

        $db = null;

        return $reviewedArticles;
    }

    /*
     * Returns Review objects for article.
     *
     */
    function getReviewsForArticle($articleId) {
        $query = "SELECT * FROM ".Review::TABLE_NAME." WHERE article_id=:artId AND review_result_id is not null";
        $reviews = [];

        $db = getConnection();
        $rows = $this->executeSelectStatement($db, $query, array(":artId" => $articleId));
        $db = null;

        foreach ($rows as $row) {
            $r = new Review();
            $r->fill($row);
            $reviews[] = $r;
        }

        return $reviews;
    }

    function getReviewResult($revResId) {
        $query = "SELECT * FROM ".ReviewResult::TABLE_NAME." WHERE id=:revResId";

        $db = getConnection();

        $rr = null;
        $rows = $this->executeSelectStatement($db, $query, array(":revResId" => $revResId));
        foreach ($rows as $row) {
            $rr = new ReviewResult();
            $rr->fill($row);
        }
        $db = null;

        return $rr;
    }

    /*
     * Removes the review and attached review result.
     */
    function remove($id)
    {
        $review = $this->get($id);
        if($review == null) {
            return;
        }

        $removeRrQ = "DELETE FROM ".ReviewResult::TABLE_NAME." WHERE id=:revResId";
        $db = getConnection();
        $this->executeModifyStatement($db, $removeRrQ, array(":revResId" => $review->getReviewResultId()));
        $db = null;
        return parent::remove($id); // TODO: Change the autogenerated stub
    }
}

?>