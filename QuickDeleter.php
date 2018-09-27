<?php

namespace UIOWA\QuickDeleter;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use DateTimeRC;
use Project;
use REDCap;

if(SUPER_USER == 1) {

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
                    <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index"; ?>">
                        Quick Deleter </a>
                </h1>

                <table id="Pages_Table">
                    <tr>
                        <td>
                            <a href="<?= $this->getUrl("index.php?tab=0") ?>">My Projects</a>
                        </td>
                        <td>
                            <a href="<?= $this->getUrl("index.php?tab=1") ?> ">All Projects</a>
                        </td>
                        <form name="Custom_Form_json" id="Custom_Form_json" method="POST"
                              action="<?= $this->getUrl("index.php?tab=2") ?>">
                            <td>
                                <button class="Button_Link" type="submit" id="Custom_Page_json" name="Custom_Page_json">
                                    json
                                </button>
                            </td>
                            <td>
                                <input id="Custom_Box_json" class="Button_Box" type='text' name='Custom_Box_json'
                                       value="">
                            </td>
                        </form>

                        <form name="Custom_Form_csv" id="Custom_Form_csv" method="POST"
                              action="<?= $this->getUrl("index.php?tab=3") ?>">
                            <td>
                                <button class="Button_Link" type="submit" id="Custom_Page_csv" name="Custom_Page_csv">
                                    csv
                                </button>
                            </td>
                            <td>
                                <input id="Custom_Box_csv" class="Button_Box" type='text' name='Custom_Box_csv'
                                       value="">
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

            $this->Display_Header();
            $this->Display_Home_Page();
            $this->Display_Table();

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
//                 $("form[name=Form]").on("change", "input[type=checkbox]", function () {
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

                $(document).ready(function () {
                    console.log("ready!");
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
        }

        //  Displays home page
        public function Display_Home_Page()
        {

            $Current_URL = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $Home_Page = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index";
            if ($Current_URL == $Home_Page) {
                ?>
                <div>
                    <h2 style="text-align: center; padding-top:50px; color:white;">Quickly delete and undelete
                        projects</h2>
                </div>
                <?php
            }
        }

        //  Displays page limit dropdown
        public function Display_Pager()
        {
            ?>

            <div id="pager" class="pager" align="center">

                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/first.png") ?>"
                     class="first"/>
                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/prev.png") ?>"
                     class="prev"/>
                <!-- the "pagedisplay" can be any element, including an input -->
                <span class="pagedisplay"
                      data-pager-output-filtered="{startRow:input} &ndash; {endRow} / {filteredRows} of {totalRows} total rows"></span>
                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/next.png") ?>"
                     class="next"/>
                <img src="<?= $this->getUrl("resources/tablesorter/tablesorter/images/icons/last.png") ?>"
                     class="last"/>

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
                            <input class="reset_button" type="reset" name="reset" id="reset"
                                   onclick="Clear_Row_Styling()">
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
        }

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
            <link href="<?= $this->getUrl("/resources/tablesorter/tablesorter/jquery.tablesorter.pager.min.css") ?>"
                  rel="stylesheet">
            <link href="<?= $this->getUrl("/resources/styles.css") ?>" rel="stylesheet" type="text/css"/>

            <script src="<?= $this->getUrl("/QuickDeleter.js") ?>"></script>
            <?php
        }

        //  Displays table headers
        public function Table_Header()
        {


            ?>

            <thead>
            <tr>
                <?php

                $Current_URL = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                $Tab0 = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=0";
                $Tab1 = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=1";

                if ($Current_URL == $Tab0 || $Current_URL == $Tab1) {
                    ?>
                    <th></th> <?php
                } else {
                    ?>
                    <th style="text-align:center" data-filter="false"><input name="check_all" id="check_all"
                                                                             type="checkbox"></th> <?php
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
        }

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
        }

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
        }

        //  Runs SQL query for displaying table.  Takes parsed json if necessary.
        public function Display_Table()
        {

            global $conn;
            if (!isset($conn)) {
                db_connect(false);
            }

            $tab = $_REQUEST['tab'];  //  Tabs for SQL array

            $this->Tablesorter_Includes();

            //  Calls parsed json if tab=2
            if (!isset($_REQUEST['tab'])) {
                die;
            } else {
                $Parsed_json = $this->Parse_Posted_Json();
                $Parsed_csv = $this->Parse_Posted_Csv();
            }



//        $Custom_String_ssv = str_replace(" ", ",", $ssv);
            //echo $Custom_String_ssv;

            $Parsed_json_array = explode(",", $Parsed_json);

//         Forms comma separated question mark placeholder string for SQL WHERE IN () query.  e.g. ?,?,?
            $qMarks = str_repeat('?,', count($Parsed_json_array) - 1) . '?';
//        echo $qMarks;

//         Forms int placeholder string for bind_param().  e.g. 'iii'
            $Get_Integers = explode(",", $Parsed_json);
            $Integers = join(array_pad(array(), count($Get_Integers), "i"));
//        echo $Integers;

        $Parsed_csv_array = explode(",", $Parsed_csv);
        //         Forms comma separated question mark placeholder string for SQL WHERE IN () query.  e.g. ?,?,?
        $qMarksCsv = str_repeat('?,', count($Parsed_csv_array) - 1) . '?';
//        echo $qMarksCsv;

//         Forms int placeholder string for bind_param().  e.g. 'iii'
        $Get_IntegersCsv = explode(",", $Parsed_csv);
        $IntegersCsv = join(array_pad(array(), count($Get_IntegersCsv), "i"));
//        echo $IntegersCsv;


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
        WHERE a.project_id IN (" . $qMarksCsv . ")  
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
        WHERE a.project_id IN (" . $Custom_String_ssv . ")  
        GROUP BY a.project_id
        ORDER BY a.project_id ASC  
            "
            );

            $Current_URL = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $My_Projects_Page = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=0";
            $All_Projects_Page = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=1";
            $json_Page = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=2";
            $csv_Page = SERVER_NAME . APP_PATH_WEBROOT . "ExternalModules/?prefix=quick_deleter&page=index&tab=3";



             ?>



        <form name="Form" id="Form" action="<?= $this->getUrl("index.php") ?>" method="POST"
              onsubmit="return confirm('Confirm that the selected projects should be deleted/undeleted');">

            <?php

            // Loads tablesorter and displays submit form if the page is My or All projects.
            if($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page) {

                $this->Display_Submit_Button(); ?>
                <div id="id_projects_table" align="center">
                <table id='Projects_Table' class='tablesorter'>
                <?php
                $this->Display_Pager();
                $this->Table_Header();
            }

            //  If the page is json or csv and a value was submitted, load tablesorter and display submit for, otherwise show error no results.
            if($Current_URL == $json_Page) {
                if ($Parsed_json != "") {

                    $this->Display_Submit_Button(); ?>

                    <div id="id_projects_table" align="center">
                    <table id='Projects_Table' class='tablesorter'>
                    <?php
                    $this->Display_Pager();
                    $this->Table_Header();
                }
                else {
                    echo "Error, no results.  Please enter a value";
                }
            }

        if($Current_URL == $csv_Page) {
        if ($Parsed_csv != "" ) {

            $this->Display_Submit_Button(); ?>

            <div id="id_projects_table" align="center">
            <table id='Projects_Table' class='tablesorter'>
            <?php
            $this->Display_Pager();
            $this->Table_Header();
        }
        else {
            echo "Error, no results.  Please enter a value";
        }
        }

            //  If page is json, prepare sql statement.  Elseif csv then prepare statement.
            if ($Current_URL == $json_Page) {

                $stmt = $conn->prepare($Project_Pages[2]);
                $stmt->bind_param($Integers, ...$Parsed_json_array);
                $stmt->execute();
                $Get_Result = $stmt->get_result();


                while ($row_json = $Get_Result->fetch_assoc()) {
                    ?>

                    <tr id="<?php echo $row_json['New Date Deleted']; ?>"> <?php ;

                        if ($row_json['New Date Deleted'] == "") // If date_delete is null, color row green, otherwise red.  // also works:  $row_json['New Date Deleted'] == ""
                        {
                            $Row_Color = "style=\"background-color: rgba(0, 200, 0, 0.1);\"";
//                 $Flagged = 0;
                        } else {
                            $Row_Color = "style=\"background-color: rgba(200, 0, 0, 0.1);\"";
//                 $Flagged = 1;
                        }
                        ?>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <input class="PID_Checkbox" id="<?php echo $row_json['Flagged']; ?>" type='checkbox'
                                   name="Select_Project" value=<?php echo $row_json['project_id']; ?>>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_json['project_id']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <!--                    <a href="-->
                            <?php //sprintf("https://%s%sProjectSetup/index.php?pid=%d", SERVER_NAME, APP_PATH_WEBROOT, $row_json['project_id']); ?><!--" > -->
                            <?php //echo $row_json['app_title']; ?><!-- </a>-->
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/index.php?pid=" . $row_json['project_id']; ?>"> <?php echo $row_json['app_title']; ?> </a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_json['Purpose']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_json['Statuses']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "DataExport/index.php?pid=" . $row_json['project_id'] . "&report_id=ALL"; ?>"> <?php echo $row_json['record_count']; ?></a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "UserRights/index.php?pid=" . $row_json['project_id']; ?>"> <?php echo $row_json['Users']; ?> </a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_json['New Creation Time']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row_json['project_id']; ?>"> <?php echo $row_json['New Last Event']; ?></a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row_json['project_id']; ?>"> <?php echo $row_json['Days Since Last Event']; ?></a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_json['New Date Deleted']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_json['New Final Delete Date']; ?>
                        </td>

                        <?php ;
                        ?>
                    </tr>
                    <?php
                }  // End while loop
            }  //  End if ($Current_URL == $json_Page)
            elseif($Current_URL == $csv_Page) {
                $stmt = $conn->prepare($Project_Pages[3]);
                $stmt->bind_param($IntegersCsv, ...$Parsed_csv_array);
                $stmt->execute();
                $Get_Result = $stmt->get_result();


                while ($row_csv = $Get_Result->fetch_assoc()) {
                    ?>

                    <tr id="<?php echo $row_csv['New Date Deleted']; ?>"> <?php ;

                        if ($row_csv['New Date Deleted'] == "") // If date_delete is null, color row green, otherwise red.  // also works:  $row_csv['New Date Deleted'] == ""
                        {
                            $Row_Color = "style=\"background-color: rgba(0, 200, 0, 0.1);\"";
//                 $Flagged = 0;
                        } else {
                            $Row_Color = "style=\"background-color: rgba(200, 0, 0, 0.1);\"";
//                 $Flagged = 1;
                        }
                        ?>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <input class="PID_Checkbox" id="<?php echo $row_csv['Flagged']; ?>" type='checkbox'
                                   name="Select_Project" value=<?php echo $row_csv['project_id']; ?>>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_csv['project_id']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <!--                    <a href="-->
                            <?php //sprintf("https://%s%sProjectSetup/index.php?pid=%d", SERVER_NAME, APP_PATH_WEBROOT, $row_csv['project_id']); ?><!--" > -->
                            <?php //echo $row_csv['app_title']; ?><!-- </a>-->
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/index.php?pid=" . $row_csv['project_id']; ?>"> <?php echo $row_csv['app_title']; ?> </a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_csv['Purpose']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_csv['Statuses']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "DataExport/index.php?pid=" . $row_csv['project_id'] . "&report_id=ALL"; ?>"> <?php echo $row_csv['record_count']; ?></a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "UserRights/index.php?pid=" . $row_csv['project_id']; ?>"> <?php echo $row_csv['Users']; ?> </a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_csv['New Creation Time']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row_csv['project_id']; ?>"> <?php echo $row_csv['New Last Event']; ?></a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "Logging/index.php?pid=" . $row_csv['project_id']; ?>"> <?php echo $row_csv['Days Since Last Event']; ?></a>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_csv['New Date Deleted']; ?>
                        </td>
                        <td align='center' class="color" <?php echo $Row_Color ?>>
                            <?php echo $row_csv['New Final Delete Date']; ?>
                        </td>

                        <?php ;
                        ?>
                    </tr>
                    <?php
                }  // End while loop
            }  //  End elseif($Current_URL == $csv_Page)

            // Results for My or All Projects SQL query.
            $Result = db_query($Project_Pages[$tab]);

            //  Seeming don't need.  Maybe delete later
