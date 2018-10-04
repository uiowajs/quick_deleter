
// Tablesorter
(function($, window, document) {
    $(document).ready(function() {
        $("#Projects_Table").tablesorter({

            theme: 'blue',
            widthFixed: true,
            usNumberFormat: true,
            sortReset: false,
            sortRestart: false,
            widgets: ['filter', 'stickyHeaders', 'pager']

        });
    });
}(window.jQuery, window, document));