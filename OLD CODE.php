<?php


//WORKING WHILE LOOP
//        // Builds HTML rows and displays sql results.
//        while ($row = db_fetch_assoc($sqlGetProjects))
//        {
//
//            if($row['New Date Deleted'] == "") {
//                ?>
    <!--                <tr>-->
    <!--                --><?php
//            }
//            else
//            {
//                ?>
    <!--                <tr style="background-color:#e76666;">--><?php //;
//            }
//            ?>
    <!--            <td align='center'><input id="Checkbox:--><?php //echo $row['project_id']; ?><!--" type='checkbox' name='Select_Project' value=--><?php //echo $row['project_id']; ?><!--></td>-->
    <!--            <td align='center'>--><?php //echo $row['app_title']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['project_id']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['Purpose']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['Statuses']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['record_count']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['New Creation Time']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['New Last Event']; ?><!-- </td>-->
    <!--            <td align='center'>--><?php //echo $row['Days Since Last Event']; ?><!-- </td>-->
    <!--            --><?php
//
//            if($row['New Date Deleted'] == "")
//            {
//                ?>
    <!--                <td align='center' style="background-color: #69d876;">--><?php //echo $row['New Date Deleted']; ?><!--</td>--><?php //;
//            }
//            else
//            {
//                ?>
    <!--                <td align='center' style="background-color: #e76666;">--><?php //echo $row['New Date Deleted']; ?><!--</td>--><?php //;
//            }
//            ?>
    <!--            <td align='center'>--><?php //echo $row['New Final Delete Date']; ?><!--</td>-->
    <!--            </tr>-->
    <!--            --><?php
//        }  // End while loop




// //  Highlights row when checkbox checked.  WORKING 2
// $(function(){
//     $( "input[type=checkbox]" ).on('click', function(){
//         if($(this).is(':checked'))
//             if($('#ID_New_Date_Deleted').is(':empty'))
//                 $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0)").css({fontWeight: this.checked?'bold':'normal'});
//             else
//                 $(this).closest('tr').css("backgroundColor", "rgba(0, 255, 0)").css({fontWeight: this.checked?'bold':'normal'});
//         else
//             $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked?'bold':'normal'});
//     });
// });




// //  Highlights row when checkbox checked.  WORKING
// $(function(){
//     $( "input[type=checkbox]" ).on('click', function(){
//         if($(this).is(':checked'))
//             $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0)").css({fontWeight: this.checked?'bold':'normal'});
//         else
//             $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked?'bold':'normal'});
//     });
// });



//  THIS WORKS IN JSFIDDLE http://jsfiddle.net/etVc8/459/
// $(function(){
//     $( "input[type=checkbox]" ).on("change", function(){
//         if($(this).is(':checked'))
//             $(this).closest('tr').css('background-color', '#cd0000');
//         else
//             $(this).closest('tr').css('background-color', '');
//     });
// });




//  OLD PID_BOX
//
//                        <tr>
//                        <td>Delete:  </td>
//                        <td><input id='PID_Box' type='text' name='PID' readonly></td>
//                    </tr>
//                    <tr>
//                        <td>Undelete:  </td>
//                        <td><input id='PID_Box_Undelete' type='text' name='PID_Undelete' readonly></td>
//                    </tr>
//







/*

// WORKING
//                if($row['New Date Deleted'] == "")  // If date_delete is null, color row green, otherwise red.
//                {
//                    ?>
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-- ><input id="--><?php //echo $row['Flagged']; ?><!--" type='checkbox' name="Select_Project" value=--><?php //echo $row['project_id']; ?><!--></td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-- >--><?php //echo $row['app_title']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-- >--><?php //echo $row['project_id']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-- >--><?php //echo $row['Purpose']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['Statuses']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['record_count']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['New Creation Time']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['New Last Event']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['Days Since Last Event']; ?><!-- </td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['New Date Deleted']; ?><!--</td>-->
<!--                    <td align='center'  --><?php //echo $Active_Project_Color ?><!-->--><?php //echo $row['New Final Delete Date']; ?><!--</td>-->
<!--                <td align='center'  --><?php //echo $Active_Project_Color ?><!--><input id="id_FlaggedRow" name="FlaggedRow" type="text"  readonly value="--><?php //echo $row['Flagged']; ?><!--"></td>--><?php //;
//                }
//                else
//                {
//                    ?>
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-- ><input id="--><?php //echo $row['Flagged']; ?><!--" type='checkbox' name="Select_Project" value=--><?php //echo $row['project_id']; ?><!--></td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['app_title']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['project_id']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['Purpose']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['Statuses']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['record_count']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['New Creation Time']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['New Last Event']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['Days Since Last Event']; ?><!-- </td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['New Date Deleted']; ?><!--</td>-->
<!--                    <td align='center' --><?php //echo $Deleted_Project_Color ?><!-->--><?php //echo $row['New Final Delete Date']; ?><!--</td>-->
<!--                <td align='center' --><?php //echo $Deleted_Project_Color ?><!--><input id="id_FlaggedRow" name="FlaggedRow" type="text"  readonly value="--><?php //echo $row['Flagged']; ?><!--"></td>--><?php //;
//                }






*/





