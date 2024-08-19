let tableDetail;

let tableCalcCriteriaStudent;

let tableCalcCriteriaWeight;

let tableCalcCriteriaValue;

let tableCalcCriteriaResult;

let tableCalcPreferenceWeight;

let tableCalcPreferenceResult;

let tableResult;

const columnsCriteriaOpt = () => {
    let col = [
        {data:"nis", name:"nis", searchable:true, orderable:true},
        {data:"nama", name:"nama", searchable:true, orderable:true}
    ];

    $.each(criteria, function (index, value) {
        col.push({data:value, name:value, searchable:false, orderable:false});
    });
    return col
}

const columnsPreferenceRes = () => {
    let col = [
        {data:"nis", name:"nis", searchable:true, orderable:true},
        {data:"nama", name:"nama", searchable:true, orderable:true}
    ];

    $.each(criteria, function (index, value) {
        col.push({data:value, name:value, searchable:false, orderable:false});
    });
    col.push({data:"total", name:"total", searchable:false, orderable:false});
    return col
}

const reloadTable = (type) => {
    switch (type) {
        case "detail":
            tableDetail.ajax.reload();
            break;

        case "result":
            tableResult.ajax.reload();
            break;
    }
}

const loadTableDetail = () => {
    let tableDetailOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: columnsCriteriaOpt(),
        order: [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables.detail,
            beforeSend: function() {
                if (typeof tableDetail != "undefined" && tableDetail.hasOwnProperty('settings') && tableDetail.settings()[0].jqXHR != null) {
                    tableDetail.settings()[0].jqXHR.abort();
                }
            },
            data: function (param) {
                param.search = $("#search-detail").val();
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            // console.log(row);
            // console.log(data);
            // console.log(dataIndex);
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            let eq = 2;
            $.each(criteria, function (index, value) {
                $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold");
                eq++;
            });
        }
    }, dataTableOption);
    tableDetail = $("#table-detail").DataTable(tableDetailOpt);
}

const loadTableCalcCriteriaStudent = () => {
    let tableCalcOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: columnsCriteriaOpt(),
        order: [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables.calc.criteria.student,
            beforeSend: function() {
                if (typeof tableCalcCriteriaStudent != "undefined" && tableCalcCriteriaStudent.hasOwnProperty('settings') && tableCalcCriteriaStudent.settings()[0].jqXHR != null) {
                    tableCalcCriteriaStudent.settings()[0].jqXHR.abort();
                }
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            let eq = 2;
            $.each(criteria, function (index, value) {
                $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold");
                eq++;
            });
        }
    }, dataTableOption);
    tableCalcCriteriaStudent = $("#table-calc-criteria-students").DataTable(tableCalcOpt);
}

const loadTableCalcCriteriaWeigth = () => {
    let tableCalcOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: [
            {data:"nama", name:"nama", searchable:false, orderable:false},
            {data:"bobot", name:"bobot", searchable:false, orderable:false},
            {data:"min", name:"min", searchable:false, orderable:false},
            {data:"max", name:"max", searchable:false, orderable:false},
        ],
        order: [[ 0, 'asc' ]],
        ajax: {
            url: url.datatables.calc.criteria.weight,
            beforeSend: function() {
                if (typeof tableCalcCriteriaWeight != "undefined" && tableCalcCriteriaWeight.hasOwnProperty('settings') && tableCalcCriteriaWeight.settings()[0].jqXHR != null) {
                    tableCalcCriteriaWeight.settings()[0].jqXHR.abort();
                }
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-start text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(2)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(3)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
        }
    }, dataTableOption);
    tableCalcCriteriaWeight = $("#table-calc-weigth-criteria").DataTable(tableCalcOpt);
}

const loadTableCalcCriteriaValue = () => {
    let tableCalcOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: columnsCriteriaOpt(),
        order: [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables.calc.criteria.value,
            beforeSend: function() {
                if (typeof tableCalcCriteriaValue != "undefined" && tableCalcCriteriaValue.hasOwnProperty('settings') && tableCalcCriteriaValue.settings()[0].jqXHR != null) {
                    tableCalcCriteriaValue.settings()[0].jqXHR.abort();
                }
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            let eq = 2;
            $.each(criteria, function (index, value) {
                $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold");
                eq++;
            });
        }
    }, dataTableOption);
    tableCalcCriteriaValue = $("#table-calc-criteria-values").DataTable(tableCalcOpt);
}

const loadTableCalcCriteriaResult = () => {
    let tableCalcOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: columnsCriteriaOpt(),
        order: [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables.calc.criteria.result,
            beforeSend: function() {
                if (typeof tableCalcCriteriaResult != "undefined" && tableCalcCriteriaResult.hasOwnProperty('settings') && tableCalcCriteriaResult.settings()[0].jqXHR != null) {
                    tableCalcCriteriaResult.settings()[0].jqXHR.abort();
                }
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            let eq = 2;
            $.each(criteria, function (index, value) {
                $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold");
                eq++;
            });
        }
    }, dataTableOption);
    tableCalcCriteriaResult = $("#table-calc-result-criteria").DataTable(tableCalcOpt);
}

