<?php

namespace UIOWA\QuickDeleter;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use DateTimeRC;
use Project;
use REDCap;

//if(SUPER_USER == 1) {

//  Session for returning submitted json after deleting/undeleting project.
    session_start();

    class QuickDeleter extends AbstractExternalModule
    {

        //  Displays title and page links
        public function Display_Header()
        {
            ?>
            <div align="center" id="div_Header">

                <link href="<?= $this->getUrl("/resources/styles.css") ?>" rel="stylesheet" type="text/css"/>

                <h1 style="text-align: center; padding-top:30px; padding-bottom:5px; color:white;" class="Main_Header">
                    <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index"; ?>">Quick Deleter </a>
                </h1>

                <table id="Pages_Table">
                    <tr>
                        <td>
                            <a href="<?= $this->getUrl("index.php?tab=0") ?>">My Projects</a>
                        </td>
                        <td>
                            <a href="<?= $this->getUrl("index.php?tab=1") ?> ">All Projects</a>
                        </td>
                        <form name="Custom_Form_json" id="Custom_Form_json" method="POST" action="<?= $this->getUrl("index.php?tab=2") ?>">
                            <td>
                                <button class="Button_Link" type="submit" id="Custom_Page_json" name="Custom_Page_json">json</button>
                            </td>
                            <td>
                                <input id="Custom_Box_json" class="Button_Box" type='text' name='Custom_Box_json' value="">
                            </td>
                        </form>
                        <form name="Custom_Form_csv" id="Custom_Form_csv" method="POST" action="<?= $this->getUrl("index.php?tab=3") ?>">
                            <td>
                                <button class="Button_Link" type="submit" id="Custom_Page_csv" name="Custom_Page_csv">csv</button>
                            </td>
                            <td>
                                <input id="Custom_Box_csv" class="Button_Box" type='text' name='Custom_Box_csv' value="">
                            </td>
                        </form>
                    </tr>
                </table>
            </div>
            <?php
        }

        //  Displays header, home page, and table.  Contains javascript
        public function Display_Page()
        {

            if(SUPER_USER == 1) {


                $this->Display_Header();
                $this->Display_Home_Page();
                $this->Display_Table();

                // Logs when a super user accesses quick deleter
                REDCap::logEvent("Super user, " . USERID . ", accessed the Quick Deleter external module", NULL, NULL, NULL, NULL, NULL);

                ?>

                <script type="text/javascript">

                    // Puts comma separated values of checkboxes in PID_Box.
                    $("form[name=Form]").on("change", "input[type=checkbox]", function () {
                        var values = $.map($("input[type=checkbox]:checked"), function (pid) {
                            return pid.value;
                        });
                        $("form[name=Form]").find("input[id=PID_Box]").val(values);
                    });

                    //  Highlight row when box checked
                    $(".PID_Checkbox").on('change', function () {
                        if ($(this).is(':checked'))
                        // console.log($(this).attr('id'));
                            if ($(this).prop('id') === '0')
                            // console.log($(this).attr('id'));
                                $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0, 0.7)").css({fontWeight: this.checked ? 'bold' : 'normal'});
                            else
                            // console.log($(this).attr('id'));
                                $(this).closest('tr').css("backgroundColor", "rgba(0, 255, 0, 1)").css({fontWeight: this.checked ? 'bold' : 'normal'});
                        else
                        // console.log("Hi");
                            $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked ? 'bold' : 'normal'});
                    });

                    // Highlights all rows when check all box checked
                    $(document).ready(function () {
                        $("#check_all").on('change', function () {
                            var PID_Checkboxes = $(".PID_Checkbox");
                            console.log($(this));
                            PID_Checkboxes.each(function () {

                                // $(this).toggle($(this).checked);
                                console.log($(this).checked);
                                if ($(this).is(':checked'))

                                // console.log($(this).attr('id'));
                                    if ($(this).prop('id') === '0')
                                    // console.log($(this).attr('id'));
                                        $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0, 0.7)").css({fontWeight: this.checked ? 'bold' : 'normal'});
                                    else
                                    // console.log($(this).attr('id'));
                                        $(this).closest('tr').css("backgroundColor", "rgba(0, 255, 0, 1)").css({fontWeight: this.checked ? 'bold' : 'normal'});
                                else
                                // console.log("Hi");
                                    $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked ? 'bold' : 'normal'});
                            });
                        })
                    });

                    // Removes checked row color on form reset
                    function Clear_Row_Styling() {
                        $('tr').css("backgroundColor", "").css({fontWeight: 'normal'});
                    }

                    // Avoids having to resubmit the form on page refresh
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>

                <?php

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
        }

        //  Displays home page
        public function Display_Home_Page()
        {

            $Current_URL = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $Home_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index";
            if ($Current_URL == $Home_Page) {
                ?>
                <div>
                    <h2 style="text-align: center; padding-top:50px; color:white;">Quickly delete and undelete projects</h2>
                </div>
                <?php
            }
        }

        //  Displays page limit dropdown
        public function Display_Pager()
        {
            ?>
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
                    <option value="500">500</option>
                    <option value="all">All Rows</option>
                </select>

            </div>
            <?php
        }

        //  Displays submit button for deleting/undeleting projects
        public function Display_Submit_Button()
        {
            ?>
            <div align="center">
                <table id='Submit_Table'>
                    <tr>
                        <td>
                            <input class="reset_button" type="reset" name="reset" id="reset" onclick="Clear_Row_Styling()">
                        </td>
                        <td>
                            <input class="submit_button" type='submit' id='submit' name='submit'>
                        </td>
                        <td>
                            <input id='PID_Box' type='text' name='PID' hidden readonly>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        }  // End Display_Submit_Button()

        //  Contains source files for table sorter
        public function Tablesorter_Includes()
        {
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
        }  // End Tablesorter_Includes()

        //  Displays table headers
        public function Table_Header()
        {

            // Page url variables
            $Current_URL = "http://" . SERVER_NAME . $_SERVER['REQUEST_URI'];
            $My_Projects_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=0";
            $All_Projects_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=1";
            $json_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=2";
            $csv_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=3";

             if ($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page) {
                ?>
             <thead>
                <tr>
                    <th data-filter="false"></th> <?php
            } elseif ($Current_URL == $json_Page || $Current_URL == $csv_Page) {
                ?>
            <thead>
                <tr>
                    <th style="text-align:center" data-filter="false"><input name="check_all" id="check_all" type="checkbox"></th> <?php
            }
            ?>
                    <th style="text-align:center"><b>PID</b></th>
                    <th style="text-align:center"><b>Project Name</b></th>
                    <th style="text-align:center"><b>Purpose</b></th>
                    <th style="text-align:center"><b>Status</b></th>
                    <th style="text-align:center"><b>Record Count</b></th>
                    <th style="text-align:center"><b>Users</b></th>
                    <th style="text-align:center"><b>Date Created</b></th>
                    <th style="text-align:center"><b>Last Event Date</b></th>
                    <th style="text-align:center"><b>Days Since Event</b></th>
                    <th style="text-align:center"><b>Delete Flagged</b></th>
                    <th style="text-align:center"><b>Final Delete</b></th>
                    <!--                                <th style="text-align:center"><b>Days Until Delete</b></th>-->
                </tr>
            </thead>
            <?php
        }  // End Table_Header()

        //  Takes user submitted json and parses it into PIDs.  Stores in session variable to retain after deleting/undeleting projects.
        public function Parse_Posted_Json()
        {
            $Custom_Box_json = $_POST['Custom_Box_json'];

            if (isset($Custom_Box_json)) {
                $Posted_json = $Custom_Box_json;
                $_SESSION['Custom_json'] = $Custom_Box_json;
            } elseif (isset($_SESSION['Custom_json'])) {
                $Posted_json = $_SESSION['Custom_json'];
            }

            $Decoded_json = json_decode($Posted_json);

            $Custom_PID = array();
            foreach ($Decoded_json AS $values) {
                $Custom_PID[] = $values->PID;
            }

            $Parsed_json = implode(",", $Custom_PID);
            return $Parsed_json;
        }  // End Parse_Posted_Json()



        //  Takes user submitted csv and parses it into PIDs.  Stores in session variable to retain after deleting/undeleting projects.
        public function Parse_Posted_Csv()
        {
            $Custom_Box_csv = $_POST['Custom_Box_csv'];

            if (isset($Custom_Box_csv)) {
                $Posted_csv = $Custom_Box_csv;
                $_SESSION['Custom_csv'] = $Custom_Box_csv;
            } elseif (isset($_SESSION['Custom_csv'])) {
                $Posted_csv = $_SESSION['Custom_csv'];
            }


            return $Posted_csv;
        }  // End Parse_Posted_Csv()

        //  Runs SQL query and displays results tablesorter table.  Takes parsed json/csv if necessary.
        public function Display_Table()
        {

            global $conn;
            if (!isset($conn)) {
                db_connect(false);
            }

            $tab = $_REQUEST['tab'];  //  Tabs for SQL array
            $this->Tablesorter_Includes();  // Enables tablesorter

            //  Get results for submitted json or csv
//            if (!isset($_REQUEST['tab'])) {
//                die;
//            } else {
                $Parsed_json = $this->Parse_Posted_Json();
                $Parsed_csv = $this->Parse_Posted_Csv();
//            }

            //  Page urls
            $Current_URL = "http://" . SERVER_NAME . $_SERVER['REQUEST_URI'];
            $My_Projects_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=0";
            $All_Projects_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=1";
            $json_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=2";
            $csv_Page = "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=3";

            // Set variables depending on json/csv page
            if($Current_URL == $json_Page) {
                $Parsed_Array = explode(",", $Parsed_json);
                $qMarks = str_repeat('?,', count($Parsed_Array) - 1) . '?';
                $Get_Integers = explode(",", $Parsed_json);
                $Integers = join(array_pad(array(), count($Get_Integers), "i"));
            }
            elseif($Current_URL == $csv_Page) {
                $Parsed_Array = explode(",", $Parsed_csv);
                $qMarks = str_repeat('?,', count($Parsed_Array) - 1) . '?';
                $Get_Integers = explode(",", $Parsed_csv);
                $Integers = join(array_pad(array(), count($Get_Integers), "i"));
            }

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
                CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
                GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                FROM redcap_projects as a
                JOIN redcap_user_rights AS b
                ON a.project_id=b.project_id
                JOIN redcap_record_counts AS c
                ON a.project_id=c.project_id
                WHERE username = '" . USERID . "'
                GROUP BY a.project_id
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
                CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
                GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                FROM redcap_projects as a
                JOIN redcap_user_rights AS b
                ON a.project_id=b.project_id
                JOIN redcap_record_counts AS c
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
                CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
                GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                FROM redcap_projects as a
                JOIN redcap_user_rights AS b
                ON a.project_id=b.project_id
                JOIN redcap_record_counts AS c
                ON a.project_id=c.project_id
                WHERE a.project_id IN (" . $qMarks . ")  
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
                CAST(CASE WHEN a.date_deleted IS NULL THEN 0 ELSE 1 END AS INT) AS 'Flagged',
                GROUP_CONCAT((b.username) SEPARATOR ', ') AS 'Users'
                FROM redcap_projects as a
                JOIN redcap_user_rights AS b
                ON a.project_id=b.project_id
                JOIN redcap_record_counts AS c
                ON a.project_id=c.project_id
                WHERE a.project_id IN (" . $qMarks . ")  
                GROUP BY a.project_id
                ORDER BY a.project_id ASC  
                    "
            );
        ?>
        <form name="Form" id="Form" action="<?= $this->getUrl("index.php") ?>" method="POST" onsubmit="return confirm('Confirm that the selected projects should be deleted/undeleted');">
        <?php


        // Displays submit form if the page is My or All projects.  Class = tablesorter is how tablesorter is applied to the table.
        if($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page) {

            $this->Display_Submit_Button(); ?>
            <div id="id_projects_table" align="center">
            <table id='Projects_Table' class='tablesorter'>
            <?php
            $this->Display_Pager();
            $this->Table_Header();
        }  // End if($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page)

        if($Current_URL == $json_Page || $Current_URL == $csv_Page) {
            $stmt = $conn->prepare($Project_Pages[$tab]);
            $stmt->bind_param($Integers, ...$Parsed_Array);
            $stmt->execute();
            $Get_Result = $stmt->get_result();
            $num_rows = mysqli_num_rows($Get_Result);
//            var_dump($Get_Result);

        //  If the page is json or csv and a value was submitted, display submit form, otherwise show error no results.
        if($Current_URL == $json_Page) {
            if ($Parsed_json != "") {

                $this->Display_Submit_Button(); ?>

                <div id="id_projects_table" align="center">
                <table id='Projects_Table' class='tablesorter'>
                <?php
                $this->Display_Pager();
                $this->Table_Header();
            }  // End if ($Parsed_json != "")
            else {
                echo "Error, no results.  Please enter a value";
            }
        }  // End if($Current_URL == $json_Page)
        elseif($Current_URL == $csv_Page) {

            // Only display table when submitted csv has results
            if ($num_rows >= 1) {

                $this->Display_Submit_Button(); ?>

                <div id="id_projects_table" align="center">
                <table id='Projects_Table' class='tablesorter'>
                <?php
                $this->Display_Pager();
                $this->Table_Header();
            }  // End if ($num_rows >= 1)
            else {
                echo "Error, no results.  Please enter a value";
            }
        }  // End elseif($Current_URL == $csv_Page)

            // Builds HTML rows and displays sql results for submitted json and csv.
            while ($row = $Get_Result->fetch_assoc()) {
                ?>

                <tr id="<?php echo $row['New Date Deleted']; ?>"> <?php ;

                    if ($row['New Date Deleted'] == "") // If date_delete is null, color row green, otherwise red.  // also works:  $row['New Date Deleted'] == ""
                    {
                        $Row_Color = "style=\"background-color: rgba(0, 200, 0, 0.1);\"";
    //                 $Flagged = 0;
                    } else {
                        $Row_Color = "style=\"background-color: rgba(200, 0, 0, 0.1);\"";
    //                 $Flagged = 1;
                    }
                    ?>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <input class="PID_Checkbox" id="<?php echo $row['Flagged']; ?>" type='checkbox' name="Select_Project" value=<?php echo $row['project_id']; ?>>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "/index.php?pid=" . $row['project_id']; ?>"><?php echo $row['project_id']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['app_title']; ?> </a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['Purpose']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/other_functionality.php?pid=" . $row['project_id']; ?>" ><?php echo $row['Statuses']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "DataExport/index.php?pid=" . $row['project_id'] . "&report_id=ALL"; ?>"> <?php echo $row['record_count']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "UserRights/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['Users']; ?> </a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['New Creation Time']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['New Last Event']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['Days Since Last Event']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['New Date Deleted']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['New Final Delete Date']; ?>
                    </td>

                    <?php ;
                    ?>
                </tr>
                <?php
            }  // End while loop

        }  // End if($Current_URL == $json_Page || $Current_URL == $csv_Page)
        elseif($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page) {
            // Results for My or All Projects SQL query.
            $Result = db_query($Project_Pages[$tab]);

            // Builds HTML rows and displays sql results for My Projects and All Projects.
            while ($row = db_fetch_assoc($Result))  // $sqlGetAllProjects
            {
                ?>

                <tr id="<?php echo $row['New Date Deleted']; ?>"> <?php ;

                    if ($row['New Date Deleted'] == "") // If date_delete is null, color row green, otherwise red.  // also works:  $row['New Date Deleted'] == ""
                    {
                        $Row_Color = "style=\"background-color: rgba(0, 200, 0, 0.1);\"";
    //                 $Flagged = 0;
                    } else {
                        $Row_Color = "style=\"background-color: rgba(200, 0, 0, 0.1);\"";
    //                 $Flagged = 1;
                    }
                    ?>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <input class="PID_Checkbox" id="<?php echo $row['Flagged']; ?>" type='checkbox' name="Select_Project" value=<?php echo $row['project_id']; ?>>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "/index.php?pid=" . $row['project_id']; ?>"><?php echo $row['project_id']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['app_title']; ?> </a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['Purpose']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/other_functionality.php?pid=" . $row['project_id']; ?>" ><?php echo $row['Statuses']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "DataExport/index.php?pid=" . $row['project_id'] . "&report_id=ALL"; ?>"> <?php echo $row['record_count']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "UserRights/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['Users']; ?> </a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['New Creation Time']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['New Last Event']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['Days Since Last Event']; ?></a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['New Date Deleted']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['New Final Delete Date']; ?>
                    </td>

                    <?php ;
                    ?>
                </tr>
                <?php
                }  // End while loop for My/All projects
            }    // End elseif($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page)
        }  //  End Display_Table()

        // This function is called on form submit.  Gets pre values, executes update query, gets post values, adds project update to REDCap Activity Log.
        public function Submit()
        {
            if(SUPER_USER == 1) {
                $Pre_Values = $this->Get_Values();
                $this->Update_Project();
                $Post_Values = $this->Get_Values();

                // Adds logging to REDCap
                foreach ($Pre_Values AS $Pre_Value) {
                    foreach ($Post_Values AS $Post_Value) {
                        if ($Post_Value['project_id'] == $Pre_Value['project_id']) {
                            if ($Post_Value != $Pre_Value) {
                                if ($Post_Value['date_deleted'] == NULL) {
                                    REDCap::logEvent("Project " . $Post_Value['project_id'] . " undeleted via Quick Deleter by " . USERID . "", NULL, NULL, NULL, NULL, $Post_Value['project_id']);
                                }  // End of if (date_delete == NULL)
                                else {
                                    REDCap::logEvent("Project " . $Post_Value['project_id'] . " deleted via Quick Deleter by " . USERID . "", NULL, NULL, NULL, NULL, $Post_Value['project_id']);
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
                REDCap::logEvent("Non super user, " . USERID . ", tried to delete/undelete projects via the Quick Deleter external module", NULL, NULL, NULL, NULL, NULL);
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "This function is for super users only";
                echo "<br>";
            }  // End super user check
        }  // End of Submit()

        // Gets PIDs for rows that were checked
        public function Get_PID()
        {
            $PID_Box = $_POST['PID'];
            return $PID_Box;
        }  // End of Get_PID()

        // Gets value of date_deleted.  Used for both pre and post values
        public function Get_Values()
        {
            $sql_Get_Values = "
            SELECT project_id, date_deleted
            FROM redcap_projects
            WHERE project_id IN (" . $this->Get_PID() . ")
            ";

            $sql = db_query($sql_Get_Values);

            $Results = array();
            while ($Values = db_fetch_assoc($sql)) {
                $Results[] = $Values;
            }
            return $Results;
        }  // End Get_Values()

        // Prepared query to delete or undelete projects
        public function Update_Project()
        {

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

            return $sqlUpdateProject;
        }  // End Update_Project()
    }  // End QuickDeleter class
//}  // End if(SUPER_USER == 1)
//else {
//    REDCap::logEvent("Non super user, " . USERID . ", tried to access the Quick Deleter external module", NULL, NULL, NULL, NULL, NULL);
//    echo "This function is for super users only";
//    echo "<br>";
//}

