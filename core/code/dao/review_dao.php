<?php
/*

    Data access object for reviews.

*/
require_once('base_dao.php');
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
    */
    function assignReview($articleId, $reviewerId, $assignedById) {
        $query = "INSERT INTO ".Review::TABLE_NAME."(article_id, reviewer_id,assigned_by_id) VALUES(:articleId, :reviewerId,:assignedById)";
        
        $db = getConnection();
        
        try {
            $stmt = $db->prepare($query);
            $stmt->execute(array("articleId"=>$articleId,
                                 "reviewerid"=>$reviewerId,
                                 "assignedById"=>$assignedById));
            
            $rowCount = $stmt->rowCount();
            return $rowCount;
        } catch (Exception $e) {
            /* error occured */
            $db = null;
            return 0;
        }
    }
    
    /*
        Returns a list of arrays. Each array will contain ["review"] => Review and ["article"] => Article.
        
        The Article object will contain title.
    */
    function getArticlesToReview($reviewerId) {
        $query = "SELECT article.title, review.id, review.article_id, review.reviewer_id, review.assigned_by_id, review.review_result_id FROM review LEFT JOIN article ON review.article_id=article.id WHERE review.reviewer_id=:reviewerId AND review.review_result_id IS NULL";
        
        $res = [];
        
        $db = getConnection();
        
        $stmt = $db->prepare($query);
        $stmt->execute(array("reviewerId" => $reviewerId));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        
        returns 0 if error occurs and 1 if everything is ok.
    */
    function reviewArticle($reviewId, $reviewResult) {
        $query = "INSERT INTO ".ReviewResult::TABLE_NAME."(id,crit_1,crit_2,crit_3,crit_4) VALUES(:id,:c1,:c2,:c3,:c4)";
        
        $db = getConnection();
        
        
        
        $db->beginTransaction();
        
        //first, get the review_result highest id
        $id = null;
        $maxIdQ = "SELECT MAX(id) AS max FROM ".ReviewResult::TABLE_NAME;
        $stmt = $db->prepare($maxIdQ);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $stmt = $db->prepare($query);
        $stmt->execute(array(":id"=>$id,
                             ":c1"=>$reviewResult->getCrit1(),
                             ":c2"=>$reviewResult->getCrit2(),
                             ":c3"=>$reviewResult->getCrit3(),
                             ":c4"=>$reviewResult->getCrit4(),));
        $rowCount = $stmt->rowCount();
        if($rowCunt != 1) {
            $db->rollBack();
            return 0;
        }
        
        $db->commit();
        
        //third update the review row
        $query = "UPDATE ".Review::TABLE_NAME." SET review_result_id=:id";
        
        $stmt = $db->prepare($query);
        $stmt->execute(array(":id" => $id));
        $rowCount = $stmt->rowCount();
        if($rowCount != 1) {
            $db = null;
            return 0;
        }
        
        $db = null;
        
        return 1;
    }
}

?>