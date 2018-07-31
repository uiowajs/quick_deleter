<?php

require_once APP_PATH_DOCROOT . 'ControlCenter/header.php';

$QuickDeleter = new \UIOWA\QuickDeleter\QuickDeleter();

if(isset($_POST['submit']))
    {
    $QuickDeleter->Delete_or_Undelete_Project();
        echo "<meta http-equiv='refresh' content='0'>";  // Refreshes page after submit
}
else
    {
    $QuickDeleter->DisplayProjectsTable();
}
?>