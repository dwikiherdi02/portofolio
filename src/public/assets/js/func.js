const showErrorValidation = (form, errors) => {
    form.find(".form-error-message").each(function (_, element) {
        const el = $(element);
        const _for = el.attr("for-name");
        if(errors[_for]) {
            el.text(errors[_for].replace("id", ""));
        }
    });
}

const replaceObject = (obj, destObj) => {
    const objReplace = Object.keys(obj);
    for (let index = 0; index < objReplace.length; index++) {
        const key = objReplace[index];
        if (key == "icon") { continue; }
        destObj[key] = obj[key];
    }
    return destObj;
}

const deleteData = (el, callback) => {
    const url = $(el).data("href");
    let swalCustom = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-link text-dark"
        },
        buttonsStyling: false
    });
    swalCustom.fire({
        text: "Data yang sudah dihapus tidak bisa di kembalikan lagi. Apakah kamu yakin?",
        icon: "warning",
        showClass: { popup: ` animate__animated animate__pulse animate__faster ` },
        hideClass: { popup: ``},
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
            swalCustom = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-dark",
                },
                buttonsStyling: false
            });
            try {
                await processDelete(url)
                swalCustom.fire({
                    text: "Data berhasil dihapus.",
                    icon: "success",
                    showClass: { popup: ` animate__animated animate__pulse animate__faster ` },
                    hideClass: { popup: ``},
                    confirmButtonText: "Oke",
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(callback != undefined) {
                            eval(callback);
                        }
                    }
                });
            } catch (error) {
                swalCustom.fire({
                    text: "Data gagal dihapus, silahkan hubungi admin.",
                    icon: "error",
                    showClass: { popup: ` animate__animated animate__pulse animate__faster ` },
                    hideClass: { popup: ``},
                    confirmButtonText: "Oke",
                });
            }
        }
    });
}

const processDelete = (url) => {
    return $.ajax({
        type: "post",
        url: url,
        data: {"_method" : "delete"},
        dataType: "json"
    });
}
