<?php
/*
 * In case it's needed to display some info (successful registration, saving a new article...) this script
 * will display the panel with message.
 *
 * $info variable has to be set.
 */

if (isset($info)) {
    $infoMsg = Infos::getInfoMessage($info);
    if (!empty($infoMsg)) {
        ?>
        <div cla="row">
        <div class="alert alert-info">
            <?php echo escapechars($infoMsg); ?>
        </div>
        </div>
        <?php
    }
}
?>