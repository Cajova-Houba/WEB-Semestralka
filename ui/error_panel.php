<?php
/*
 * If an error occurs, this script will display appropriate panel.
 *
 * $err variable has to be set in order to display the error message.
 */

if (isset($err)) {
    $errMsg = Errors::getErrorMessage($err);
?>
    <div class="alert alert-danger">
        <?php echo escapechars($errMsg); ?>
    </div>
<?php
}
?>
