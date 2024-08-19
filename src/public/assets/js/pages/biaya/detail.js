const templateFormBiaya = `
    <div id="row-{{COUNTROW}}" class="row mt-3">
        <div class="col-lg-5 order-lg-1 order-2">
            <label for="nilai_{{COUNTROW}}" class="form-label text-uppercase text-secondary">Nilai</label>
            <input type="number" name="biaya[{{COUNTROW}}][nilai]" id="nilai_{{COUNTROW}}" class="form-control" placeholder="Masukan nilai biaya">
            <div class="form-error-message form-text text-xs text-danger font-weight-bold" for-name="biaya.{{COUNTROW}}.nilai"></div>
        </div>
        <div class="col-lg-3 order-lg-2 order-3">
            <label for="bobot_minimal_{{COUNTROW}}" class="form-label text-uppercase text-secondary">Bobot Min</label>
            <input type="number" name="biaya[{{COUNTROW}}][bobot_minimal]" id="bobot_minimal_{{COUNTROW}}" class="form-control"
                placeholder="Masukan bobot min">
            <div class="form-error-message form-text text-xs text-danger font-weight-bold" for-name="biaya.{{COUNTROW}}.bobot_minimal"></div>
        </div>
        <div class="col-lg-3 order-lg-3 order-4">
            <label for="bobot_maksimal_{{COUNTROW}}" class="form-label text-uppercase text-secondary">Bobot Maks</label>
            <input type="number" name="biaya[{{COUNTROW}}][bobot_maksimal]" id="bobot_maksimal_{{COUNTROW}}" class="form-control"
                placeholder="Masukan bobot maks">
            <div class="form-error-message form-text text-xs text-danger font-weight-bold" for-name="biaya.{{COUNTROW}}.bobot_maksimal"></div>
        </div>
        <div class="col-lg-1 order-lg-4 order-1 pb-lg-0 pb-3 align-self-center">
            <a class="btn btn-link btn-md btn-hapus my-0 mt-4 p-0" title="Hapus Entri Data" data-row="{{COUNTROW}}"><i class="fa-solid fa-trash-can fa-xl text-danger"></i></a>
        </div>
    </div>
`;

const callbackValidation = (form, errors) => {
    form.find(".form-error-message").each(function (_, element) {
        const el = $(element);
        const _for = el.attr("for-name");
        var formName = _for.split(".")[2].replace("_", " ");
        if(errors[_for]) {
            var msg = errors[_for].replace(_for.replace("_", " "), formName);
            el.text(msg);
        }
    });
}

$(function () {
    $(document).on("click", "#btn-tambah", function (e) {
        e.preventDefault();
        countRow++;
        let row = templateFormBiaya;
        row = row.replaceAll("{{COUNTROW}}", countRow);
        $("#form-data-entry").append(row);
    });

    $(document).on("click", ".btn-hapus", function (e) {
        e.preventDefault();
        let rowData = $(this).data("row");
        let rowID = `#row-${rowData}`;
        $(rowID).remove();
    });
});
