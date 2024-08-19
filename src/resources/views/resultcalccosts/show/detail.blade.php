<div class="row ps-1">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center pb-0">
                <div>
                    <h6>Kriteria Setiap Siswa</h6>
                </div>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="me-2">
                        <input id="search-detail" class="form-control form-control-sm" type="text" placeholder="Cari..." aria-label="Cari" autocomplete="off">
                    </div>
                </div>
            </div>
            <table id="table-detail" class="table align-items-center mb-0 px-3">
                <thead>
                    <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        NISN</th>
                    <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        Nama Siswa</th>
                    @foreach ($kriteria as $item)
                    <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        {{ $item->nama }}</th>
                    @endforeach
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>