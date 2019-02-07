var UIOWA_QuickDeleter = {};

UIOWA_QuickDeleter.selectedProjectInfo = {};



(function($, window, document) {
    $(document).ready(function() {



        UIOWA_QuickDeleter.checks = $(".PID_Checkbox").on('change', function()
        {
            var checked = UIOWA_QuickDeleter.checks.is(':checked');

            $("#send_button").toggle(!checked);
            $("#send_button").toggle(checked);

        });

        UIOWA_QuickDeleter.checks.first().change();


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

            // Required to create the table without auto closing the tag
            var QD_Delete_Table = '<table id="Delete_Confirm_Table" >';

            $('#modal-body-top').html(
            '<b style="font-size:16px">Confirm that the following ' + QD_Count_Projects + ' project(s) will be modified:</b>'
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


            var QD_Restore_Table = '<table id="Restore_Confirm_Table" >';

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

        });


        // Delete modal contents when confirmation modal closes
        $('#Confirmation_Modal').on('hide.bs.modal', function () {


            $('div#Delete_Projects_Inner_Div').html("");


            $('div#Restore_Projects_Inner_Div').html("");

        });


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
                '<b style="font-size:16px">Confirm that the following project will be ' + action + ':' +
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




        });

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