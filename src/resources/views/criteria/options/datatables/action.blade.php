<a href="{{ route('kriteria.opsi.ubah', [$kriteria->id, $nilaiKriteria->id]) }}" class="btn btn-link btn-sm text-secondary text-xs p-0 m-0">
    <div
        class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-warning text-center me-2 d-flex align-items-center justify-content-center">
        <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
    </div>
</a>
<button data-href="{{ route('kriteria.opsi.hapus', [$kriteria->id, $nilaiKriteria->id]) }}" class="btn btn-link btn-sm text-secondary text-xs p-0 m-0" onclick="deleteData(this, 'reloadTable()')">
    <div
        class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-danger text-center me-2 d-flex align-items-center justify-content-center">
        <i class="fa-solid fa-trash-can" style="color: #ffffff;"></i>
    </div>
</button>