//        $PID_Array = explode(",", $_POST['PID']);
//        $PID_Box = explode(",", $_POST['PID']);
//        echo json_encode($PID_Box);
//        foreach ($PID_Box as $PID) {
//            echo json_encode($PID);
//            $sqlUpdateProject = "
//            UPDATE redcap_projects
//            SET date_deleted = IF(date_deleted IS NULL, '".NOW."', NULL)
//            WHERE project_id = ".$PID."
//            SELECT project_id, date_deleted
//            FROM redcap_projects";
//
//
//        db_query($sqlUpdateProject);


//            echo $sqlUpdateProject;

//            echo $PID;

//            SELECT project_id, date_deleted
//            FROM redcap_projects




//            if($Post_Date_Deleted == NULL)
//            {
//                REDCap::logEvent("Project: ".$PID_Box." updated via Quick Deleter", NULL, $sqlUpdateProject, NULL, NULL, $PID_Box);
//            }
//            else
//            {
//                echo "Failure";
//            }


// THIS WORKS IN MYPHPADMIN
//            UPDATE redcap_projects
//            SET date_deleted = IF(date_deleted IS NULL, now(), NULL);
//            SELECT project_id, date_deleted
//            FROM redcap_projects
//            WHERE project_id = 13






////    public function Delete_or_Undelete_Project()  // Executes sql query to set date_deleted to either NOW or NULL.
////    {
////        global $conn;
//////        if (!isset($conn)) {
//////            db_connect(false);
//////        }
//
//        // Project ID of checked rows on submit.
////        $PID_Box = $_POST['PID'];
////        $PID_String = explode(", ", $_POST['PID']);
//
//
//
//        // Query to get values before update query
//
//        $sqlPreValues = "
//        SELECT project_id, date_deleted
//        FROM redcap_projects
//        WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//
//        // Run query and fetch values before update query
//
//        $sqlPre = db_query($sqlPreValues);
////        $Pre_Values = array();
//        while ($Pre_Values = db_fetch_assoc($sqlPre)) {
////            $Pre_Values = db_fetch_assoc($sqlPre);
////            $Pre_json = json_encode($Pre_Values);
//            print_r(array_values($Pre_Values));
////                $Pre_Values[] = $Pre_Values;
////            echo $Pre_json."<br>";
////            array_push($Pre_Values, $Pre_json);
////            echo $Pre_Values['project_id'] . " : ";
////            echo $Pre_Values['date_deleted']."<br>";
//
//        }
////return $Pre_Values;
////echo $Pre_Values;
////        foreach($Pre_Values AS $Test) {
////            echo $Test;
////            echo "</br>";
////        }
//        echo "</br>";
//
//
////        echo $Pre_json;
////        var_dump($Pre_Values)."<br>";
//
//        // Update query
//        $sqlUpdateProject = "
//        UPDATE redcap_projects
//        SET date_deleted = IF(date_deleted IS NULL, '".NOW."', NULL)
//            WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//        // Run update query
//        db_query($sqlUpdateProject);
//
//
//
//        // Query to get values after update query
//        $sqlPostValues = "
//        SELECT project_id, date_deleted
//        FROM redcap_projects
//        WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//        $Post_Values = array();
////        var_dump($Post_Values);
//        // Run query and fetch values after update query
//        $sqlPost = db_query($sqlPostValues);
//        while ($Post_Values = db_fetch_assoc($sqlPost)) {
////            $Post_Values = db_fetch_assoc($sqlPost);
//            $Post_Values[] = $Post_Values;
////            array_push($Post_Values, db_fetch_assoc($sqlPost));
////           print_r(array_values(($Post_Values)));
//
////            $Post_json = json_encode($Post_Values);
////            array_push($Post_Values, $Post_json);
////            echo $Post_json."<br>";
////            echo $Post_Values['project_id'] . " : ";
////            echo $Post_Values['date_deleted']."<br>";
//        }
//        print_r(array_values(($Post_Values)));
////var_dump($Post_Values)."<br>";
////        var_dump($Post_Values);
//        echo "</br>";
////echo $Post_json;
//
//
//
////            if($Post_Values != $Pre_Values) {
////                if($Post_Values['date_deleted'] = NULL)
////                {
////                    REDCap::logEvent("Project: ".$PID_Box." undeleted via Quick Deleter", NULL, $sqlUpdateProject, NULL, NULL, $PID_Box);
////                }
////                else
////                {
////                REDCap::logEvent("Project: ".$PID_Box." deleted via Quick Deleter", NULL, $sqlUpdateProject, NULL, NULL, $PID_Box);
////                }
////            }
////            else {
////               echo "Failure";
////            }
//
//
//
//
//
//
//
//// WOKING
////            $stmt = $conn->prepare($sqlUpdateProject);
////            if ($stmt = $conn->prepare($sqlUpdateProject)) {
////                $stmt->bind_param('s', $PID_Box);
////                $stmt->execute();
////                $success = $stmt->affected_rows;
////                $stmt->bind_result($pid1);
////                $stmt->get_result();
//
//
////                echo $success;
//
////                while ($stmt->fetch()) {
////                    echo $pid1;
////                }
////                $stmt->close();
////            } else {
////                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
////            }
//
//
//
//
//
//
//
//
////            if($success >= 1)
////            {
////                REDCap::logEvent("Project: ".$PID." updated via Quick Deleter", NULL, $sqlUpdateProject, NULL, NULL, $PID);
////            }
////            else
////            {
////                echo "Failure";
////            }
//
//
//
//
//
//
////        }  // End foreach loop
////    }  // End Delete_or_Undelete_Project()



