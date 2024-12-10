(function($) {
    'use strict';

    $(function() {
        if($('#journal-entry-table').length){
            journalEntryTable('#journal-entry-table', true, true);
        }

        $(document).on('click','#apply-filter',function() {
            journalEntryTable('#journal-entry-table', true, true);
        });
    });
})(jQuery);

function journalEntryTable(datatable_name, buttons = false, show_all = false){
    const type = 'journal entry table';
    var filter_journal_entry_date_start_date = $('#filter_journal_entry_date_start_date').val();
    var filter_journal_entry_date_end_date = $('#filter_journal_entry_date_end_date').val();

    const column = [ 
        { 'data' : 'JOURNAL_ENTRY_DATE' },
        { 'data' : 'REFERENCE_CODE' },
        { 'data' : 'JOURNAL_ID' },
        { 'data' : 'JOURNAL_ITEM' },
        { 'data' : 'DEBIT' },
        { 'data' : 'CREDIT' },
        { 'data' : 'JOURNAL_LABEL' },
        { 'data' : 'ANALYTIC_LINES' },
        { 'data' : 'ANALYTIC_DISTRIBUTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_journal_entry_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_journal_entry_date_start_date' : filter_journal_entry_date_start_date, 
                'filter_journal_entry_date_end_date' : filter_journal_entry_date_end_date, 
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}