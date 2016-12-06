<?php
require_once('BaseObject.class.php');

/*
 Possible article states.
*/
abstract class ArticleState{
    const CREATED = 0;
    const TO_BE_REVIEWED = 1;
    const REVIEWED = 2;
    const PUBLISHED = 3;
    const REJECTED = 4;
}

/*
 Article object.
*/
class Article extends BaseObject {
    const TABLE_NAME = 'article';
    private $title = '';
    private $content = '';
    private $created = '';
    private $state = '';
    
    function __construct() {
        $this->state = ArticleState::CREATED;
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
    
    
}

?>