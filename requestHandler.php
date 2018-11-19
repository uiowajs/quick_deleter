<?php

$module = new \UIOWA\QuickDeleter\QuickDeleter();

error_log(json_encode($_POST));

if ($_REQUEST['action'] == 'delete') {
    $module->Delete_Individual($_POST['pid']);
}
else if ($_REQUEST['action'] == 'restore') {
    $module->Restore_Individual($_POST['pid']);
}