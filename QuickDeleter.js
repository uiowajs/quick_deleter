var UIOWA_QuickDeleter = {};

UIOWA_QuickDeleter.selectedProjectInfo = {};



(function($, window, document) {
    $(document).ready(function() {



        var $checks = $(".PID_Checkbox").on('change', function()
        {
            var checked = $checks.is(':checked');

            $("#send_button").toggle(!checked);
            $("#send_button").toggle(checked);

        });

            $checks.first().change();


        // Tablesorter
        $("#Projects_Table").tablesorter({

            theme: 'blue',
            widthFixed: true,
            usNumberFormat: true,
            sortReset: true,
            sortRestart: false,
            widgets: ['filter', 'pager', 'stickyHeaders'],

            widgetOptions: {

                stickyHeaders_offset: 50,
                filter_reset : '.reset_button'

            }



        });

        // Puts comma separated values of checkboxes in PID_Box.
        $("form[name=Form]").on("change", "input[type=checkbox]", function () {
            var QD_PID_Values = $.map($("input[type=checkbox]:checked"), function (pid) {
                return pid.value;
            });
            $("form[name=Form]").find("input[id=PID_Box]").val(QD_PID_Values);
        });

        // Highlights all rows when check all box checked
        $(document).ready(function () {
            $("#check_all").on('change', function () {
                var QD_PID_Checkboxes = $(".PID_Checkbox");
                // console.log($(this));
                QD_PID_Checkboxes.each(function () {

                    // console.log($(this).checked);
                    if ($(this).is(':checked'))

                    // console.log($(this).attr('id'));
                        if ($(this).prop('id') === '0')
                        // console.log($(this).attr('id'));
                            $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0, 0.7)").css({fontWeight: this.checked ? 'bold' : 'normal'});

                        else
                        // console.log($(this).attr('id'));
                            $(this).closest('tr').addClass("Select_Restore_Row");
                    else
                    // console.log("Hi");
                        $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked ? 'bold' : 'normal'}).removeClass("Select_Restore_Row");
                });

                // var $checks = $("#check_all").on('change', function () {
                    $(this).is(':checked');
                    // $("#send_button").toggle(!checked);
                    $("#send_button").toggle("#send_button");
                // });


            })
        });

        //  Highlight row when box checked
        $(".PID_Checkbox").on('change', function () {
            if ($(this).is(':checked'))
            // console.log($(this).attr('id'));
                if ($(this).prop('id') === '0')
                // console.log($(this).attr('id'));
                    $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0, 0.8)").css({fontWeight: this.checked ? 'bold' : 'normal'}).css("color", "black");
                else
                // console.log($(this).attr('id'));
                    $(this).closest('tr').addClass("Select_Restore_Row");
            else
            // console.log("Hi");
                $(this).closest('tr').css("backgroundColor", "").css({fontWeight: this.checked ? 'bold' : 'normal'}).removeClass("Select_Restore_Row");
        });







        // Confirmation modal on submit with checkboxes
        $('#send_button').click(function() {

            var QD_values = new Array();
            $.each($(".PID_Checkbox:checked"), function() {
                var QD_Data = $(this).parents('tr:eq(0)');
                QD_values.push({
                    'PID: ': $(QD_Data).find('td:eq(1)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Title: ': $(QD_Data).find('td:eq(2)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Purpose: ': $(QD_Data).find('td:eq(3)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Status: ': $(QD_Data).find('td:eq(4)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Records: ': $(QD_Data).find('td:eq(5)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Users: ': $(QD_Data).find('td:eq(6)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Created: ': $(QD_Data).find('td:eq(7)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Last Event: ': $(QD_Data).find('td:eq(8)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                    'Deleted ': $(QD_Data).find('td:eq(9)').text().trim().replace(/(\r\n|\n|\r)/gm,""),
                });
            });

            // console.log(values);

            var QD_Delete_Projects = new Array();
            var QD_Restore_Projects = new Array();



            QD_values.forEach(function (object) {

                if(object['Deleted '] === "") {

                    QD_Delete_Projects.push(object)
                }
                else {

                    QD_Restore_Projects.push(object)
                }

            });

            if(QD_Delete_Projects.length !== 0) {

                $('div#Delete_Projects_Outer_Div').removeClass("Hide_Header");
                $('div#Delete_Projects_Inner_Div').removeClass("Hide_Header");
                $('div#Delete_Projects_Div').removeClass("Hide_Header");
                $('hr#Spacer').removeClass("Hide_Header");
            }


            if(QD_Restore_Projects.length !== 0) {

                $('div#Restore_Projects_Outer_Div').removeClass("Hide_Header");
                $('div#Restore_Projects_Inner_Div').removeClass("Hide_Header");
                $('div#Restore_Projects_Div').removeClass("Hide_Header");

                $('hr#Spacer').removeClass("Hide_Header");
            }

            var QD_Count_Projects = QD_Delete_Projects.length + QD_Restore_Projects.length;



            // QD_Delete_Projects.forEach(function (object) {
            //
            //     // Display_Project_Properties.forEach(function (property, i) {
            //
            //         $('#Delete_Projects_Div').append(
            //             '<div id="Delete_Projects_Inner_Div">' +
            //             '<span style="font-weight:bold; color:red; font-size:16px">DELETE:</span>' +
            //             '<br/><br/>' + '<span style="font-weight:bold" >' + object["Title: "] + '</span>' +
            //             '<br/><b> PID: </b> ' + object["PID: "] +
            //             '<br/>' +
            //             '<br/><b> Record count:</b> ' + object["Records: "] +
            //             '<br/>' +
            //             '<br/><b> Status :</b> ' + object["Status: "] +
            //             '<br/><b> Purpose :</b> ' + object["Purpose: "] +
            //             '<br/>' +
            //             '<br/><b> Created :</b> ' + object["Created: "] +
            //             '<br/><b> Last_Event :</b> ' + object["Last Event: "] +
            //             '<br/>' +
            //             '<br/><b> Users :</b> ' + object["Users: "] +
            //             '<br/>' +
            //             '<br/>' +
            //             '</div>'
            //         );
            //
            //
            //
            //
            //     // });
            // });

            // QD_Restore_Projects.forEach(function (object) {
            //
            //
            //
            //
            //     $('#Restore_Projects_Div').append(
            //         '<div id="Restore_Projects_Inner_Div">' +
            //             '<span style="font-weight:bold; color:green; font-size:16px">RESTORE:</span>' +
            //             '<br/><br/>' + '<span style="font-weight:bold" >' + object["Title: "] + '</span>' +
            //             '<br/><b> PID: </b> ' + object["PID: "] +
            //             '<br/>' +
            //             '<br/><b> Record count:</b> ' + object["Records: "] +
            //             '<br/>' +
            //             '<br/><b> Status :</b> ' + object["Status: "] +
            //             '<br/><b> Purpose :</b> ' + object["Purpose: "] +
            //             '<br/>' +
            //             '<br/><b> Created :</b> ' + object["Created: "] +
            //             '<br/><b> Last_Event :</b> ' + object["Last Event: "] +
            //             '<br/>' +
            //             '<br/><b> Users :</b> ' + object["Users: "] +
            //             '<br/>' +
            //             '<br/>' +
            //         '</div>'
            //     );
            //
            //
            //     // });
            // });


            // Required to create the table without auto closing the tag
            var QD_Delete_Table = '<table id="Delete_Confirm_Table" class="tablesorter">';

            $('#modal-body-top').html(
            '<b style="font-size:16px">Confirm that the following ' + QD_Count_Projects + ' project(s) should be modified:</b>'
                );


            $('#Delete_Projects_Inner_Div').append(

                // Required to create the table without auto closing the tag
                $('#Delete_Projects_Inner_Div').append(QD_Delete_Table) +

                '<tr id="">' +

                '<th>' +
                '<b> PID</b> ' +
                '</th>' +

                '<th>' +
                '<b> Title</b> ' +
                '</th>' +

                '<th>' +
                '<b> Records</b> '+
                '</th>' +

                '<th>' +
                '<b> Status</b> '+
                '</th>' +

                '<th>' +
                '<b> Purpose</b> '  +
                '</th>' +

                '<th>' +
                '<b> Created</b> '+
                '</th>' +

                '<th>' +
                '<b> Last_Event</b>' +
                '</th>' +

                '<th>' +
                '<b> Users</b> ' +
                '</th>' +

                '</tr>'

            );


            // Table instead of rows
            QD_Delete_Projects.forEach(function (QD_Deleted_Projects) {

                $('#Delete_Projects_Inner_Div').append(


                    '<tr>' +


                    '<td>' +
                    QD_Deleted_Projects["PID: "] +
                    '</td>' +

                                '<td>' +
                               QD_Deleted_Projects["Title: "]+
                                '</td>' +


                                '<td>' +
                                    QD_Deleted_Projects["Records: "] +
                                '</td>' +

                                '<td>' +
                                    QD_Deleted_Projects["Status: "] +
                                '</td>' +

                                '<td>' +
                                    QD_Deleted_Projects["Purpose: "] +
                                '</td>' +

                                '<td>' +
                                    QD_Deleted_Projects["Created: "] +
                                '</td>' +

                                '<td>' +
                                    QD_Deleted_Projects["Last Event: "] +
                                '</td>' +

                                '<td>' +
                                    QD_Deleted_Projects["Users: "] +
                                '</td>' +


                            '</tr>'

                );  // End delete projects inner div append




            });  // End forEach Deleted_Projects


            var QD_Restore_Table = '<table id="Restore_Confirm_Table" class="tablesorter">';

            $('#Restore_Projects_Inner_Div').append(

                $('#Restore_Projects_Inner_Div').append(QD_Restore_Table) +

                '<tr>' +

                '<th>' +
                '<b> PID</b> ' +
                '</th>' +

                '<th>' +
                '<b> Title</b> ' +
                '</th>' +



                '<th>' +
                '<b> Records</b> '+
                '</th>' +

                '<th>' +
                '<b> Status</b> '+
                '</th>' +

                '<th>' +
                '<b> Purpose</b> '  +
                '</th>' +

                '<th>' +
                '<b> Created</b> '+
                '</th>' +

                '<th>' +
                '<b> Last_Event</b>' +
                '</th>' +

                '<th>' +
                '<b> Users</b> ' +
                '</th>' +

                '</tr>'
            );


            // Table instead of rows
            QD_Restore_Projects.forEach(function (QD_Restored_Projects) {

                $('#Restore_Projects_Inner_Div').append(

                    '<tr>' +

                    '<td>' +
                    QD_Restored_Projects["PID: "] +
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Title: "]+
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Records: "] +
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Status: "] +
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Purpose: "] +
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Created: "] +
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Last Event: "] +
                    '</td>' +

                    '<td>' +
                    QD_Restored_Projects["Users: "] +
                    '</td>' +


                    '</tr>'

                );  // End delete projects inner div append


            });  // End forEach Deleted_Projects


            // console.log("Delete Projects Array: ");
            // console.log(QD_Delete_Projects);
            //
            // console.log("Restore Projects Array: ");
            // console.log(QD_Restore_Projects);


            if(QD_Delete_Projects.length !== 0 || QD_Restore_Projects.length !== 0) {
                $('#Confirmation_Modal').modal('show');
            }

            if(QD_Delete_Projects.length === 0) {
                $('div#Delete_Projects_Outer_Div').addClass("Hide_Header");
                $('div#Delete_Projects_Inner_Div').addClass("Hide_Header");
                // $('div#Delete_Projects_Div').html("");
                $('hr#Spacer').addClass("Hide_Header");
            }



            if(QD_Restore_Projects.length === 0) {
                $('div#Restore_Projects_Outer_Div').addClass("Hide_Header");
                $('div#Restore_Projects_Inner_Div').addClass("Hide_Header");
                // $('div#Restore_Projects_Div').html("");
                $('hr#Spacer').addClass("Hide_Header");
            }

            

            // $('#Cancel_Button_Checkboxes').click(function(){
            //
            //
            //     $('#reset').click();
            // });



        });  // End on send button click




        $('#Accept_Send_Checkboxes').click(function(){


            $.ajax({
                method: 'POST',
                url: UIOWA_QuickDeleter.submitUrl,
                data: {
                    pid_box: $("#PID_Box").val()
                    // custom_box:  Custom_Value,

                }

            })
                .done(function() {


                    if(window.location.href.indexOf("tab=2") > -1)

                        $("#Custom_Page").click();

                    else

                        document.location.reload();

                });



            // var Submit_Form = document.getElementById("Form").submit();
            // if(Tab === 3) {
            //     document.getElementById("Custom_Box").submit();
            // }
            // console.log(Submit_Form);
        });






        // Delete modal contents when confirmation modal closes
        $('#Confirmation_Modal').on('hide.bs.modal', function () {


            $('div#Delete_Projects_Inner_Div').html("");


            $('div#Restore_Projects_Inner_Div').html("");



        });




        //Displays projects set for delete and restore on submit confirmation
        // $('#send_button').click(function() {
        //
        //     // Finds if project was already set for delete or not
        //     var Delete_Flagged = $(".PID_Checkbox:checked", "#Projects_Table").map(function () {
        //         return $(this).prop('id');
        //     }).get();
        //
        //     // Gets title for selected projects
        //     var Selected_Projects = $(".PID_Checkbox:checked", "#Projects_Table").map(function () {
        //         return $(this).parent().parent().find('td:eq(2)').text().trim();
        //     }).get();
        //
        //
        //
        //
        //     //  Creates object with project title as key and delete flag as value
        //     var Combined_Array = {};
        //     for (var i = 0; i < Selected_Projects.length; i++) {
        //         Combined_Array[Selected_Projects[i]] = Delete_Flagged[i];
        //     }
        //
        //     // Create arrays and variables
        //     var Delete_Projects = [];
        //     var Restore_Projects = [];
        //     // var Delete = "";
        //     // var Restore = "";
        //     // var Line_Breaks = "";
        //
        //     // Get value (delete flag) of object key, checks if 0 or 1, puts project title in array
        //     for (key in Combined_Array) {
        //         if (Combined_Array.hasOwnProperty(key)) {
        //             var value = Combined_Array[key];
        //             if (value === "0") {
        //
        //                 Delete_Projects = Delete_Projects.concat(key);
        //
        //             } else {
        //
        //                 Restore_Projects = Restore_Projects.concat(key);
        //
        //             }
        //         }
        //     }
        //
        //     // console.log(Delete_Projects);
        //
        //
        //     UIOWA_QuickDeleter.arrayOfValues = [];
        //
        //         $('.PID_Checkbox:checked', '#Projects_Table').each(function() {
        //
        //             var rowTds = $(".PID_Checkbox:checked", "#Projects_Table").closest('tr').children().not(':first');
        //
        //             // drop first (empty) header
        //             // rowTds = $(rowTds).not(':first');
        //
        //             // build object with project info
        //             for (var i in UIOWA_QuickDeleter.tableHeaders) {
        //                 var currHeader = UIOWA_QuickDeleter.tableHeaders[i];
        //
        //                 UIOWA_QuickDeleter.selectedProjectInfo[currHeader] = $(rowTds[i]).text().trim();
        //                 UIOWA_QuickDeleter.arrayOfValues.push(UIOWA_QuickDeleter.selectedProjectInfo[currHeader]);
        //
        //             }
        //
        //             console.log(UIOWA_QuickDeleter.arrayOfValues);
        //
        //
        //         });
        //
        //
        //
        //
        //     console.log(UIOWA_QuickDeleter.selectedProjectInfo);
        //
        //
        //     if(Delete_Projects.length === 0) {
        //         $("#Delete_Projects_Div").addClass("Hide_Header");
        //     }
        //     else {
        //         $("#Delete_Projects_Div").removeClass("Hide_Header");
        //     }
        //     if(Restore_Projects.length === 0) {
        //         $("#Restore_Projects_Div").addClass("Hide_Header");
        //     }
        //     else {
        //         $("#Restore_Projects_Div").removeClass("Hide_Header");
        //     }
        //
        //     // $('#Delete_Projects_Div').html( '<b id="Delete_Projects_Header">"DELETE:"</b>');
        //     $('#Delete_Projects_Div').html('<b style="color:red" id="Delete_Projects_Header">DELETE:</br></b>' + '<span id="Delete_Projects_Span">' + Delete_Projects.join('</br>')+'</span>');
        //     $('#Restore_Projects_Div').html('<b style="color:green" id="Restore_Projects_Header">RESTORE:</br></b>' +  '<span id="Restore_Projects_Span">' + Restore_Projects.join('</br>')+'</span>');
        //
        //     // Submits form to delete/restore projects when "Accept" is clicked on the confirmation modal
        //     $('#Accept_Send_Checkboxes').click(function(){
        //
        //
        //         $("#Hidden_Submit").click();
        //
        //     });
        // });


        // Confirmation popup for delete/restore via button
        $('#Projects_Table button').click(function() {

            if ($(this).text() === "Delete") {
                $(this).closest('tr').css("backgroundColor", "rgba(255, 0, 0, 0.8)").css({fontWeight: 'bold'}).css("color", "black");
            } else {
                // console.log($(this).attr('id'));
                $(this).closest('tr').addClass("Select_Restore_Row");
            }

            // get td elements
            var rowTds = $(this).closest('tr').children();

            // drop first (empty) header
            rowTds = $(rowTds).not(':first');

            // build object with project info
            for (var i in UIOWA_QuickDeleter.tableHeaders) {
                var currHeader = UIOWA_QuickDeleter.tableHeaders[i];

                UIOWA_QuickDeleter.selectedProjectInfo[currHeader] = $(rowTds[i]).text().trim();

            }

            // console.log(UIOWA_QuickDeleter.selectedProjectInfo);

            var action = "RESTORED".fontcolor("green");

            if (UIOWA_QuickDeleter.selectedProjectInfo['Deleted'] === "") {
                action = "DELETED".fontcolor("red");
            }


            $('#Modify_Individual_Project_Div').html(
                '<b style="font-size:16px">Confirm that the following project should be ' + action + ':' +
                '<br/><br/></b>' + '<span style="font-weight:bold" id="Modify_Individual_Project_Span">' + UIOWA_QuickDeleter.selectedProjectInfo['Project Name']+'</span>' +
                '<br/><b>PID:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['PID'] +
                '<br/>' +
                '<br/><b>Record count:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['Records'] +
                '<br/>' +
                '<br/><b>Status:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['Status'] +
                '<br/><b>Purpose:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['Purpose'] +
                '<br/>' +
                '<br/><b>Created:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['Created'] +
                '<br/><b>Last_Event:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['Last Event'] +
                '<br/>' +
                '<br/><b>Users:</b> ' + UIOWA_QuickDeleter.selectedProjectInfo['Users']
            );





            $('#Accept_Send_Button').click(function(){
                $.ajax({
                    method: 'POST',
                    url: UIOWA_QuickDeleter.submitUrl,
                    data: {
                        pid: UIOWA_QuickDeleter.selectedProjectInfo['PID'],
                        action: UIOWA_QuickDeleter.selectedProjectInfo['Deleted'] === '' ? 'delete' : 'restore'
                    }
                })
                    .done(function() {
                        if(window.location.href.indexOf("tab=2") > -1)

                            $("#Custom_Page").click();

                        else

                            document.location.reload();
                    });

                    $('#reset').click();

            });

            $('#Cancel_Button_Individual').click(function(){
                $('#reset').click();
            });


        });


        //  Adds DELETE or RESTORE to Action column on box checked
        $('.PID_Checkbox').on('click', function() {
            if ($(this).is(':checked'))
                if ($(this).prop('id') === '0')
                    $(this).closest("tr").find("td#Row_Action").text("DELETE");
                else
                    $(this).closest("tr").find("td#Row_Action").text("RESTORE");
            else
                $(this).closest("tr").find("td#Row_Action").text("");

        });


        $("#check_all").on('change', function () {
            var PID_Checkboxes = $(".PID_Checkbox");
            // console.log($(this));
            PID_Checkboxes.each(function () {
                if ($(this).is(':checked'))
                    if ($(this).prop('id') === '0')
                        $(this).closest("tr").find("td#Row_Action").text("DELETE");
                    else
                        $(this).closest("tr").find("td#Row_Action").text("RESTORE");
                else
                    $(this).closest("tr").find("td#Row_Action").text("");
            });
        });

        // Removes checked row color, column filter, and action on form reset
        $('#reset').on('click', function() {
            $('tr').css("backgroundColor", "").css({fontWeight: 'normal'});
            $('tr').closest('tr').removeClass("Select_Restore_Row");
            $("td#Row_Action").text("");

            $('#Projects_Table').trigger('sortReset');

                $("#send_button").hide();

        });


        // Avoids having to resubmit the form on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

    });

}(window.jQuery, window, document));