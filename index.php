<?php

//require_once APP_PATH_DOCROOT . 'ControlCenter/header.php';
$page = new HtmlPage();
$page->PrintHeaderExt();
include APP_PATH_VIEWS . 'HomeTabs.php';

$QuickDeleter = new \UIOWA\QuickDeleter\QuickDeleter();

if(isset($_POST['Hidden_Submit']))
{
    $QuickDeleter->Submit_Checkboxes();
}
else
{
    $QuickDeleter->Display_Page();  // Display_Projects_Table
}

?>










