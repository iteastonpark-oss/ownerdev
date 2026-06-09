/**
 * Created by iy2 on 10/2/2016.
 */

$(document).ready(function () {
    table = $('.datatables-scrool').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true,
        "filter": false,
        "paging": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "order": [[0, 'asc']],
    });
    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

});


function select(a) {
    var theForm = document.myForm;
    for (var i = 0; i < theForm.elements.length; i++) {
        if (theForm.elements[i].name == 'check[]')
            theForm.elements[i].checked = a;
    }
}
