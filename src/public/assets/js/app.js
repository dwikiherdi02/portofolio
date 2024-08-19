let isFormAjax = "";
$(function () {
    $('.form-ajax').submit(function(event) {
        event.preventDefault();

        if (isFormAjax != "") {
            isFormAjax.abort();
        }

        const form = $(this)

        let formAction = form.attr("action");

        let dataCallback = form.data("callback");

        let dataSubmitBtn = form.data("submit-button");

        let btnSubmit = form.find("button[type=submit]");
        if (dataSubmitBtn != undefined && dataSubmitBtn  != "") {
            btnSubmit = $(dataSubmitBtn);
        }

        let btnSubmitText = btnSubmit.html();

        isFormAjax = $.ajax({
            type: "POST",
            dataType: "json",
            url: formAction,
            data: form.serialize(),
            beforeSend: function() {
                $(".form-error-message").text("");
                btnSubmit.prop("disabled", true);
                btnSubmit.html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                `);
            },
            success: function() {
                isFormAjax = "";
                form.unbind('submit').submit(); // continue the submit unbind preventDefault
            },
            error: function(xhr) {
                isFormAjax = "";
                btnSubmit.prop("disabled", false);
                btnSubmit.html(btnSubmitText);
                res = xhr.responseJSON;

                // if(xhr.status == 0) { showAlert(2, true); return; }

                if(xhr.status == 422 || res.data != undefined) {
                    errors = res.data;
                    if(dataCallback != undefined && dataCallback != "") {
                        eval(dataCallback)
                    } else {
                        showErrorValidation(form, errors)
                    }
                }
            }
        });
    });
});
