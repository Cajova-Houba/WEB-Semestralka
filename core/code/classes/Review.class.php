<?php

require_once('BaseObject.class.php');

class ReviewResult extends BaseObject {
    
    const TABLE_NAME = 'review_result';
    private $crit1 = 0;
    private $crit2 = 0;
    private $crit3 = 0;
    private $crit4 = 0;

    function __construct() {

    }

    static function newResult($c1, $c2, $c3, $c4) {
        $reviewResult = new ReviewResult();
        $reviewResult->setCrit1($c1);
        $reviewResult->setCrit2($c2);
        $reviewResult->setCrit3($c3);
        $reviewResult->setCrit4($c4);

        return $reviewResult;
    }
    
    function getTableName() {
        return ReviewResult::TABLE_NAME;
    }
    
    function fill($row) {
        $this->setId($row["id"]);
        $this->crit1 = $row["crit_1"];
        $this->crit2 = $row["crit_2"];
        $this->crit3 = $row["crit_3"];
        $this->crit4 = $row["crit_4"];
    }
    
    function getCrit1() {
        return $this->crit1;
    }
    
    function getCrit2() {
        return $this->crit2;
    }
    
    function getCrit3() {
        return $this->crit3;
    }
    
    function getCrit4() {
        return $this->crit4;
    }
    
    function setCrit1($c1) {
        $this->crit1 = $c1;
    }
    
    function setCrit2($c2) {
        $this->crit2 = $c2;
    }
    
    function setCrit3($c3) {
        $this->crit3 = $c3;
    }
    
    function setCrit4($c4) {
        $this->crit4 = $c4;
    }
}

/*
    Review object. It connects articles with reviewers and review results.
*/

class Review extends BaseObject {
    
    const TABLE_NAME = 'review';
    private $articleId = null;
    private $reviewerId = null;
    private $assignedById =  null;
    private $reviewResultId = null;
    
    function __construct() {
        
    }
    
    function getTableName() {
        return $this->TABLE_NAME;
    }
    
    function fill($row) {
        $this->setId($row["id"]);
        $this->setArticleId($row["article_id"]);
        $this->setReviewerId($row["reviewer_id"]);
        $this->setAssignedById($row["assigned_by_id"]);
        $this->setReviewResultId($row["review_result_id"]);
    }
    
    
    function getArticleId() {
        return $this->articleId;
    }
    
    function getReviewerId() {
        return $this->reviewerId;
    }
    
    function getAssignedById() {
        return $this->assignedById;
    }
    
    function getReviewResultId() {
        return $this->reviewResultId;
    }
    
    function setArticleId($articleId) {
        $this->articleId = $articleId;
    }
    
    function setReviewerId($reviewerId) {
        $this->reviewerId = $reviewerId;
    }
    
    function setAssignedById($assignedById) {
        $this->assignedById = $assignedById;
    }
    
    function setReviewResultId($reviewResultId) {
        $this->reviewResultId = $reviewResultId;
    }
    
    /*
        Returns true is the review object has a review result assigned.
    */
    function isReviewed() {
        return $reviewResultId != null;
    }
    
}


?>