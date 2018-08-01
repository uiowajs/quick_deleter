<?php

namespace UIOWA\QuickDeleter;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use DateTimeRC;
use Project;
use REDCap;


/*  TO DO

    -  Quick links to ADB, ABD custom query, all the modules in the "Quick Suite" at the top of page
    -  Values in table are links.
    -  Add icons next to certain values
    -  Column for:  Days until final delete, days since last record, last 5 logging entries
    -  Create/delete history page
    -  Custom query page

 */

class QuickDeleter extends AbstractExternalModule {

    public function DisplayProjectsTable()  // Creates html form, table, and headers.  Contains tablesorter paths and scripts to put PID in the PID_Box when checkbox checked.
    {
        ?>
        <head>

            <script src="http://code.jquery.com/jquery-latest.js"></script>
            <script src="<?= $this->getUrl("/resources/tablesorter/jquery.tablesorter.min.js") ?>"></script>
            <script src="<?= $this->getUrl("/resources/tablesorter/jquery.tablesorter.widgets.min.js") ?>"></script>
            <script src="<?= $this->getUrl("/resources/tablesorter/widgets/widget-pager.min.js") ?>"></script>
            <script src="<?= $this->getUrl("/resources/tablesorter/parsers/parser-input-select.min.js") ?>"></script>
            <script src="<?= $this->getUrl("/resources/tablesorter/widgets/widget-output.min.js") ?>"></script>

            <link href="<?= $this->getUrl("/resources/tablesorter/tablesorter/theme.blue.min.css") ?>" rel="stylesheet">
            <link href="<?= $this->getUrl("/resources/tablesorter/tablesorter/jquery.tablesorter.pager.min.css") ?>" rel="stylesheet">
            <link href="<?= $this->getUrl("/resources/styles.css") ?>" rel="stylesheet" type="text/css"/>
            

            <script src="<?= $this->getUrl("/QuickDeleter.js") ?>"></script>

        </head>

        <body>

            <form name="Form" id="Form" action="<?= $this->getUrl("index.php") ?>" method="POST" >

                <div id="pager" class="pager" align="center">
                    <h1 style="text-align: center;" >Quick Deleter</h1>



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

                <table id='Submit_Table'>
<!--                    <table id='Projects_Table' class='tablesorter' >-->
                    <tr>
                        <div align="center">
                        <td><input type='submit' id='submit' name='submit'></td>
                        <td><input id='PID_Box' type='text' name='PID' readonly></td>
                        </div>
                    </tr>
                    <tr>
                        <td><input type="reset" name="reset" id="reset" onclick="Clear_Row_Styling()" ></td>
                    </tr>
                </table>

                <div align='center'>
                <table id='Projects_Table' class='tablesorter' >
                    <thead>
                        <tr>
                            <th style="text-align:center"></th>
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
                        </tr>
                    </thead>

                    <tbody>
                        <?php   $this->GetProjectList(); ?>
                    </tbody>
                </table>
                </div>
        </body>

        <script type="text/javascript">

            // Puts comma separated values of checkboxes in PID_Box.
            $("form[name=Form]").on("change", "input[type=checkbox]", function () {
                var values = $.map($("input[type=checkbox]:checked"), function (pid) {
                        return pid.value;
                    });
                $("form[name=Form]").find("input[id=PID_Box]").val(values);
            });
       
            //  Highlight row when box checked
            $( "input[type=checkbox]" ).on('change', function(){
//                 $("form[name=Form]").on("change", "input[type=checkbox]", function () {
                if($(this).is(':checked'))
                    // console.log($(this).attr('id'));
                    if($(this).prop('id') === '0')
                        // console.log($(this).attr('id'));
                        $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0, 0.7)").css({fontWeight: this.checked?'bold':'normal'});
                    else
                        // console.log($(this).attr('id'));
                        $(this).closest('tr').css("backgroundColor", "rgba(0, 255, 0, 1)").css({fontWeight: this.checked?'bold':'normal'});
                else
                        // console.log("Hi");
                    $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked?'bold':'normal'});
            });

            // Removes checked row color on form reset
            function Clear_Row_Styling()
            {
                $('tr').css("backgroundColor", "").css({fontWeight: 'normal'});
            }

            // Avoids having to resubmit the form on page refresh
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }

        </script>
        <?php
    }  // End DisplayProjectsTable()

    public function GetProjectList()
    {
        // SQL Query to get project list.
        $sqlGetProjects = db_query(
            "SELECT a.project_id, app_title, a.date_deleted, a.purpose, a.status, record_count, last_logged_event, creation_time, username,
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
        ORDER BY app_title ASC");  // End SQL Query

        // Builds HTML rows and displays sql results.
        while ($row = db_fetch_assoc($sqlGetProjects))
        {
            ?>
            <tr id="<?php echo $row['New Date Deleted']; ?>"> <?php ;  

            if($row['New Date Deleted'] == "") // If date_delete is null, color row green, otherwise red.  // also works:  $row['New Date Deleted'] == ""
            {
                $Row_Color = "style=\"background-color: rgba(0, 200, 0, 0.1);\"";
//                 $Flagged = 0;
            }
            else
            {
                $Row_Color = "style=\"background-color: rgba(200, 0, 0, 0.1);\"";
//                 $Flagged = 1;
            }
                ?>
                <td align='center' class="color" <?php echo $Row_Color ?>> <input id="<?php echo $row['Flagged']; ?>" type='checkbox' name="Select_Project" value=<?php echo $row['project_id']; ?>></td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['project_id']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['app_title']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['Purpose']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['Statuses']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['record_count']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['Users']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['New Creation Time']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['New Last Event']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['Days Since Last Event']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['New Date Deleted']; ?> </td>
                <td align='center' class="color" <?php echo $Row_Color ?>> <?php echo $row['New Final Delete Date']; ?> </td>
                  </td>
                <?php ;
            ?>
            </tr>
            <?php
        }  // End while loop
        ?>
        <table align="center">
        <tr>
                <td>
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
                </td>
            </tr>
        </table>
<?php
    }  // End GetProjectList()
    
    public function Delete_or_Undelete_Project()  // Executes sql query to set date_deleted to either NOW or NULL.
    {
        global $conn;
        if (!isset($conn)) {
            db_connect(false);
        }

        // Takes PIDs submitted from PID_Box and makes an array.
        $PID_Box = explode(",", $_POST['PID']);
        foreach ($PID_Box as $PID) {
            $sqlUpdateProject = "
            UPDATE redcap_projects 
            SET date_deleted = IF(date_deleted IS NULL, '" . NOW . "', NULL) 
            WHERE project_id = ? 
            ";

//            $sqlGetUpdatedProjects = "SELECT project_id, date_deleted
//            FROM redcap_projects";

            if ($stmt = $conn->prepare($sqlUpdateProject)) {
                $stmt->bind_param('i', $PID);
                $stmt->execute();
//                $success = $stmt->affected_rows;
                $stmt->bind_result($pid1);

                while ($stmt->fetch()) {
                    return $pid1;
                    echo $pid1;
                }
                $stmt->close();
            } else {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }







            
//            if($success >= 1)
//            {
//                REDCap::logEvent("Project updated via Quick Deleter", NULL, $sqlUpdateProject, NULL, NULL, $PID);
//            }
//            else
//            {
//                echo "Failure";
//            }
        }  // End foreach loop
    }  // End Delete_or_Undelete_Project()
}  // End QuickDeleter class