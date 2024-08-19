let isFormProsesHitung = "";

const clearForm = (form) => {
    const file = $("#berkas");
    file.val("");
    file.parent().find("span").text(defaultFileText);
    // form[0].reset();
}

const clearXhr = () => {
    isFormProsesHitung = "";
}

const alertConfirmDeleteExistingData = () => {
    return new Promise((resolve) => {
        let swalCustom = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-link text-dark"
            },
            buttonsStyling: false
        });
        swalCustom.fire({
            text: "Unggah berkas siswa akan menghapus perhitungan dan hasil sebelumnya. Apakah kamu yakin?",
            icon: "warning",
            showClass: { popup: ` animate__animated animate__pulse animate__faster ` },
            hideClass: { popup: ``},
            showCancelButton: true,
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                resolve(true);
            } else {
                resolve(false);
            }
        });
    })
}

const modalProgress = (status, label) => {
    status = (status != "") ? status : "show";
    label = (label != "") ? label : "Proses..";

    const modal = $("#process-storing");
    let elLabel = modal.find(".modal-body").find(".progress-label");

    elLabel.text("");
    modal.modal(status);
    if(status == "show") {
        elLabel.text(label);
    }
}

const checkProcess = (form) => {
    if (isFormProsesHitung != "") {
        isFormProsesHitung.abort();
    }

    let formData = new FormData();

    const formSerialize = form.serializeArray();

    const file = form.find("input[type=file]")[0].files;

    formData.append("berkas", file[0]);
    $.each(formSerialize,function(index, input){
        formData.append(input.name, input.value);
    });

    isFormProsesHitung = $.ajax({
        type: "POST",
        dataType: "json",
        url: url.process.check,
        data: formData,
        contentType: false,
        processData: false
    });

    return isFormProsesHitung;
}

const importProcess = (form) => {
    if (isFormProsesHitung != "") {
        isFormProsesHitung.abort();
    }

    let formData = new FormData();

    const formSerialize = form.serializeArray();

    const file = form.find("input[type=file]")[0].files;

    formData.append("berkas", file[0]);
    $.each(formSerialize,function(index, input){
        formData.append(input.name, input.value);
    });

    isFormProsesHitung = $.ajax({
        type: "POST",
        dataType: "json",
        url: url.process.import,
        data: formData,
        contentType: false,
        processData: false
    });

    return isFormProsesHitung;
}

const deleteProcess = (form) => {
    if (isFormProsesHitung != "") {
        isFormProsesHitung.abort();
    }

    let formData = new FormData();

    const formSerialize = form.serializeArray();

    $.each(formSerialize,function(index, input){
        let value = (input.name == "_method") ? "delete" : input.value;
        formData.append(input.name, value);
    });

    isFormProsesHitung = $.ajax({
        type: "POST",
        dataType: "json",
        url: url.process.delete,
        data: formData,
        contentType: false,
        processData: false
    });

    return isFormProsesHitung;
}

const calculateProcess = (form) => {
    if (isFormProsesHitung != "") {
        isFormProsesHitung.abort();
    }

    let formData = new FormData();

    const formSerialize = form.serializeArray();

    $.each(formSerialize,function(index, input){
        let value = input.value;
        formData.append(input.name, value);
    });

    isFormProsesHitung = $.ajax({
        type: "POST",
        dataType: "json",
        url: url.process.calculate,
        data: formData,
        contentType: false,
        processData: false
    });

    return isFormProsesHitung;
}

$(function () {
    $(document).on("change", "#new-school-year", function (e) {
        e.preventDefault();
        $("#text-new-school-year").val("");
        $("#value-new-school-year").val("");
        if($(this).val() != "") {
            $("#text-new-school-year").val($(this).find(":selected").text());
            $("#value-new-school-year").val($(this).val());
        }
    });

    $(document).on("click", "#btn-download-file", function (e) {
        e.preventDefault();
        let url = new URL($(this).attr("href"));
        let newSchoolYear = $("#new-school-year").val();
        if (newSchoolYear != "") {
            url.searchParams.set("syid", newSchoolYear);
        }
        window.open(url, "_blank");
    });

    $(document).on("change", "#berkas", function (e) {
        e.preventDefault();
        let file = $(this)[0].files[0];
        $(this).parent().find("span").text(file.name);
    });

    $(document).on("submit", "#form-proses-hitung", async function (e) {
        e.preventDefault();

        let btnSubmit = $("#btn-proses");
        const btnSubmitText = btnSubmit.html();
        const form = $(this);

        $(".form-error-message").text("");
        btnSubmit.prop("disabled", true);
        btnSubmit.html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
        `);

        try {
            const resCheck = await checkProcess(form);
            clearXhr();

            if (resCheck.data.is_delete_existing == true) {
                const statConfirm = await alertConfirmDeleteExistingData();
                if (statConfirm == false) {
                    clearForm(form);
                    btnSubmit.prop("disabled", false);
                    btnSubmit.html(btnSubmitText);
                    return false;
                }

                modalProgress("show", "Menghapus data...");
                await deleteProcess(form);
                clearXhr();
            }

            modalProgress("show", "Mengimpor data...");
            const resImport = await importProcess(form);
            clearXhr();
            console.log(resImport);

            modalProgress("show", "Menghitung data...");
            const resCalculate = await calculateProcess(form);
            clearXhr();

            modalProgress("hide");
            if (resCalculate.data.redirect_to) {
                window.location.replace(resCalculate.data.redirect_to);
            }
        } catch (xhr) {
            clearForm(form);
            modalProgress("hide");

            btnSubmit.prop("disabled", false);
            btnSubmit.html(btnSubmitText);

            res = xhr.responseJSON;
            if(xhr.status == 422 || res.data != undefined) {
                errors = res.data;
                showErrorValidation(form, errors)
            } else {
                callout("Gagal", res.message, {type: "danger"});
            }
        }

    });
});