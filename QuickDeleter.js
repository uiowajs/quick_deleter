
// Tablesorter
(function($, window, document) {
    $(document).ready(function() {
        $("#Projects_Table").tablesorter({

            theme: 'blue',
            widthFixed: true,
            usNumberFormat: true,
            sortReset: false,
            sortRestart: false,
            widgets: ['filter', 'pager', 'scroller'],

            widgetOptions: {

                stickyHeaders_offset: 50,

                // scroller_upAfterSort: true,
                // // pop table header into view while scrolling up the page
                // scroller_jumpToHeader: true,
                //
                // scroller_height : 300,
                // // set number of columns to fix
                // scroller_fixedColumns : startFixedColumns,
                // // add a fixed column overlay for styling
                // scroller_addFixedOverlay : false,
                // // add hover highlighting to the fixed column (disable if it causes slowing)
                // scroller_rowHighlight : 'hover',
                //
                // // bar width is now calculated; set a value to override
                // scroller_barWidth : null



            }



        });
    });
}(window.jQuery, window, document));