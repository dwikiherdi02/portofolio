let tableList;

let isFormUnggahSiswa = "";

let students = [];

let isExist = false;

const reloadTable = () => {
    tableList.ajax.reload();
}

const clearFileStudents = () => {
    const file = $("#berkas");
    file.val("");
    file.parent().find("span").text(defaultFileText);
}

const alertConfirmDeleteExistingData = () => {
    let swalCustom = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-link text-dark"
        },
        buttonsStyling: false
    });
    swalCustom.fire({
        text: "Unggah berkas siswa akan menghapus data siswa yang sebelumnya dibuat. Apakah kamu yakin?",
        icon: "warning",
        showClass: { popup: ` animate__animated animate__pulse animate__faster ` },
        hideClass: { popup: ``},
        showCancelButton: true,
        confirmButtonText: "Ya, Saya Yakin!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            showProcessStoringData();
        } else {
            students = [];
        }
    });
}

const showProcessStoringData = async () => {
    const modal = $("#process-storing");
    const count = students.length;

    modal.modal("show");

    let progressTypeOther = modal.find(".progress-type-other");
    let progressTypeProcess = modal.find(".progress-type-process");
    let progressMessage = progressTypeOther.find(".progress-label").find(".progress-message");
    let progress = progressTypeProcess.find(".progress").find("div");
    let totalData = progressTypeProcess.find(".progress-label").find(".progress-total");
    let processedData = progressTypeProcess.find(".progress-label").find(".progress-current");

    progressTypeOther.show();
    progressTypeProcess.hide();

    if (isExist == true) {
        progressMessage.text("Menghapus data sebelumnya..");
        await deleteExistData();
        await timeout(3000);

        progressMessage.text("Data berhasil dihapus..");
        await timeout(2000);
    }

    progressMessage.text("Proses simpan data siswa..");
    await timeout(1000);

    progress.css("width", "0%");
    totalData.text(count);
    processedData.text(0);

    progressTypeOther.hide();
    progressTypeProcess.show();
    for (let i = 0; i < count; i++) {
        var value = students[i];
        await processStoringData(value);

        var pct = (parseFloat(i+1)/parseFloat(count)) * parseFloat(100);
        progress.css("width", `${pct}%`);
        processedData.text(i+1);
    }

    modal.modal("hide");
}

const deleteExistData = () => {
    return $.ajax({
        type: "POST",
        url: url.deletingProcess,
        data: {_method: "DELETE"},
        dataType: "json",
    });
}

const processStoringData = (data) => {
    return $.ajax({
        type: "POST",
        url: url.storingProcess,
        data: data,
        dataType: "json",
    });
}

const timeout = (ms) => {
    return new Promise(resolve => setTimeout(resolve, ms));
}

$(function () {
    const tableListOpt = replaceObject({
        lengthMenu: [ [100, 250, 500, 1000, -1], ["100", "250", "500", "1,000", "All"] ],
        columns: [
            {data:"nis", name:"nis", searchable:true, orderable:true},
            {data:"nama", name:"nama", searchable:true, orderable:true},
            // {data:"status", name:"status", searchable:false, orderable:false},
            // {data:"aksi", name:"aksi", searchable:false, orderable:false},
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
            $(row).find('td:eq(0)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            $(row).find('td:eq(1)').addClass("align-middle text-secondary text-xs font-weight-bold");
            // $(row).find('td:eq(2)').addClass("align-middle text-center text-secondary text-xs font-weight-bold");
            // $(row).find('td:eq(3)').addClass("align-middle text-end");
        }
    }, dataTableOption);
    tableList = $("#table-list").DataTable(tableListOpt);

    $("#search").keyup(function (e) {
        reloadTable();
    });

    $(document).on("change", "#berkas", function (e) {
        e.preventDefault();
        let file = $(this)[0].files[0];
        $(this).parent().find("span").text(file.name);
    });

    $(document).on("submit", "#form-unggah-siswa", function (e) {
        e.preventDefault();

        if (isFormUnggahSiswa != "") {
            isFormUnggahSiswa.abort();
        }

        const form = $(this);

        const formSerialize = form.serializeArray();

        const file = form.find("input[type=file]")[0].files;

        let formData = new FormData();

        formData.append("berkas", file[0]);
        $.each(formSerialize,function(index, input){
            formData.append(input.name, input.value);
        });

        const formAction = form.attr("action");

        let btnSubmit = $("#btn-unggah-siswa");

        const btnSubmitText = btnSubmit.html();

        isFormUnggahSiswa = $.ajax({
            type: "POST",
            dataType: "json",
            url: formAction,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                clearFileStudents();
                $(".form-error-message").text("");
                btnSubmit.prop("disabled", true);
                btnSubmit.html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                `);
            },
            success: function(res) {
                isFormUnggahSiswa = "";
                students = res.data.students;

                $("#upload-file").modal("toggle");
                btnSubmit.prop("disabled", false);
                btnSubmit.html(btnSubmitText);

                isExist = res.data.is_exist

                if (isExist == true) {
                    alertConfirmDeleteExistingData();
                    return false;
                }
                showProcessStoringData();
            },
            error: function(xhr) {
                isFormUnggahSiswa = "";
                btnSubmit.prop("disabled", false);
                btnSubmit.html(btnSubmitText);
                res = xhr.responseJSON;

                if(xhr.status == 422 || res.data != undefined) {
                    errors = res.data;
                    showErrorValidation(form, errors)
                }
            }
        });
    });

    $("#upload-file").on('hidden.bs.modal', function (event) {
        clearFileStudents();
        $(".form-error-message").text("");
    });

    $("#process-storing").on('hidden.bs.modal', function (event) {
        students = [];
        const modal = $(this);
        let progressTypeOther = modal.find(".progress-type-other");
        let progressTypeProcess = modal.find(".progress-type-process");
        let progressMessage = progressTypeOther.find(".progress-label").find(".progress-message");
        let progress = progressTypeProcess.find(".progress").find("div");
        let totalData = progressTypeProcess.find(".progress-label").find(".progress-total");
        let processedData = progressTypeProcess.find(".progress-label").find(".progress-current");
        progressMessage.text("");
        progress.css("width", "0%");
        totalData.text(0);
        processedData.text(0);

        progressTypeOther.hide();
        progressTypeProcess.show();
        setTimeout(() => {
            reloadTable();
            callout("Sukses", "Data siswa berhasil ditambah.", {type: "success"});
        }, 700);
    });
});
