<?php

//require_once APP_PATH_DOCROOT . 'ControlCenter/header.php';
$page = new HtmlPage();
$page->PrintHeaderExt();
include APP_PATH_VIEWS . 'HomeTabs.php';

$QuickDeleter = new \UIOWA\QuickDeleter\QuickDeleter();

if(isset($_POST['submit']))
    {
    $QuickDeleter->Submit();
}
else
    {
    $QuickDeleter->Display_Home_Page();  // Display_Projects_Table
}
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
