<x-app-layout>
    <x-main :title="__('Data Entri Siswa - '. $tahunAjaran->tahun)" :isPrevious="true">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center pb-3">
                        <div></div>
                        <div class="d-flex flex-wrap justify-content-between">
                            <a href="{{ route('data_siswa.unduh_template') }}" target="_blank" class="btn btn-dark btn-sm bg-gradient-dark mb-0 me-2">Unduh Template</a>
                            <button
                                class="btn btn-success btn-sm bg-gradient-success mb-0" data-bs-toggle="modal" data-bs-target="#upload-file">Unggah</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center pb-0">
                        <div>
                            <h6>Daftar Siswa</h6>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="me-2">
                                <input id="search" class="form-control form-control-sm" type="text"
                                placeholder="Cari..." aria-label="Cari" autocomplete="off">
                            </div>
                            <div>
                                <a href="{{ route('data_siswa.data_entri.unduh_pdf', $tahunAjaran->id) }}" class="btn btn-sm btn-dark btn-md bg-gradient-dark my-0" title="Tambah Entri Data" target="_blank">Unduh</a>
                            </div>
                        </div>
                    </div>
                    <table id="table-list" class="table align-items-center mb-0 px-3">
                        <thead>
                            <th
                                class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                NISN</th>
                            <th
                                class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                Nama</th>
                            {{-- <th
                                class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                Status</th>
                            <th class="align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                            </th> --}}
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        @push('modals')
        <div
        class="modal" id="upload-file" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="upload-file-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="upload-file-label">Unggah Berkas Siswa</h6>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('data_siswa.data_entri.proses_ambil_data', $tahunAjaran->id) }}" id="form-unggah-siswa">
                            @method('POST')
                            @csrf
                            <div class="mb-3">
                                <label for="berkas" class="form-label text-uppercase text-secondary">Berkas</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end bg-light">Pilih Berkas</span>
                                    <label tabindex="0" class="form-control m-0 ps-2">
                                        <span>
                                            Masukan template yang sudah di unduh (.xls, .xlsx)
                                        </span>
                                        <input type="file" name="berkas" id="berkas" class="d-none">
                                    </label>
                                </div>
                                <x-form-input-error :forname="__('berkas')"></x-form-input-error>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0">
                        <button
                        type="button" class="btn btn-link text-dark my-0" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" form="form-unggah-siswa" id="btn-unggah-siswa" class="btn btn-info bg-gradient-info my-0">Proses</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal animate__animated animate__pulse animate__faster" id="process-storing" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="upload-file-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="progress-type-other">
                            <label class="progress-label">
                                <span class="progress-message"></span>
                            </label>
                        </div>
                        <div class="progress-type-process">
                            <label for="progress" class="progress-label">
                                <span class="progress-current">0</span> / <span class="progress-total">0</span>
                            </label>
                            <div class="progress">
                                <div
                                class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endpush
    </x-main>
    <x-datatables />
    @push('scripts')
    <script>
        const defaultFileText = "Masukan template yang sudah di unduh (.xls, .xlsx)";
        const url = {
            "datatables": `{{ route('data_siswa.data_entri.datatabel', $tahunAjaran->id) }}`,
            "deletingProcess": `{{ route('data_siswa.data_entri.proses_hapus_data', $tahunAjaran->id) }}`,
            "storingProcess": `{{ route('data_siswa.data_entri.proses_simpan_data', $tahunAjaran->id) }}`,
        };
    </script>
    <script src="{{ asset('assets/js/pages/siswa/show.js') }}"></script>
    @endpush
</x-app-layout>
