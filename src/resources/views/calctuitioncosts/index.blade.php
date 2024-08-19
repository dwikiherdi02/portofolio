<x-app-layout>
    <x-main :title="__('Hitung Biaya SPP')">
        <div class="row">
            <div class="col-lg-7 mt-lg-0 order-lg-1 order-2">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 mb-3">
                                <select id="new-school-year" class="form-select">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($tahunAjaran as $item)
                                        <option value="{{ $item->id }}">{{ $item->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <a href="{{ route('hitung_biaya_spp.unduh_berkas') }}" target="_blank" class="btn btn-success bg-gradient-dark mb-0 w-100" id="btn-download-file">Unduh berkas</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body pb-0">
                        <form method="POST" id="form-proses-hitung">
                            @method('POST')
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <input type="text" id="text-new-school-year" class="form-control" placeholder="Tahun Ajaran" value="" disabled>
                                    <input type="hidden" name="id_tahun_ajaran" id="value-new-school-year" class="form-control" value="">
                                    <x-form-input-error :forname="__('id_tahun_ajaran')"></x-form-input-error>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text border-end bg-light">Pilih Berkas</span>
                                        <label tabindex="0" class="form-control m-0 ps-2">
                                            <span>
                                                Unggah berkas data siswa yang sudah diisi (.xls, .xlsx)
                                            </span>
                                            <input type="file" name="berkas" id="berkas" class="d-none">
                                        </label>
                                    </div>
                                    <x-form-input-error :forname="__('berkas')"></x-form-input-error>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer pt-0 pb-0 text-end">
                        <button type="submit" form="form-proses-hitung" id="btn-proses"
                            class="btn btn-info bg-gradient-info">Proses</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 order-lg-2 order-1">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Alur Proses Perhitungan</h6>
                    </div>
                    <div class="card-body pt-0">
                        <ol class="list-group list-group-flush list-group-numbered">
                            <li
                                class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                Pilih tahun yang akan anda dihitung biaya SPPnya
                            </li>
                            <li
                                class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                Lalu klik unduh untuk mendapatkan berkas excel berisi data siswa beserta kriteria - kriteria pengambil keputusan
                            </li>
                            <li
                                class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                Isi berkas dengan benar bedasarkan data siswa
                            </li>
                            <li class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                Unggah berkas yang sudah diisi, lalu klik proses untuk melanjukan tahap perhitungan
                            </li>
                        </ol>
                        <p class="text-danger text-xs font-weight-bold border-top mb-0 pt-3">
                            <b>CATATAN!!!</b> Jangan mengubah <b><i>Header</i></b> maupun format yang ada di <b><i>Sheet</i></b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @push('modals')
        <div class="modal animate__animated animate__pulse animate__faster" id="process-storing" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="upload-file-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="progress" class="progress-label"></label>
                        <div class="loader-line my-0 w-100"></div>
                    </div>
                </div>
            </div>
        </div>
        @endpush
    </x-main>
    @push('scripts')
    <script>
        const defaultFileText = "Unggah berkas data siswa yang sudah diisi (.xls, .xlsx)";
        const url = {
            process: {
                check: "{{ route('hitung_biaya_spp.proses_cek') }}",
                delete: "{{ route('hitung_biaya_spp.proses_hapus') }}",
                import: "{{ route('hitung_biaya_spp.proses_impor') }}",
                calculate: "{{ route('hitung_biaya_spp.proses_hitung') }}"
            }
        };
    </script>
    <script src="{{ asset('assets/js/pages/proses-penentuan/hitung.js') }}"></script>
    @endpush
</x-app-layout>
