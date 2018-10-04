
// Tablesorter
(function($, window, document) {
    $(document).ready(function() {
        $("#Projects_Table").tablesorter({

            theme: 'blue',
            widthFixed: true,
            usNumberFormat: true,
            sortReset: false,
            sortRestart: false,
            widgets: ['stickyHeaders', 'filter', 'pager'],

            widgetOptions: {

                stickyHeaders_offset: 50,

            }



        });
    });
}(window.jQuery, window, document));