//            if($Current_URL == $My_Projects_Page || $Current_URL == $All_Projects_Page) {
//                if ($Result != "") {
//
//                    $this->Tablesorter_Includes();
//                } else {
//                    echo "Error, no results";
//                }
//            }

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
                        <input class="PID_Checkbox" id="<?php echo $row['Flagged']; ?>" type='checkbox'
                               name="Select_Project" value=<?php echo $row['project_id']; ?>>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['project_id']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <!--                    <a href="-->
                        <?php //sprintf("https://%s%sProjectSetup/index.php?pid=%d", SERVER_NAME, APP_PATH_WEBROOT, $row['project_id']);
                        ?><!--" > --><?php //echo $row['app_title'];
                        ?><!-- </a>-->
                        <a href="<?php echo "http://" . SERVER_NAME . APP_PATH_WEBROOT . "ProjectSetup/index.php?pid=" . $row['project_id']; ?>"> <?php echo $row['app_title']; ?> </a>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['Purpose']; ?>
                    </td>
                    <td align='center' class="color" <?php echo $Row_Color ?>>
                        <?php echo $row['Statuses']; ?>
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

            //  Displays pager below table
//            ?>
<!--            <table align="center">-->
<!--                <tr>-->
<!--                    <td>-->
<!--                        --><?php
//                        if ($Result != "") {
//                            $this->Display_Pager();
//                        } ?>
<!--                    </td>-->
<!--                </tr>-->
<!--            </table>-->
<!--            --><?php
        }  // End GetProjectList()

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

//            echo "Referred from custom json page";
                header("Location: {$_SERVER['HTTP_REFERER']}");
            }
            else {
                echo "This function is for super users only";
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

// Echo </br> needed to display echos under redcap nav bar
            echo "</br>";
            echo "</br>";
            echo "</br>";
            echo "</br>";
            echo "</br>";
            echo "</br>";
//        echo $this->Get_PID();


            // Converts submitted PID_Box string to array for bind_param()
            $PID_Array = explode(",", $this->Get_PID());
//        print_r($PID_Array);
//        var_dump($PID_Array);

            // Forms comma separated question mark placeholder string for SQL WHERE IN () query.  e.g. ?,?,?
            $qMarks = str_repeat('?,', count($PID_Array) - 1) . '?';
//        echo $qMarks;

            // Forms int placeholder string for bind_param().  e.g. 'iii'
            $Get_Integers = explode(",", $this->Get_PID());
            $Integers = join(array_pad(array(), count($Get_Integers), "i"));
//        echo $Integers;

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
}
else {
    echo "This function is for super users only";
}  // End super user check

