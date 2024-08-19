<div class="row ps-1">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center pb-0">
                <div>
                    <h6>Biaya SPP Bedasarkan Hasil Preferensi</h6>
                </div>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="me-2">
                        <input id="search-result" class="form-control form-control-sm" type="text" placeholder="Cari..."
                            aria-label="Cari" autocomplete="off">
                    </div>
                    <div>
                        <a href="{{ route('hasil_biaya_spp.lihat.unduh_pdf', $tahunAjaran->id) }}"
                            class="btn btn-sm btn-dark btn-md bg-gradient-dark my-0" title="Unduh Data" target="_blank">Unduh</a>
                    </div>
                </div>
            </div>
            <table id="table-result" class="table align-items-center mb-0 px-3">
                <thead>
                    <th
                        class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        NISN</th>
                    <th
                        class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        Nama Siswa</th>
                    <th
                        class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        Total Preferensi</th>
                    <th
                        class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                        Biaya</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>