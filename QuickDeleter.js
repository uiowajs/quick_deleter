
// Tablesorter
(function($, window, document) {
$(document).ready(function()
    {
        $("#Projects_Table").tablesorter({

		theme: 'blue',
        widthFixed: true,
        usNumberFormat: false,
        sortReset: false,
        sortRestart: true,
		widgets: ['filter', 'stickyHeaders', 'pager']

        });
    }
);
}(window.jQuery, window, document));