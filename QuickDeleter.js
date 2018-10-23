

(function($, window, document) {
    $(document).ready(function() {

        // Tablesorter
        $("#Projects_Table").tablesorter({

            theme: 'blue',
            widthFixed: true,
            usNumberFormat: true,
            sortReset: false,
            sortRestart: false,
            widgets: ['filter', 'pager', 'scroller'],

            widgetOptions: {

                stickyHeaders_offset: 50,
                filter_reset : '.reset_button'


            }

        });


        // Puts comma separated values of checkboxes in PID_Box.
        $("form[name=Form]").on("change", "input[type=checkbox]", function () {
            var values = $.map($("input[type=checkbox]:checked"), function (pid) {
                return pid.value;
            });
            $("form[name=Form]").find("input[id=PID_Box]").val(values);
        });

        // Highlights all rows when check all box checked
        $(document).ready(function () {
            $("#check_all").on('change', function () {
                var PID_Checkboxes = $(".PID_Checkbox");
                // console.log($(this));
                PID_Checkboxes.each(function () {

                    // console.log($(this).checked);
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

        // Displays projects set for delete and restore on submit confirmation
        $('#submit').click(function() {

            // Finds if project was already set for delete or not
            var Delete_Flagged = $("input:checkbox:checked", "#Projects_Table").map(function () {
                return $(this).prop('id');
            }).get();

            // Gets title for selected projects
            var Selected_Projects = $("input:checkbox:checked", "#Projects_Table").map(function () {
                return $(this).parent().parent().find('td:eq(2)').text();
            }).get();

            // Removes spaces before and after title in array element
            Selected_Projects = Selected_Projects.map(function (el) {
                return el.trim();
            });

            //  Creates object with project title as key and delete flag as value
            var Combined_Array = {};
            for (var i = 0; i < Selected_Projects.length; i++) {
                Combined_Array[Selected_Projects[i]] = Delete_Flagged[i];
            }

            // Create arrays and variables
            var Delete_Projects = [];
            var Restore_Projects = [];
            var Delete = "";
            var Restore = "";
            var Line_Breaks = "";

            // Get value (delete flag) of object key, checks if 0 or 1, puts project title in array
            for (key in Combined_Array) {
                if (Combined_Array.hasOwnProperty(key)) {
                    var value = Combined_Array[key];
                    if (value === "0") {
                        // console.log(value);
                        // console.log("Deleting");
                        Delete_Projects = Delete_Projects.concat(key);
                        if (Delete_Projects.length === 0) {
                            Delete = "";
                            Line_Breaks = "";
                        }
                        else {
                            Delete = "DELETE:\n";
                            Line_Breaks = "\n\n";
                        }
                    } else {
                        // console.log(value);
                        // console.log("Restoring");
                        Restore_Projects = Restore_Projects.concat(key);
                        if (Restore_Projects.length === 0) {
                            Restore = "";
                        }
                        else {
                            Restore = "RESTORE:\n"
                        }
                    }
                }
            }

            // Displays each project title on new line
            Delete_Projects = Delete_Projects.join("\n");  // Prints each array element on new line
            Restore_Projects = Restore_Projects.join("\n");  // Prints each array element on new line

            // Confirmation dialog popup on submit
            if(Delete_Projects.length === 0 && Restore_Projects.length === 0) {
                return false;
            }
            else {
                if (!confirm("Confirm that the following projects should be modified: \n\n" +
                    Delete + Delete_Projects + Line_Breaks + Restore + Restore_Projects)
                ) return false;
            }
        });



        // $('.PID_Checkbox').change(function () {
        //     $('#submit').prop("disabled", !this.checked);
        // }).change();




        // Avoids having to resubmit the form on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }


    });


}(window.jQuery, window, document));