const loadTableCalcPreferenceWeigth = () => {
    let tableCalcOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: columnsCriteriaOpt(),
        order: [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables.calc.preference.weight,
            beforeSend: function() {
                if (typeof tableCalcPreferenceWeigth != "undefined" && tableCalcPreferenceWeigth.hasOwnProperty('settings') && tableCalcPreferenceWeigth.settings()[0].jqXHR != null) {
                    tableCalcPreferenceWeigth.settings()[0].jqXHR.abort();
                }
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            let eq = 2;
            $.each(criteria, function (index, value) {
                $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold");
                eq++;
            });
        }
    }, dataTableOption);
    tableCalcPreferenceWeight = $("#table-calc-preference-weight").DataTable(tableCalcOpt);
}

const loadTableCalcPreferenceResult = () => {
    let tableCalcOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: columnsPreferenceRes(),
        order: [[ 1, 'asc' ]],
        ajax: {
            url: url.datatables.calc.preference.result,
            beforeSend: function() {
                if (typeof tableCalcPreferenceResult != "undefined" && tableCalcPreferenceResult.hasOwnProperty('settings') && tableCalcPreferenceResult.settings()[0].jqXHR != null) {
                    tableCalcPreferenceResult.settings()[0].jqXHR.abort();
                }
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            let eq = 2;
            $.each(criteria, function (index, value) {
                $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold");
                eq++;
            });
            $(row).find(`td:eq(${eq})`).addClass("align-middle text-center text-secondary text-xs font-weight-bold bg-success text-white");
        }
    }, dataTableOption);
    tableCalcPreferenceResult = $("#table-calc-preference-result").DataTable(tableCalcOpt);
}

const loadTableResult = () => {
    let tableResultOpt = replaceObject({
        dom: `<"table-responsive p-0" <"card-body px-0 pt-0 pb-2" t><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>>`,
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: [
            {data:"nis", name:"nis", searchable:true, orderable:true},
            {data:"nama", name:"nama", searchable:true, orderable:true},
            {data:"total_preferensi", name:"total_preferensi", searchable:true, orderable:true},
            {data:"biaya", name:"biaya", searchable:true, orderable:true},
        ],
        order: [[ 2, 'desc' ]],
        ajax: {
            url: url.datatables.result,
            beforeSend: function() {
                if (typeof tableResult != "undefined" && tableResult.hasOwnProperty('settings') && tableResult.settings()[0].jqXHR != null) {
                    tableResult.settings()[0].jqXHR.abort();
                }
            },
            data: function (param) {
                param.search = $("#search-result").val();
            }
        },
        processing: true,
        serverSide: true,
        createdRow: function( row, data, dataIndex ) {
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(2)').addClass("align-middle text-center text-secondary text-xs font-weight-bold bg-success text-white");
            $(row).find('td:eq(3)').addClass("align-middle text-end text-secondary text-xs font-weight-bold bg-dark text-white");
        }
    }, dataTableOption);
    tableResult = $("#table-result").DataTable(tableResultOpt);
}

const detailTab = async () => {
    if (tableDetail == undefined) {
        await loadTableDetail();
    }
}

const calcTab = async () => {
    if (tableCalcCriteriaStudent == undefined) {
        await loadTableCalcCriteriaStudent();
    }
}

const resultTab = async () => {
    if (tableResult == undefined) {
        await loadTableResult();
    }
}

$(function () {
    detailTab();

    let showTab = $("#show-tab").find('a[data-bs-toggle="tab"]');
    showTab.on('shown.bs.tab', function(event){
        let current = event.target; // newly activated tab
        // let previous =  event.relatedTarget; // previous active tab
        let el = $(current);
        let acontrol = el.attr("aria-controls");
        switch (acontrol) {
            case "detail":
                detailTab();
                break;

            case "calculation":
                calcTab();
                break;

            case "result":
                resultTab();
                break;

            default:
                detailTab();
                break;
        }

    });

    $("#search-detail").keyup(function (e) {
        reloadTable('detail');
    });

    $("#search-result").keyup(function (e) {
        reloadTable('result');
    });

    $("#criteria-students").on("shown.bs.collapse", async function(e) {
        if (tableCalcCriteriaStudent == undefined) {
            await loadTableCalcCriteriaStudent();
        }
    });

    $("#weigth-criteria-values").on("shown.bs.collapse", async function(e) {
        if (tableCalcCriteriaWeight == undefined) {
            await loadTableCalcCriteriaWeigth();
        }
    });

    $("#criteria-values").on("shown.bs.collapse", async function(e) {
        if (tableCalcCriteriaValue == undefined) {
            await loadTableCalcCriteriaValue();
        }
    });

    $("#result-criteria").on("shown.bs.collapse", async function(e) {
        if (tableCalcCriteriaResult == undefined) {
            await loadTableCalcCriteriaResult();
        }
    });

    $("#preference-weight").on("shown.bs.collapse", async function(e) {
        if (tableCalcPreferenceWeight == undefined) {
            await loadTableCalcPreferenceWeigth();
        }
    });

    $("#preference-result").on("shown.bs.collapse", async function(e) {
        if (tableCalcPreferenceResult == undefined) {
            await loadTableCalcPreferenceResult();
        }
    });
});
