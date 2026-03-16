(function ($) {
    'use strict';

    $(document).ready(function () {

        // Initial load
        if ($('#daily-employee-late-status-table').length) {
            dailyEmployeeLateStatus('#daily-employee-late-status-table');
        }

        if ($('#daily-employee-absentism-status-table').length) {
            dailyEmployeeAbsentismStatus('#daily-employee-absentism-status-table');
        }

        // Apply filter
        $('#apply-filter').on('click', function () {
            const month  = parseInt($('#filter_month').val());
            const year   = parseInt($('#filter_year').val());
            const cutoff = parseInt($('#filter_cutoff_period').val());

            const startDay = cutoff === 1 ? 1 : 16;
            const endDay   = cutoff === 1 ? 15 : new Date(year, month, 0).getDate();

            rebuildTable('#daily-employee-late-status-table', startDay, endDay);
            rebuildTable('#daily-employee-absentism-status-table', startDay, endDay);

            dailyEmployeeLateStatus('#daily-employee-late-status-table');
            dailyEmployeeAbsentismStatus('#daily-employee-absentism-status-table');
        });

    });

})(jQuery);

/* ---------------------------------------------------
|  HELPERS
--------------------------------------------------- */

function rebuildTable(tableSelector, startDay, endDay) {
    const table = $(tableSelector);

    if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().clear().destroy();
    }

    let headerHtml = `<tr><th>Company</th><th>Employee</th>`;
    let footerHtml = `<tr><th colspan="2" class="text-end">TOTAL</th>`;

    for (let d = startDay; d <= endDay; d++) {
        headerHtml += `<th class="text-center">${d}</th>`;
        footerHtml += `<th class="text-center"></th>`;
    }

    headerHtml += `<th class="text-center">Total</th></tr>`;
    footerHtml += `<th class="text-center"></th></tr>`;

    table.find('thead').html(headerHtml);
    table.find('tfoot').html(footerHtml);
    table.find('tbody').empty();
}

function footerTotals(api, startColIndex) {

    const intVal = i => typeof i === 'string'
        ? i.replace(/[^\d.-]/g, '') * 1
        : typeof i === 'number'
            ? i
            : 0;

    api.columns().every(function (colIdx) {

        if (colIdx < startColIndex) return;

        const total = this.data().reduce((a, b) => intVal(a) + intVal(b), 0);

        $(api.column(colIdx).footer()).html(total || '0');
    });
}

/* ---------------------------------------------------
|  LATE / UNDERTIME TABLE
--------------------------------------------------- */

function dailyEmployeeLateStatus(datatable) {

    const month  = parseInt($('#filter_month').val());
    const year   = parseInt($('#filter_year').val());
    const cutoff = parseInt($('#filter_cutoff_period').val());

    const startDay = cutoff === 1 ? 1 : 16;
    const endDay   = cutoff === 1 ? 15 : new Date(year, month, 0).getDate();

    const columns = [
        { data: 'COMPANY' },
        { data: 'EMPLOYEE' }
    ];

    for (let d = startDay; d <= endDay; d++) {
        columns.push({
            data: 'DAY_' + d,
            className: 'text-center'
        });
    }

    columns.push({
        data: 'TOTAL',
        className: 'fw-bold text-center'
    });

    $(datatable).DataTable({
        ajax: {
            url: 'view/_daily_employee_status_generation.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: 'employee attendance late summary table',
                month: month,
                year: year,
                cutoff: cutoff
            },
            dataSrc: ''
        },
        columns: columns,
        order: [[0, 'asc'], [1, 'asc']],

        // ✅ Added searching capability by including "f" in dom
        dom:
            "<'row mb-2'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-end'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",

        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Attendance Summary',
                footer: true,
                exportOptions: { footer: true }
            },
            {
                extend: 'csvHtml5',
                title: 'Attendance Summary',
                footer: true,
                exportOptions: { footer: true }
            },
            {
                extend: 'pdfHtml5',
                title: 'Attendance Summary',
                orientation: 'landscape',
                pageSize: 'A4',
                footer: true,
                exportOptions: { footer: true }
            }
        ],
        footerCallback: function () {
            footerTotals(this.api(), 2);
        },
        language: {
            search: "",
            searchPlaceholder: "Search employee / company...",
            emptyTable: 'No late or undertime records found'
        }
    });
}

/* ---------------------------------------------------
|  ABSENTISM TABLE (UNPAID LEAVE)
--------------------------------------------------- */

function dailyEmployeeAbsentismStatus(datatable) {

    const month  = parseInt($('#filter_month').val());
    const year   = parseInt($('#filter_year').val());
    const cutoff = parseInt($('#filter_cutoff_period').val());

    const startDay = cutoff === 1 ? 1 : 16;
    const endDay   = cutoff === 1 ? 15 : new Date(year, month, 0).getDate();

    const columns = [
        { data: 'COMPANY' },
        { data: 'EMPLOYEE' }
    ];

    for (let d = startDay; d <= endDay; d++) {
        columns.push({
            data: 'DAY_' + d,
            className: 'text-center'
        });
    }

    columns.push({
        data: 'TOTAL',
        className: 'fw-bold text-center'
    });

    $(datatable).DataTable({
        ajax: {
            url: 'view/_daily_employee_status_generation.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: 'employee attendance absentism summary table',
                month: month,
                year: year,
                cutoff: cutoff
            },
            dataSrc: ''
        },
        columns: columns,
        order: [[0, 'asc'], [1, 'asc']],

        // ✅ Added searching capability by including "f" in dom
        dom:
            "<'row mb-2'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-end'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",

        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Absentism Summary',
                footer: true,
                exportOptions: { footer: true }
            },
            {
                extend: 'csvHtml5',
                title: 'Absentism Summary',
                footer: true,
                exportOptions: { footer: true }
            },
            {
                extend: 'pdfHtml5',
                title: 'Absentism Summary',
                orientation: 'landscape',
                pageSize: 'A4',
                footer: true,
                exportOptions: { footer: true }
            }
        ],
        footerCallback: function () {
            footerTotals(this.api(), 2);
        },
        language: {
            search: "",
            searchPlaceholder: "Search employee / company...",
            emptyTable: 'No unpaid leave records found'
        }
    });
}
