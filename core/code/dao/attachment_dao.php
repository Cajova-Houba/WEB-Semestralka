<?php
require_once ('db_connector.php');
require_once ('base_dao.php');
require_once ('classes/Attachment.class.php');
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

        $db = getConnection();

        $rowCount = $this->executeModifyStatement($db, $query, array(":path" => $attachment->getPath(),
                                                                     ":name" => $attachment->getName(),
                                                                     ":articleId" => $attachment->getArticleId()));
        $db = null;

        return $rowCount;
    }
}

?>