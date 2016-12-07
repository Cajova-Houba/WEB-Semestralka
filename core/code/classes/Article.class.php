<?php
require_once('BaseObject.class.php');

/*
 Possible article states.
*/
abstract class ArticleState{
    const CREATED = 'CREATED';
    const TO_BE_REVIEWED = 'TO_BE_REVIEWED';
    const REVIEWED = 'REVIEWED';
    const PUBLISHED = 'PUBLISHED';
    const REJECTED = 'REJECTED';
}

/*
 Article object.
*/
class Article extends BaseObject {
    const TABLE_NAME = 'article';
    const AUTHOR_TABLE_NAME = 'author';
    private $title = '';
    private $content = '';
    private $created = '';
    private $state = '';
    private $authors = [];
    
    function __construct() {
        $this->state = ArticleState::CREATED;
    }
    
    function __construct1($title, $content, $created) {
        $this->title = $title;
        $this->content = $content;
        $this->created = $created;
        $this->state = ArticleState::CREATED;
    }
    
    function __construct2($id, $title, $content, $created, $state, $authors) {
        $this->setId($id);
        $this->title = $title;
        $this->content = $content;
        $this->created = $created;
        $this->state = $state;
        $this->authors = $authors;
    }
    
    function fill($row) {
        $this->setId($row["id"]);
        $this->title = $row["title"];
        $this->content = $row["content"];
        $this->created = $row["created"]; 
        $this->state = $row["state"];
    } 
    
    function getTableName() {
        return Article::TABLE_NAME;
    }
    
    function getTitle() {
        return $this->title;
    }
    
    function getContent() {
        return $this->content;
    }
    
    function getCreated() {
        return $this->created;
    }
    
    function getState() {
        return $this->state;
    }
    
    function getAuthors() {
        return $this->authors;
    }
    
    function setTitle($title) {
        $this->title = $title;
    }
    
    function setContent($content) {
        $this->content = $content;
    }
    
    function setCreated($created) {
        $this->created = $created;
    }
    
    function setState($state) {
        $this->state = $state;
    }
    
    function setAuthors($authors) {
        $this->authors = $authors;
    }
    
    
}

?>