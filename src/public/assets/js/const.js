const dataTableOption = {
    dom: `<"card-body px-0 pt-0 pb-2" <"table-responsive p-0" t>><"card-body d-flex flex-wrap justify-content-between align-items-center px-3 pt-2 pb-2" <l> <"ml-auto" p>>`,
    pagingType: `first_last_numbers`,
    oLanguage: {
        sLengthMenu:  `_MENU_`,
        oPaginate: {
            sFirst: `<i class="fa-solid fa-backward-step"></i>`,
            sLast: `<i class="fa-solid fa-forward-step"></i>`,
        },
    },
    language: {
        url: dttbl.language,
    },
    lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    fnDrawCallback: function(oSettings) {
        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
        } else {
            $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
        }
    }
}