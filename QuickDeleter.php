<?php

namespace UIOWA\QuickDeleter;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use DateTimeRC;
use Project;
use REDCap;



//  Session for returning submitted json/csv after deleting/restoring project.
    session_start();

    class QuickDeleter extends AbstractExternalModule
    {

        //  Displays header, home page, and table
        public function Display_Page()
        {

            if(SUPER_USER == 1) {

                // Display page header
                ?>
                <div align="center" id="div_Header">

                    <link href="<?= $this->getUrl("/resources/styles.css") ?>" rel="stylesheet" type="text/css"/>

                    <h1 style="text-align: center; padding-top:40px; padding-bottom:5px; color:white;" class="Main_Header">
                        <a href="<?php echo "//" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index"; ?>">Quick Deleter </a>
                    </h1>

                    <table id="Pages_Table">
                        <tr>
                            <td>
                                <a href="<?= $this->getUrl("index.php?tab=0") ?>">My Projects</a>
                            </td>
                            <td>
                                <a href="<?= $this->getUrl("index.php?tab=1") ?> ">All Projects</a>
                            </td>
                            <form name="Custom_Form" id="Custom_Form" method="POST" action="<?= $this->getUrl("index.php?tab=2") ?>">
                            <td>
                                <button class="Button_Link" type="submit" id="Custom_Page" name="Custom_Page">Custom</button>
                            </td>
                            <td>
                                <input id="Custom_Box" class="Button_Box" type='text' name='Custom_Box' value="" placeholder="Enter json or csv">
                            </td>
                            </form>
                        </tr>
                    </table>
                </div>
                <?php

                // Display home page
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://";
                $Current_URL = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                $Home_Page = $this->getUrl("index.php");
                if ($Current_URL == $Home_Page) {
                    ?>
                    <div>
                        <h2 style="text-align: center; padding-top:50px; color:white;">Quickly delete and restore projects</h2>

                        <h3 style="text-align: center; padding-top:50px; color:lightgrey;">

                        My projects:  Projects that the current user has permissions for</br>
</br>
                        Custom:  Enter comma separated project IDs or a json object from the Admin Dashboard.</br>


</h3>

                    </div>
                    <?php
                }

                global $conn;
                if (!isset($conn)) {
                    db_connect(false);
                }

                $tab = $_REQUEST['tab'];  //  Tabs for SQL array

                // Enables tablesorter
                ?>
                <script src="<?= $this->getUrl("/resources/tablesorter/jquery.tablesorter.min.js") ?>"></script>
                <script src="<?= $this->getUrl("/resources/tablesorter/jquery.tablesorter.widgets.min.js") ?>"></script>
                <script src="<?= $this->getUrl("/resources/tablesorter/widgets/widget-pager.min.js") ?>"></script>
                <script src="<?= $this->getUrl("/resources/tablesorter/parsers/parser-input-select.min.js") ?>"></script>
                <script src="<?= $this->getUrl("/resources/tablesorter/widgets/widget-output.min.js") ?>"></script>

                <link href="<?= $this->getUrl("/resources/tablesorter/tablesorter/theme.blue.min.css") ?>" rel="stylesheet">
                <link href="<?= $this->getUrl("/resources/tablesorter/tablesorter/jquery.tablesorter.pager.min.css") ?>" rel="stylesheet">
                <link href="<?= $this->getUrl("/resources/styles.css") ?>" rel="stylesheet" type="text/css"/>

                <script src="<?= $this->getUrl("/QuickDeleter.js") ?>"></script>
                <?php

                $Parsed_Custom = $this->Parse_Custom();
                $Parsed_Array = explode(",", $Parsed_Custom);
                $qMarks = str_repeat('?,', count($Parsed_Array) - 1) . '?';
                $Get_Integers = explode(",", $Parsed_Custom);
                $Integers = join(array_pad(array(), count($Get_Integers), "i"));

                // SQL
                $Project_Pages = array("
                    SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
                    CAST(CASE a.status
                         WHEN 0 THEN 'Development'
                         WHEN 1 THEN 'Production'
                         WHEN 2 THEN 'Inactive'
                         WHEN 3 THEN 'Archived'
                         ELSE a.status
                         END AS CHAR(50)) AS 'Statuses',
                    CAST(CASE a.purpose
                        WHEN 0 THEN 'Practice / Just for fun'
                        WHEN 4 THEN 'Operational Support'
                        WHEN 2 THEN 'Research'
                        WHEN 3 THEN 'Quality Improvement'
                        WHEN 1 THEN 'Other'
                        ELSE a.purpose
                        END AS CHAR(50)) AS 'Purpose',
                    CAST(creation_time AS date) AS 'New Creation Time', 
                    CAST(a.date_deleted AS date) AS 'New Date Deleted', 
                    CAST(last_logged_event AS date) AS 'New Last Event', 
                    DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
                    CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
                    CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS CHAR(50)) AS 'Flagged',
                    GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                    FROM redcap_projects as a
                    LEFT JOIN redcap_user_rights AS b
                    ON a.project_id=b.project_id
                    LEFT JOIN redcap_record_counts AS c
                    ON a.project_id=c.project_id
                    GROUP BY a.project_id
                    HAVING (GROUP_CONCAT((b.username) SEPARATOR ', ') LIKE '%".USERID."%')
                    ORDER BY a.project_id ASC  
                    "
                        ,
                            "
                    SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
                    CAST(CASE a.status
                         WHEN 0 THEN 'Development'
                         WHEN 1 THEN 'Production'
                         WHEN 2 THEN 'Inactive'
                         WHEN 3 THEN 'Archived'
                         ELSE a.status
                         END AS CHAR(50)) AS 'Statuses',
                    CAST(CASE a.purpose
                        WHEN 0 THEN 'Practice / Just for fun'
                        WHEN 4 THEN 'Operational Support'
                        WHEN 2 THEN 'Research'
                        WHEN 3 THEN 'Quality Improvement'
                        WHEN 1 THEN 'Other'
                        ELSE a.purpose
                        END AS CHAR(50)) AS 'Purpose',
                    CAST(creation_time AS date) AS 'New Creation Time', 
                    CAST(a.date_deleted AS date) AS 'New Date Deleted', 
                    CAST(last_logged_event AS date) AS 'New Last Event', 
                    DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
                    CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
                    CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS CHAR(50)) AS 'Flagged',
                    GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                    FROM redcap_projects as a
                    LEFT JOIN redcap_user_rights AS b
                    ON a.project_id=b.project_id
                    LEFT JOIN redcap_record_counts AS c
                    ON a.project_id=c.project_id
                    GROUP BY a.project_id
                    ORDER BY a.project_id ASC
                    ",
                            "
                    SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
                    CAST(CASE a.status
                         WHEN 0 THEN 'Development'
                         WHEN 1 THEN 'Production'
                         WHEN 2 THEN 'Inactive'
                         WHEN 3 THEN 'Archived'
                         ELSE a.status
                         END AS CHAR(50)) AS 'Statuses',
                    CAST(CASE a.purpose
                        WHEN 0 THEN 'Practice / Just for fun'
                        WHEN 4 THEN 'Operational Support'
                        WHEN 2 THEN 'Research'
                        WHEN 3 THEN 'Quality Improvement'
                        WHEN 1 THEN 'Other'
                        ELSE a.purpose
                        END AS CHAR(50)) AS 'Purpose',
                    CAST(creation_time AS date) AS 'New Creation Time', 
                    CAST(a.date_deleted AS date) AS 'New Date Deleted', 
                    CAST(last_logged_event AS date) AS 'New Last Event', 
                    DATEDIFF(now(), last_logged_event) AS 'Days Since Last Event',
                    CAST(DATE_ADD(a.date_deleted, INTERVAL 30 DAY) AS date) AS 'New Final Delete Date',
                    CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS CHAR(50)) AS 'Flagged',
                    GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                    FROM redcap_projects as a
                    LEFT JOIN redcap_user_rights AS b
                    ON a.project_id=b.project_id
                    LEFT JOIN redcap_record_counts AS c
                    ON a.project_id=c.project_id
                    WHERE a.project_id IN (" . $qMarks . ")  
                    GROUP BY a.project_id
                    ORDER BY a.project_id ASC
                    "
                );

            ?>

            <form name="Form" id="Form" action="<?= $this->getUrl("index.php") ?>" method="POST">
            <?php

            // Displays submit form if the page is My or All projects and not home page.
            if(($tab == 0 || $tab == 1) && $Current_URL != $this->getUrl("index.php")) {

                $this->Display_Table_Header();

            }

            // Prepare sql if json or csv
            if($tab == 2) {
                $stmt = $conn->prepare($Project_Pages[$tab]);
                $stmt->bind_param($Integers, ...$Parsed_Array);
                $stmt->execute();
                $Get_Result = $stmt->get_result();
                $num_rows = mysqli_num_rows($Get_Result);

            //  If the page is json or csv and a value was submitted, display submit form, otherwise show error no results.

                if ($num_rows != "") {

                    $this->Display_Table_Header();

                }  // End if ($Parsed_json != "")
                else {
                    ?>
                    <h5 style="text-align: center; padding-top:100px; padding-bottom:5px;  color:white;">Error, no results.  Please enter a value</h5>
                    <?php
                }

                // Builds HTML rows and displays sql results for submitted json and csv.
                while ($row = $Get_Result->fetch_assoc()) {

                    $this->Build_HTML_Table($row);

                }  // End while loop
            }
            elseif($tab == 0 || $tab == 1) {
                // Results for My or All Projects SQL query.
                $Result = db_query($Project_Pages[$tab]);

                // Builds HTML rows and displays sql results for My Projects and All Projects.
                while ($row = db_fetch_assoc($Result))  // $sqlGetAllProjects
                {

                    $this->Build_HTML_Table($row);
                }  // End while loop for My/All projects
            }

                    // Logs when a super user accesses quick deleter
                    //REDCap::logEvent("Super user, " . USERID . ", accessed the Quick Deleter external module", NULL, NULL, NULL, NULL, NULL);

                }  // End if(SUPER_USER == 1)
                else {
                    // Echos needed to display message under REDCap navbar
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    REDCap::logEvent("Non super user, " . USERID . ", tried to access the Quick Deleter external module", NULL, NULL, NULL, NULL, NULL);
                    echo "This function is for super users only";
                    echo "<br>";
                }
            }  // End Display_Page()

        public function Display_Table_Header() {

            $tab = $_REQUEST['tab'];
            $Custom_Type = $this->Get_Custom_Type();
            echo "<br>";
            ?>

            <div>
                <?php

                if($tab == 0) {
                    ?>
                    <h2 style="text-align: center; padding-top:5px; padding-bottom:5px;  color:white;">My Projects</h2>
                    <?php
                }
                elseif($tab == 1) {
                    ?>
                    <h2 style="text-align: center; padding-top:5px; padding-bottom:5px; color:white;">All Projects</h2>
                    <?php
                }
                elseif($tab == 2 && $Custom_Type == "json") {
                    ?>
                    <h2 style="text-align: center; padding-top:5px; padding-bottom:5px; color:white;">Custom json</h2>
                    <?php
                }
                elseif($tab == 2 && $Custom_Type == "csv") {
                    ?>
                    <h2 style="text-align: center; padding-top:5px; padding-bottom:5px; color:white;">Custom csv</h2>
                    <?php
                }
                ?>
            </div>

            <!-- Submit button -->
            <div align="center">
                <table id='Submit_Table'>
                    <tr>
                        <td>
                            <input class="reset_button" type="reset" name="reset" id="reset" >
                        </td>

                        <?php


                        if($this->getSystemSetting("submit-button-colors")) {
                            $Submit_Restore_Button_Color = "submit_restore_button";
                        }
                        else {
                            $Submit_Restore_Button_Color = "";
                        }

                        if($this->getSystemSetting("submit-button-colors")) {
                            $Submit_Delete_Button_Color = "submit_delete_button";
                        }
                        else {
                            $Submit_Delete_Button_Color = "";
                        }

                        ?>
                        <td>
                            <button type="submit" id='Hidden_Submit' name='Hidden_Submit' hidden >Send</button>
                        </td>

                        <?php



                        if($this->getSystemSetting("restore-checkboxes") && $this->getSystemSetting("delete-checkboxes")) {
                            ?>
                        <td>
                            <button data-toggle="modal" data-target="#Confirmation_Modal" type="button" id='send_button' name='send_button' >Submit</button>
                        </td>
                        <?php
                        } elseif($this->getSystemSetting("restore-checkboxes") && !self::getSystemSetting("delete-checkboxes")) {
                            ?>
                        <td>
                            <button data-toggle="modal" data-target="#Confirmation_Modal" type="button" class="<?php echo $Submit_Restore_Button_Color ?> " id='send_button' name='send_button' data-toggle="modal" data-target="#Confirmation_Modal">Restore</button>
                        </td>
                        <?php
                        }
                        elseif(!self::getSystemSetting("restore-checkboxes") && $this->getSystemSetting("delete-checkboxes")) {
                            ?>
                        <td>
                            <button data-toggle="modal" data-target="#Confirmation_Modal" type="button" class="<?php echo $Submit_Delete_Button_Color ?>" id='send_button' name='send_button' data-toggle="modal" data-target="#Confirmation_Modal">Delete</button>
                        </td>


                        <?php
                        }



                        ?>

                        <td>
                            <input id='PID_Box' type='text' name='PID' hidden readonly>
                        </td>
                    </tr>
                </table>
            </div>



            <!-- Confirmation Modal -->
            <div id="Confirmation_Modal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Attention</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <b>Confirm that the following projects should be modified:</b>
                    <br/>
                    <br/>
                    <div id="Delete_Projects_Div">
                    </div>
                    <br/>
                    <div id="Restore_Projects_Div">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="Accept_Send_Checkboxes" name="Accept_Send_Checkboxes" type="button" class="btn btn-default" data-dismiss="modal">Accept</button>
                    <button id="Cancel_Button_Checkboxes" name="Cancel_Button_Checkboxes" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  </div>
                </div>

              </div>
            </div>

                        <!-- Confirmation Modal -->
            <div id="Confirmation_Modal_Button" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Attention</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">

                    <div id="Modify_Individual_Project_Div">

                    </div>

                  </div>
                  <div class="modal-footer">
                    <button id="Accept_Send_Button" name="Accept_Send_Button" type="button" class="btn btn-default" data-dismiss="modal">Accept</button>
                    <button id="Cancel_Button_Individual" name="Cancel_Button" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  </div>
                </div>

              </div>
            </div>





            <!-- Pager -->
            <div id="pager" class="pager" align="center">

                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/first.png") ?>" class="first"/>
                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/prev.png") ?>" class="prev"/>
                <!-- the "pagedisplay" can be any element, including an input -->
                <span class="pagedisplay" data-pager-output-filtered="{startRow:input} &ndash; {endRow} / {filteredRows} of {totalRows} total rows"></span>
                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/next.png") ?>" class="next"/>
                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/last.png") ?>" class="last"/>

                <select class="pagesize">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

            </div>

            <!-- Create table  -->
            <div id="id_projects_table" align="center">
                <table id='Projects_Table' class='tablesorter'>

                     <?php

                     $tab = $_REQUEST['tab'];

                     if ($tab == 0 || $tab == 1) {
                        ?>

                     <thead>
                        <tr>
                            <th data-sorter="false" data-filter="false"></th> <?php
                    } elseif ($tab == 2 ) {


                         if(self::getSystemSetting('restore-checkboxes') || self::getSystemSetting('delete-checkboxes')) {
                             ?>
                            <thead>
                            <tr>
                            <th data-sorter="false" style="text-align:center" data-filter="false"><input name="check_all" id="check_all" type="checkbox"></th> <?php

                         }
                         else {
                             ?>
                                                     <thead>
                            <tr>
                            <th data-sorter="false" data-filter="false"></th>
                             <?php
                         }
                         }

                        ?>

                            <th style="text-align:center"><b>PID</b></th>
                            <th style="text-align:center"><b>Project Name</b></th>
                            <th style="text-align:center"><b>Purpose</b></th>
                            <th style="text-align:center"><b>Status</b></th>
                            <th style="text-align:center"><b>Records</b></th>
                            <th style="text-align:center"><b>Users</b></th>
                            <th style="text-align:center"><b>Created</b></th>
                            <th style="text-align:center"><b>Last Event</b></th>
<!--                            <th style="text-align:center"><b>Days Since Event</b></th>-->
                            <th style="text-align:center"><b>Deleted</b></th>
                            <th style="text-align:center"><b>Final Delete</b></th>

                            <?php

                            if(self::getSystemSetting('hide-action-column') || !self::getSystemSetting('restore-checkboxes') || !self::getSystemSetting('delete-checkboxes')) {

                            }
                            else {

                                ?>
                                <th data-sorter="false" data-filter="false" style="text-align:center"><b>Action</b></th>
                                <?php
                            }
                             ?>

                            <!--                                <th style="text-align:center"><b>Days Until Delete</b></th>-->
                        </tr>
                    </thead>









<?php



        }  // End Display_Table_Header()

        // Checks if custom type is json or csv
        public function Get_Custom_Type() {

            $Custom_Box = $_POST['Custom_Box'];

            if(isset($Custom_Box)) {
                if(substr($Custom_Box, 0, 1) == "[") {
                    $Custom_Type = "json";
                    $_SESSION['Custom_Type'] = $Custom_Type;
                }
                elseif(is_numeric(substr($Custom_Box, 0 ,1)) == true) {
                    $Custom_Type = "csv";
                    $_SESSION['Custom_Type'] = $Custom_Type;
                }
            }
            elseif(isset($_SESSION['Custom_Type'])) {
                $Custom_Type = $_SESSION['Custom_Type'];
            }

            return $Custom_Type;
        }  // End Get_Custom_Type()

        // Parses custom json and csv
        public function Parse_Custom() {

            $Custom_Box = $_POST['Custom_Box'];
            $Get_Custom_Type = $this->Get_Custom_Type();

            if(isset($Custom_Box)) {
                if($Get_Custom_Type == "json") {
                    $Custom_Value = $Custom_Box;

                    $json_decode = json_decode($Custom_Value);

                    $Custom_PID = array();
                    foreach ($json_decode AS $values) {
                        $Custom_PID[] = $values->PID;
                    }

                    $Custom_Value = implode(",", $Custom_PID);
                    $_SESSION['Custom_Value'] = $Custom_Value;
                }
                elseif($Get_Custom_Type == "csv") {
                    $Custom_Value = $Custom_Box;
                    $_SESSION['Custom_Value'] = $Custom_Box;
                }
            } elseif(isset($_SESSION['Custom_Value'])) {
                $Custom_Value = $_SESSION['Custom_Value'];
            }

            return $Custom_Value;
        }  // End Parse_Custom()

        // Turn username list into multiple values
        public function Format_Usernames($row) {

            $userIDlist = explode(", ", $row['Users']);
            $formattedUsers = array();

            foreach ($userIDlist as $index=>$userID)
            {
                $formattedUsername = $userID;
                $formattedUsername = $this->Username_Links($formattedUsername);

                array_push($formattedUsers, $formattedUsername . ($index < count($userIDlist) - 1 ? '<span class=\'hide-in-table\'>, </span>' : '')
                );
            }

            $userCell = implode("<br>", $formattedUsers);

            return $userCell;
        }

        // Creates username link that goes to user page in control center
        public function Username_Links($userID) {

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://";

            $urlString = sprintf("//" . "%s%sControlCenter/view_users.php?username=%s",  // Browse User Page
                SERVER_NAME,
                APP_PATH_WEBROOT,
                $userID);

        $userLink = sprintf("<a href=\"%s\"
                          target=\"_blank\">%s</a>",
            $urlString, $userID);

        return $userLink;
        }

        // Builds project table
        public function Build_HTML_Table($row) {
            ?>

            <tr id="<?php echo $row['New Date Deleted']; ?>">


            <?php ;

            if ($row['New Date Deleted'] == "") {
                if($this->getSystemSetting("row-colors")) {
                    $Row_Class = "Active_Row_Colored";
                } else {
                    $Row_Class = "Active_Row_Uncolored";
                }
                if($this->getSystemSetting("button-colors")) {
               $Button_Color = "Delete_PID_Button";
                } else {
                    $Button_Color = "";
                }
//                if($this->getSystemSetting("checkbox-colors")) {
//                $Checkbox_Color = "Delete_PID_Checkbox";
//                } else {
//                    $Checkbox_Color = "PID_Checkbox";
//                }
            } else {
                if($this->getSystemSetting("row-colors")) {
                    $Row_Class = "Deleted_Row_Colored";
                } else {
                    $Row_Class = "Deleted_Row_Uncolored";
                }
                if($this->getSystemSetting("button-colors")) {
                $Button_Color = "Restore_PID_Button";
                } else {
                $Button_Color = "";
                }
//                if($this->getSystemSetting("checkbox-colors")) {
//                $Checkbox_Color = "Restore_PID_Checkbox";
//                } else {
//                    $Checkbox_Color = "PID_Checkbox";
//                }
            }




                if ($row['New Date Deleted'] == "") // If date_delete is null, color row green, otherwise red.  // also works:  $row['New Date Deleted'] == ""
                {

                    if($this->getSystemSetting("delete-checkboxes")) {

                ?>

                        <td align='center' class="<?php echo $Row_Class; ?>">
                            <input class="PID_Checkbox" id="<?php echo $row['Flagged']; ?>" type='checkbox' name="Select_Project" value=<?php echo $row['project_id']; ?>>
                        </td>

                <?php

                    }
                    else {
                ?>
                <td align='center' class="<?php echo $Row_Class; ?>">
                <form name="Delete_Row_Form" id="Delete_Row_Form" action="" method="POST" >
                    <button data-toggle="modal" data-target="#Confirmation_Modal_Button" class="<?php echo $Button_Color ?>" id="Delete_PID_Button_<?php echo $row['project_id'] ?>" type='button' name="Delete_PID_Button" value=<?php echo $row['project_id']; ?>>Delete</button>
                    <button hidden type="submit" class="<?php echo $Button_Color ?>" id="Delete_PID_Submit_<?php echo $row['project_id'] ?>" type='button' name="Delete_PID_Button" value=<?php echo $row['project_id']; ?>>Delete</button>
                    </form>
                </td>



                <?php

                    }

                } else {

                    if($this->getSystemSetting("restore-checkboxes")) {
                        ?>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <input class="PID_Checkbox" id="<?php echo $row['Flagged']; ?>" type='checkbox' name="Select_Project" value=<?php echo $row['project_id']; ?>>
                </td>
                <?php
                    }
                    else {
                ?>
                <td align='center' class="<?php echo $Row_Class; ?>">
                <form name="Restore_Row_Form" id="Restore_Row_Form" action="" method="POST" >
                    <button data-toggle="modal" data-target="#Confirmation_Modal_Button" class="<?php echo $Button_Color ?>" id="Restore_PID_Button_<?php echo $row['project_id'] ?>" type='button' name="Restore_PID_Button" value=<?php echo $row['project_id']; ?>>Restore</button>
                    <button hidden type="submit" class="<?php echo $Button_Color ?>" id="Restore_PID_Submit_<?php echo $row['project_id'] ?>" type='button' name="Restore_PID_Button" value=<?php echo $row['project_id']; ?>>Restore</button>
                    </form>
                </td>

                <?php
                    }

                }
                ?>

                <td align='center' class="<?php echo $Row_Class; ?>">
                    <a href="<?php echo "//" . SERVER_NAME . APP_PATH_WEBROOT . "ControlCenter/edit_project.php?project=" . $row['project_id']; ?>" target="_blank"> <?php echo $row['project_id']; ?></a>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <a href="<?php echo "//" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/index.php?pid=" . $row['project_id']; ?>" target="_blank" > <?php echo $row['app_title']; ?> </a>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <?php echo $row['Purpose']; ?>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <a href="<?php echo "//" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/other_functionality.php?pid=" . $row['project_id']; ?>" target="_blank" ><?php echo $row['Statuses']; ?></a>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <a href="<?php echo "//" . SERVER_NAME . APP_PATH_WEBROOT . "DataExport/index.php?pid=" . $row['project_id'] . "&report_id=ALL"; ?>" target="_blank" > <?php echo $row['record_count']; ?></a>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                   <?php

                    echo $this->Format_Usernames($row);

                    ?>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <?php echo $row['New Creation Time']; ?>
                </td>
               <td align='center' class="<?php echo $Row_Class; ?>">
                    <a href="<?php echo "//" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['New Last Event']; ?></a>
                </td>
<!--                <td align='center' class="color" --><?php //echo $Row_Color ?><!-->
<!--                    <a href="--><?php //echo $protocol . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row['project_id']; ?><!--" target="_blank" > --><?php //echo $row['Days Since Last Event']; ?><!--</a>-->
<!--                </td>-->
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <?php echo $row['New Date Deleted']; ?>
                </td>
                <td align='center' class="<?php echo $Row_Class; ?>">
                    <?php echo $row['New Final Delete Date']; ?>
                </td>
                <?php

                if(self::getSystemSetting('hide-action-column') || !self::getSystemSetting('restore-checkboxes') || !self::getSystemSetting('delete-checkboxes')) {



                }
                else {
                ?>

                <td align='center' id="Row_Action" class="<?php echo $Row_Class; ?>">
                  <?php
                    }
                    ?>
                </td>
                <?php ;
                ?>

            </tr>

            <?php

            if(isset($_POST['Restore_PID_Button'])) {
                $this->Restore_Individual($_POST['Restore_PID_Button']);
            }

           if(isset($_POST['Delete_PID_Button'])) {
                $this->Delete_Individual($_POST['Delete_PID_Button']);
            }


        }  // End Build_HTML_Table();

        public function Restore_Individual($PID) {

            if(SUPER_USER == 1) {

                $Pre_Value = $this->Get_Value_Buttons($PID);

                global $conn;
                if (!isset($conn)) {
                    db_connect(false);
                }

                $sql =
                "
                UPDATE redcap_projects
                SET date_deleted = NULL
                WHERE project_id = ?
                ";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $PID);
                $stmt->execute();


                $Post_Value = $this->Get_Value_Buttons($PID);

                if($Pre_Value != $Post_Value) {
//                    if ($Post_Value['date_deleted'] == NULL) {
                        REDCap::logEvent("Project restored via Quick Deleter", NULL, NULL, NULL, NULL, $PID);
//                    }  // End of if (date_delete == NULL)

                }




            header("Location: {$_SERVER['HTTP_REFERER']}");


                }
        }


                public function Delete_Individual($PID) {

                $Pre_Value = $this->Get_Value_Buttons($PID);

                global $conn;
                if (!isset($conn)) {
                    db_connect(false);
                }




          $sql =
                "
                UPDATE redcap_projects
                SET date_deleted = '" . NOW . "'
                WHERE project_id = ?
                ";


            $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $PID);
                $stmt->execute();


                $Post_Value = $this->Get_Value_Buttons($PID);

                if($Pre_Value != $Post_Value) {
//                    if ($Post_Value['date_deleted'] == NULL) {
                        REDCap::logEvent("Project deleted via Quick Deleter", NULL, NULL, NULL, NULL, $PID);
//                    }  // End of if (date_delete == NULL)

                }




          header("Location: {$_SERVER['HTTP_REFERER']}");

                }


        // This function is called on form submit.  Gets pre values, executes update query, gets post values, adds project update to REDCap Activity Log.
        public function Submit_Checkboxes()
        {
            if(SUPER_USER == 1) {

                $Pre_Values = $this->Get_Values();

                global $conn;
                if (!isset($conn)) {
                    db_connect(false);
                }

                // Converts submitted PID_Box string to array for bind_param()
                $PID_Array = explode(",", $this->Get_PID());

                // Forms comma separated question mark placeholder string for SQL WHERE IN () query.  e.g. ?,?,?
                $qMarks = str_repeat('?,', count($PID_Array) - 1) . '?';

                // Forms int placeholder string for bind_param().  e.g. 'iii'
                $Get_Integers = explode(",", $this->Get_PID());
                $Integers = join(array_pad(array(), count($Get_Integers), "i"));

                $sqlUpdateProject = "
                UPDATE redcap_projects
                SET date_deleted = IF(date_deleted IS NULL, '" . NOW . "', NULL)
                WHERE project_id IN (" . $qMarks . ")
                ";

                // https://stackoverflow.com/questions/3703180/a-prepared-statement-where-in-query-and-sorting-with-mysql/45905752#45905752.
                $stmt = $conn->prepare($sqlUpdateProject);
                $stmt->bind_param($Integers, ...$PID_Array);
                $stmt->execute();
                $stmt->close();

                $Post_Values = $this->Get_Values();

                // Adds logging to REDCap
                foreach ($Pre_Values AS $Pre_Value) {
                    foreach ($Post_Values AS $Post_Value) {
                        if ($Post_Value['project_id'] == $Pre_Value['project_id']) {
                            if ($Post_Value != $Pre_Value) {
                                if ($Post_Value['date_deleted'] == NULL) {
                                    REDCap::logEvent("Project restored via Quick Deleter", NULL, NULL, NULL, NULL, $Post_Value['project_id']);
                                }  // End of if (date_delete == NULL)
                                else {
                                    REDCap::logEvent("Project deleted via Quick Deleter", NULL, NULL, NULL, NULL, $Post_Value['project_id']);
                                }  // End of else (date_deleted != NULL)
                            }  // End of if ($Post_Value == $Pre_Value)
                            else {
                                REDCap::logEvent("Quick Deleter encountered an error for projects " . $Post_Value['project_id'], NULL, NULL, NULL, NULL, $Post_Value['project_id']);
                            } // End of else (project_id != project_id)
                        }  // End of if (project_id == project_id)
                    }  // End of foreach Post Values
                }  // End of foreach Pre Values

                    header("Location: {$_SERVER['HTTP_REFERER']}");
                }  // End if(SUPER_USER == 1)
                else {
                    REDCap::logEvent("Non super user, " . USERID . ", tried to delete/restore projects via the Quick Deleter external module", NULL, NULL, NULL, NULL, NULL);
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "This function is for super users only";
                    echo "<br>";
            }  // End super user check
        }  // End of Submit_Checkboxes()



        // Gets PIDs for rows that were checked
        public function Get_PID()
        {
            $PID_Box = $_POST['PID'];

            return $PID_Box;
        }  // End of Get_PID()

        // Gets value of date_deleted.  Used for both pre and post values
        public function Get_Values()
        {

            global $conn;
            if (!isset($conn)) {
                db_connect(false);
            }

            $PID_Array = explode(",", $this->Get_PID());
            $qMarks = str_repeat('?,', count($PID_Array) - 1) . '?';
            $Get_Integers = explode(",", $this->Get_PID());
            $Integers = join(array_pad(array(), count($Get_Integers), "i"));

            $sql_Get_Values = "
            SELECT project_id, date_deleted
            FROM redcap_projects
            WHERE project_id IN (" . $qMarks . ")
            ";

            $stmt = $conn->prepare($sql_Get_Values);
            $stmt->bind_param($Integers, ...$PID_Array);
            $stmt->execute();
            $Get_Result = $stmt->get_result();

            $Results = array();
            while ($Values = $Get_Result->fetch_assoc()) {
                $Results[] = $Values;
            }
            return $Results;
        }  // End Get_Values()

        public function Get_Value_Buttons($PID) {

            global $conn;
            if (!isset($conn)) {
                db_connect(false);
            }

            $sql_Get_Values = "
            SELECT project_id, date_deleted
            FROM redcap_projects
            WHERE project_id = ?
            ";

            $stmt = $conn->prepare($sql_Get_Values);
            $stmt->bind_param("i", $PID);
            $stmt->execute();
            $Get_Result = $stmt->get_result();

            $Results = array();
            while ($Values = $Get_Result->fetch_assoc()) {
                $Results[] = $Values;
            }

            return $Results;

        }



}  // End class


