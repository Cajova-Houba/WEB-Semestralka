<?php

    /*
    This file contains methods for database related stuff.
    */
    require_once('base_dao.php');
    if(!defined('__CORE_ROOT__')) {
        //get one dir up - use it when require_once classes
        define('__CORE_ROOT__', dirname(dirname(__FILE__))); 
    }
    require_once(__CORE_ROOT__.'/classes/Article.class.php');
    require_once(__CORE_ROOT__.'/classes/User.class.php');
    
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

            return 1;
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
            $query = "SELECT user.id,username,password,email,first_name,last_name,role_id FROM ".User::TABLE_NAME." LEFT JOIN ".Article::AUTHOR_TABLE_NAME." ON user.id=author.user_id where author.article_id=:articleId ";
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
    }

?>