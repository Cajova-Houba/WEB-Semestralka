<?php

require_once ('BaseObject.class.php');
/*
 * Attachment for article.
 *
 */

class Attachment extends BaseObject {
    const TABLE_NAME = 'file';

    private $path = '';
    private $name = '';
    private $articleId = -1;

    function tableName() {
        return Attachment::TABLE_NAME;
    }

    function fill($dtbRow) {
        $this->setId($dtbRow["id"]);
        $this->path = $dtbRow["path"];
        $this->name = $dtbRow["name"];
        $this->articleId = $dtbRow["article_id"];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }


}
?>