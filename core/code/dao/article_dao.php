<?php

    /*
    This file contains methods for database related stuff.
    */
    require_once ('base_dao.php');
    require_once ('attachment_dao.php');
    require_once ('user_dao.php');
    require_once ('review_dao.php');
    if(!defined('__CORE_ROOT__')) {
        //get one dir up - use it when require_once classes
        define('__CORE_ROOT__', dirname(dirname(__FILE__))); 
    }
    require_once (__CORE_ROOT__.'/classes/Article.class.php');
    
    class ArticleDao extends BaseDao {
        
        private $userDao;
        
        function __construct() {
            parent::__construct(Article::TABLE_NAME);
            $userDao = new UserDao();
        }
        
        /*
            Returns article with this id, or null if not found.
        */
        function get($id) {
            
            $row = parent::get($id);
            if($row == null) {
                return null;
            }
            
            $article = new Article();
            $article->fill($row);
            
            return $article;
        }
        
        /*
            Saves the new article and its authors.
            Returns 0 or id.
        */
        function newArticle($article) {
            $db = getConnection();

            $db->beginTransaction();
            $highestArticleIdQuery = "SELECT MAX(id) AS id FROM ".Article::TABLE_NAME;
            $artQuery = "INSERT INTO ".Article::TABLE_NAME."(id,title, content, created, state) VALUES(:id,:title, :content, :created, :state)";
            $authorsQuery = "INSERT INTO ".Article::AUTHOR_TABLE_NAME."(user_id, article_id) VALUES(:userId, :articleId)";

            // get the highest article id
            $stmt = $db->prepare($highestArticleIdQuery);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $id = null;
            foreach($rows as $row) {
                $id = $row["id"];
            }
            if($id == null) {
                $id = 1;
            } else {
                $id = $id+1;
            }

            // save the new article
            $stmt = $db->prepare($artQuery);
            $stmt->execute(array(":id" => $id, 
                                 ":title" => $article->getTitle(),
                                 ":content" => $article->getContent(),
                                 ":created" => $article->getCreated(),
                                 ":state" => $article->getState()));
            $rows = $stmt->rowCount();
            if($rows != 1) {
                $db->rollBack();
                $db = null;
                return 0;
            }
            $db->commit();

            
            // save the authors
            $stmt = $db->prepare($authorsQuery);
            foreach($article->getAuthors() as $author) {
                $stmt->execute(array(":userId" => $author, 
                                     ":articleId" => $id));
                $rows = $stmt->rowCount();
                if($rows != 1) {
                    $db = null;
                    return 0;
                }
            }

            $db = null;

            return $id;
        }

        /*
            Returns all published articles.
        */
        function getPublished() {
            $query = "SELECT * FROM ".Article::TABLE_NAME." WHERE state=:state";
            $articles = [];

            $db = getConnection();

            $stmt = $db->prepare($query);
            $stmt->execute(array(":state" => ArticleState::PUBLISHED));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $row) {
                $a = new Article();
                $a->fill($row);
                $articles[] = $a;
            }

            $db = null;
            return $articles;
        }
        
        /*
        
            Returns an array of User objects for article.
        
        */
        function getAuthorsForArticle($articleId) {
            $query = "SELECT user.id,enabled,username,password,email,first_name,last_name,role_id FROM ".User::TABLE_NAME." LEFT JOIN ".Article::AUTHOR_TABLE_NAME." ON user.id=author.user_id where author.article_id=:articleId ";
            $authors = [];
            
            $db = getConnection();
            
            $stmt = $db->prepare($query);
            $stmt->execute(array(":articleId" => $articleId));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $row) {
                $author = new User();
                $author->fill($row);
                $authors[] = $author;
            }
            
            $db = null;
            
            return $authors;
        }

        /**
         * Returns a list of new articles - those without a reviewer assigned.
         */
        function getNewArticles() {
            $query = "SELECT * FROM ".Article::TABLE_NAME." WHERE state=:state";
            $articles = [];

            $db = getConnection();

            $stmt = $db->prepare($query);
            $stmt->execute(array(":state" => ArticleState::CREATED));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $a = new Article();
                $a->fill($row);
                $articles[] = $a;
            }

            $db = null;

            return $articles;
        }

        /*
         * Returns true if the article exists and is PUBLISHED.
         */
        function isPublished($articleId) {
            $a = $this->get($articleId);
            return $a !== null && $a->getState() == ArticleState::PUBLISHED;
        }

        /*
         * Publishes article and returns 1 if the update was successful.
         */
        function publishArticle($articleId) {
            $query = "UPDATE ".Article::TABLE_NAME." SET state=:state WHERE id=:aid";

            $db = getConnection();

            $rowCount = $this->executeModifyStatement($db, $query, array(":state" => ArticleState::PUBLISHED, ":aid" => $articleId));

            $db = null;

            return $rowCount;
        }

        /*
         * Rejects the article. Deletes the review results and changes the article's state to REJECTED.
         * Returns 0 if error occurs and 1 if everything is ok.
         */
        function rejectArticle($articleId) {
            $getIds = "SELECT review_result_id FROM ".Review::TABLE_NAME." WHERE article_id=:artId";
            $delResRevQ1 = "UPDATE ".Review::TABLE_NAME." SET review_result_id = NULL WHERE article_id=:artId";
            $delResRevQ2 = "REMOVE FROM ".ReviewResult::TABLE_NAME." WHERE id=:rrid";
            $changeStateQ = "UPDATE ".Article::TABLE_NAME." SET state=:state WHERE id=:artId";

            $db = getConnection();
            $db->beginTransaction();

            $rows = $this->executeSelectStatement($db, $getIds, array(":artId" => $articleId));

            // not enough reviews
            if(sizeof($rows) != 3) {
                echo "not enough rows ".sizeof($rows)."<br>";
                $db->rollBack();
                $db = null;
                return 0;
            }

            // set review_result_id to null and remove it
            $rowCount = $this->executeModifyStatement($db, $delResRevQ1, (array(":artId" => $articleId)));
            if($rowCount != 3) {
                echo "review with article id".$articleId." not removed ".$rowCount."<br>";
                $db->rollBack();
                $db = null;
                return 0;
            }
            foreach ($rows as $row) {
                $rowCount = $this->executeModifyStatement($db, $delResRevQ2, (array(":rrid" => $row["review_result_id"])));
                if($rowCount != 1) {
                    echo "review result with id".$row["review_result_id"]." not removed ".$rowCount."<br>";
                    $db->rollBack();
                    $db = null;
                    return 0;
                }
            }

            // update the article state
            $rowCount = $this->executeModifyStatement($db, $changeStateQ, array(":state" => ArticleState::REJECTED, ":artId" => $articleId));
            if ($rowCount != 1) {
                echo "article state not updated ".$articleId."  ".$rowCount."<br>";
                $db->rollBack();
                return 0;
            }

            $db->commit();
            $db = null;

            return 1;
        }

        /*
         * Removes the article and its authors, attachments and reviews.
         */
        function remove($id)
        {
            $remAuthorsQ = "DELETE FROM ".Article::AUTHOR_TABLE_NAME." WHERE article_id=:aid";
            $reviewResqQ = "SELECT id FROM ".Review::TABLE_NAME." WHERE article_id=:aid";

            // remove authors
            $db = getConnection();
            $this->executeModifyStatement($db, $remAuthorsQ, array(":aid" => $id));

            // remove attachments
            $atDao = new AttachmentDao();
            $atDao->removeByArticle($id);
            $atDao = null;

            // remove review and its reviews
            $rDao = new ReviewDao();
            $rIds = $this->executeSelectStatement($db, $reviewResqQ, array(":aid" => $id));
            foreach ($rIds as $rid) {
                $rDao->remove($rid["id"]);
            }
            $rDao = null;
            $db = null;

            // remove article
            return parent::remove($id);
        }

        /*
         * Returns articles for author.
         */
        function getArticlesForAuthor($authorId) {
            $query = "SELECT article.id, article.title, article.content, article.created, article.state 
                      FROM ".Article::TABLE_NAME." LEFT JOIN ".Article::AUTHOR_TABLE_NAME." ON author.article_id=article.id WHERE author.user_id=:authId";

            $db = getConnection();
            $rows = $this->executeSelectStatement($db, $query, array(":authId" => $authorId));
            $db = null;

            $articles = [];
            foreach ($rows as $row) {
                $a = new Article();
                $a->fill($row);
                $articles[] = $a;
            }

            return $articles;
        }

        /*
         * Returns true if the user is article's author.
         */
        function isAuthor($articleId, $userId) {
            $query = "SELECT id FROM ".Article::AUTHOR_TABLE_NAME." WHERE user_id=:uid AND article_id=:aid";

            $db = getConnection();
            $rows = $this->executeSelectStatement($db, $query, array(":uid" => $userId, ":aid" => $articleId));
            $db = null;

            return sizeof($rows) > 0;
        }

        /*
         * Returns true if the user can delete article.
         * User must be author of an article to delete it.
         * Article can't be published.
         */
        function canDelete($userId, $articleId) {
            $query = "SELECT id FROM ".Article::AUTHOR_TABLE_NAME." WHERE user_id=:uid AND article_id=:aid";

            $db = getConnection();
            $rows = $this->executeSelectStatement($db, $query, array(":uid" => $userId, ":aid" => $articleId));
            $db = null;

            $a = $this->get($articleId);
            if($a == null) {
                return false;
            }

            return sizeof($rows) > 0 && !$a->isPublished();
        }

        function updateArticle($article) {
            $query = "UPDATE ".Article::TABLE_NAME." SET title=:t, content=:c WHERE id=:id";

            $db = getConnection();
            $rowCount = $this->executeModifyStatement($db, $query, array(":t"=>$article->getTitle(), ":c" => $article->getContent(), ":id" => $article->getId()));
            $db = null;

            return $rowCount;
        }
    }

?>