//    public function tempName() {
//        $sqlPreValues = "
//        SELECT project_id, date_deleted
//        FROM redcap_projects
//        WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//        $sqlPre = db_query($sqlPreValues);
//        while ($Pre_Values = db_fetch_assoc($sqlPre)) {
//            $Pre_PID = $Pre_Values['project_id'];
//            echo "Pre PID:  " . $Pre_PID . " - ";
//            $Pre_Date_Deleted = $Pre_Values['date_deleted'];
//            echo "Pre Date Deleted:  " . $Pre_Date_Deleted;
//            echo "</br>";
////            print_r(array_values($Pre_Values));
//        }
//
//        $sqlUpdateProject = "
//        UPDATE redcap_projects
//        SET date_deleted = IF(date_deleted IS NULL, '".NOW."', NULL)
//        WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//        $Execute_Query = db_query($sqlUpdateProject);
//
//        $sqlPostValues = "
//        SELECT project_id, date_deleted
//        FROM redcap_projects
//        WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//        $sqlPost = db_query($sqlPostValues);
//        while ($Post_Values = db_fetch_assoc($sqlPost)) {
//            $Post_PID = $Post_Values['project_id'];
//            echo "Post PID:  " . $Post_PID . " - ";
//            $Post_Date_Deleted = $Post_Values['date_deleted'];
//            echo "Post Date Deleted:  " . $Post_Date_Deleted . "  ";
//            echo "</br>";
////            print_r(array_values($Post_Values));
//        }
//
//
//    }

//           for($i = 0; $i < $Count_Affected_Projects; $i++) {
//               if
//           }





//           foreach($Pre_Values as $key_pre=>$val_pre) {
//               $found = false;
//               foreach($Post_Values as $key_post=>$val_post) {
//                   if($val_pre == $val_post) {
//                       $found = true;
//                   }
//               }
//               if(!$found) {
//
//               }
//
//           }


