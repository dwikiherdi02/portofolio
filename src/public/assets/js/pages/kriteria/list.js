const reloadTable = () => {
    tableList.ajax.reload();
}

$(function () {
    const tableListOpt = replaceObject({
        columns: [
            {data:"nama", name:"nama", searchable:true, orderable:true},
            {data:"kode", name:"kode", searchable:true, orderable:true},
            {data:"nama_jenis_kriteria", name:"nama_jenis_kriteria", searchable:true, orderable:false},
            {data:"bobot", name:"bobot", searchable:true, orderable:true},
            {data:"aksi", name:"aksi", searchable:false, orderable:false},
        ],
        "order": [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables,
            beforeSend: function() {
                if (typeof tableList != "undefined" && tableList.hasOwnProperty('settings') && tableList.settings()[0].jqXHR != null) {
                    tableList.settings()[0].jqXHR.abort();
                }
            },
            data: function (param) {
                param.search = $("#search").val();
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(2)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(3)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(4)').addClass("align-middle text-end");
        }
    }, dataTableOption);
    tableList = $("#table-list").DataTable(tableListOpt);

    $("#search").keyup(function (e) {
        reloadTable();
    });
});
