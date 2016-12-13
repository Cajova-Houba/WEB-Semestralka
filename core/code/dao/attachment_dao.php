<?php
require_once ('db_connector.php');
require_once ('base_dao.php');
if(!defined('__CORE_ROOT__')) {
    //get one dir up - use it when require_once classes
    define('__CORE_ROOT__', dirname(dirname(__FILE__)));
}
require_once (__CORE_ROOT__.'/classes/Attachment.class.php');
if(!defined('__SERVER_ROOT__')) {
    //get two dirs up - to get to the upload folder
    define('__SERVER_ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
}


/**
 * Attachment dao.
 */

class AttachmentDao extends BaseDao {

    function __construct()
    {
        parent::__construct(Attachment::TABLE_NAME);
    }

    function get($id)
    {
        $row = parent::get($id);
        $at = new Attachment();
        $at->fill($row);

        return $at;
    }

    function getAll()
    {
        $rows = parent::getAll();
        $attachments = [];

        foreach ($rows as $row) {
            $a = new Attachment();
            $a->fill($row);
            $attachments[] = $a;
        }

        return $attachments;
    }

    function getAttachmentsForArticle($articleId) {
        $query = "SELECT * FROM ".Attachment::TABLE_NAME." WHERE article_id=:aid";

        $db = getConnection();

        $rows = $this->executeSelectStatement($db, $query, array(":aid" => $articleId));
        $db = null;

        $attachments = [];

        foreach ($rows as $row) {
            $a = new Attachment();
            $a->fill($row);
            $attachments[] = $a;
        }

        return $attachments;
    }

    function save($attachment) {
        $query = "INSERT INTO ".Attachment::TABLE_NAME."(path, name, article_id) VALUES(:path,:name,:articleId)";

        $db = getConnection();;

        $rowCount = $this->executeModifyStatement($db, $query, array(":path" => $attachment->getPath(),
                                                                     ":name" => $attachment->getName(),
                                                                     ":articleId" => $attachment->getArticleId()));
        $db = null;

        return $rowCount;
    }

    /*
     * Saves a new file and saves its meta data.
     * Returns 1 if ok, 0 if error.
     */
    function saveFile($file, $articleId) {

        var_dump($file);

        // save file
        $path = __SERVER_ROOT__.'/upload/';
        $name = sha1_file($file["tmp_name"]);
        if(!move_uploaded_file($file["tmp_name"], $path.$name)) {
            return 0;
        }

        // save metadata
        $attachment = new Attachment();
        $attachment->setArticleId($articleId);
        $attachment->setName($name);
        $attachment->setPath($path);
        return $this->save($attachment);
    }

    /*
     * Removes attachments assigned to some article.
     */
    function removeByArticle($articleId) {
        $q = "SELECT FROM ".Attachment::TABLE_NAME." WHERE article_id=:aid";

        $db = getConnection();
        $rows = $this->executeSelectStatement($db, $q, array("aid" => $articleId));
        $db = null;

        foreach ($rows as $row) {
            $this->remove($row["id"]);
        }
    }
}

?>