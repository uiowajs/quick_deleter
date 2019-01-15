<?php

$module = new \UIOWA\QuickDeleter\QuickDeleter();

//error_log(json_encode($_POST));

if(isset($_POST['pid_box'])) {

    $module->Submit_Checkboxes();
}




//else if(isset($_POST['Custom_Box'])) {
//    $module->Display_Page();
//}

if ($_REQUEST['action'] == 'delete') {
    $module->Delete_Individual($_POST['pid']);
}
else if ($_REQUEST['action'] == 'restore') {
    $module->Restore_Individual($_POST['pid']);
}