//           foreach(array_combine($Pre_Values, $Post_Values) as $key => $value) {
//               echo $key;
//               echo "</br>";
//               echo $value;
//               echo "</br>";
//           }

//           $Count_Affected_Projects = count($Pre_Values);

//           echo "Pre Values:";
//           echo "</br>";
//
//            foreach($Pre_Values AS $Pre_Value) {
////                var_dump($Pre_Values);
////                echo count($Pre_Values);
////                echo "</br>";
//
//                echo "Project ID:  ";
//                print_r($Pre_Value['project_id']);
//                echo "</br>";
//                echo "Date Deleted:  ";
//                print_r($Pre_Value['date_deleted']);
//                echo "</br>";
//            }
//
////           echo "</br>";
//           echo "Number of items in Pre Values:  " .count($Pre_Values);
//           echo "</br>";
//           echo "</br>";
//
//           echo "Post Values:";
//           echo "</br>";
//
//           foreach($Post_Values AS $Post_Value) {
////            var_dump($Post_Values);
////            echo count($Post_Values);
////            echo "</br>";
//
//            echo "Project ID:  ";
//            print_r($Post_Value['project_id']);
//            echo "</br>";
//            echo "Date Deleted:  ";
//            print_r($Post_Value['date_deleted']);
//            echo "</br>";
//        }





//           echo "</br>";
//           echo "Number of items in Post Values:  " . count($Post_Values);
//           echo "</br>";


//           foreach($Pre_Values as $key => $value){
//               $value_Post_Values = $Post_Values[$key];
//               echo($value."-".$value_Post_Values."<br />");
//           }

//           echo "</br>";
//           echo "Pre Array:  ";
//           echo "</br>";
//           print_r($Pre_Values);
//           echo "</br>";
//           echo "Post Array:  ";
//           echo "</br>";
//           print_r($Post_Values);
//           echo "</br>";
//
//           echo "</br>";
//           echo "First index of Pre Array";
//           echo "</br>";
//           print_r($Pre_Values[0]);
//           echo "</br>";
//           echo "Second index of Pre Array";
//           echo "</br>";
//           print_r($Pre_Values[1]);
//
//           echo "</br>";
//           echo "</br>";
//           echo "First index of Post Values";
//           echo "</br>";
//           print_r($Post_Values[0]);
//           echo "</br>";
//           echo "Second index of Post Values";
//           echo "</br>";
//           print_r($Post_Values[1]);

//            $Post_PID = $Post_Values['project_id'];
//            echo "Post PID:  " . $Post_PID . " - ";
//            $Post_Date_Deleted = $Post_Values['date_deleted'];
//            echo "Post Date Deleted:  " . $Post_Date_Deleted . "  ";
//            echo "</br>";
//        print_r($Post_Values);
//           echo "</br>";


//            $Pre_PID = $Pre_Values['project_id'];
//            echo "Pre PID:  " . $Pre_PID . " - ";
//            $Pre_Date_Deleted = $Pre_Values['date_deleted'];
//            echo "Pre Date Deleted:  " . $Pre_Date_Deleted;
//            echo "</br>";
//        print_r($Pre_Values);
//           echo "</br>";

//    public function Get_Values() {
//        $sql = "
//        SELECT project_id, date_deleted
//        FROM redcap_projects
//        WHERE project_id IN (".$this->Get_PID().")
//        ";
//
//        $sql_Submit = db_query($sql);
//        while($sql_Values = db_fetch_assoc($sql_Submit)) {
//            print_r(array_values($sql_Submit));
//        }
//    }


//            $Post_PID = $Post_Values['project_id'];
//            echo "Post PID:  " . $Post_PID . " - ";
//            $Post_Date_Deleted = $Post_Values['date_deleted'];
//            echo "Post Date Deleted:  " . $Post_Date_Deleted . "  ";
//            echo "</br>";
//            print_r($Post_Values);


//            $Pre_Results = json_encode($Pre_Values);
//            print_r($Pre_Results);
//            $Pre_PID = $Pre_Values['project_id'];
//            echo "Pre PID:  " . $Pre_PID . " - ";
//            $Pre_Date_Deleted = $Pre_Values['date_deleted'];
//            echo "Pre Date Deleted:  " . $Pre_Date_Deleted;
//            echo "</br>";
//            print_r($Pre_Values);
