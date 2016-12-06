<?php

    /*
    This file contains methods for database related stuff.
    */
    require_once('base_dao.php');
    require_once('classes/Article.class.php');
    require_once('user_dao.php');
    
    class ArticleDao extends BaseDao {
        
        private $userDao;
        
        function __construct() {
            parent::__construct(Article::TABLE_NAME);
            $userDao = new UserDao();
        }
        
        /*
            Returns article with this id, or null if not found.
        */
        function getArticle($id) {
            
            $row = $this->get($id);
            if($row == null) {
                return null;
            }
            
            $article = new Article();
            $article->fill($row);
            
            return $article;
        }
        
        /*
            Saves the new article and its authors.
        */
        function newArticle($article) {
            $db = getConnection();

//            $db->beginTransaction();
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
//            $db->commit();

            
            // save the authors
//            $stmt = $db->prepare($authorsQuery);
//            foreach($article->getAuthors() as $author) {
//                $stmt->execute(array(":userId" => $author, 
//                                     ":articleId" => $id));
//                $rows = $stmt->rowCount();
//                if($rows != 1) {
//                    $db = null;
//                    return 0;
//                }
//            }

            $db = null;

            return 1;
        }

        /*
            Returns all published articles.
        */
        function getAllPublished() {
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
    }

?>