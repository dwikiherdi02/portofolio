<x-app-layout>
    <x-main :title="__('Tambah Opsi Kriteria - '.$kriteria->nama)" :isPrevious="true">
        <div class="row mt-4">
            <div class="col-lg-7 mt-lg-0 order-lg-1 order-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Formulir Opsi Kriteria</h6>
                    </div>
                    <div class="card-body py-0">
                        <x-form-ajax :method="__('POST')"
                            action="{{ route('kriteria.opsi.proses_tambah', $kriteria->id) }}"
                            id="form-tambah-kriteria"
                            data-submit-button="#btn-tambah-kriteria" data-callback="">
                            <div class="mb-3">
                                <label for="kode" class="form-label text-uppercase text-secondary">Kode</label>
                                <input type="text" name="kode" id="kode" class="form-control" placeholder="Otomatis" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label text-uppercase text-secondary">Keterangan (Nama Opsi)</label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukan nama opsi">
                                <x-form-input-error :forname="__('keterangan')"></x-form-input-error>
                            </div>
                            <div class="mb-3">
                                <label for="bobot" class="form-label text-uppercase text-secondary">Bobot</label>
                                <select name="bobot" id="bobot" class="form-select" aria-label="Pilih nilai bobot">
                                    <option value="">Pilih nilai bobot</option>
                                    @foreach ($bobot as $item)
                                    <option value="{{ $item->nilai }}">{{ $item->nilai }}</option>
                                    @endforeach
                                </select>
                                <x-form-input-error :forname="__('bobot')"></x-form-input-error>
                            </div>
                        </x-form-ajax>
                    </div>
                    <div class="card-footer pt-4 pb-0 text-end">
                        <button type="submit" form="form-tambah-kriteria" id="btn-tambah-kriteria" class="btn btn-info bg-gradient-info">Simpan</button>
                        {{-- <a href="{{ url()->previous() }}" class="btn btn-link text-dark">Kembali</a> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-5 order-lg-2 order-1">
                <div id="info-keterangan" class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ket-nilai-bobot"
                                        aria-expanded="true" aria-controls="ket-nilai-bobot">
                                        Keterangan Nilai Bobot
                                    </button>
                                </h6>
                            </div>
                            <div id="ket-nilai-bobot" class="card-body py-0 accordion-collapse collapse show" data-bs-parent="#info-keterangan">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-uppercase text-secondary text-xs font-weight-bolder px-0">Nilai</th>
                                            <th class="align-middle text-uppercase text-secondary text-xs font-weight-bolder px-0">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bobot as $item)
                                        <tr>
                                            <td class="align-middle text-secondary text-xs font-weight-bold">{{ $item->nilai }}</td>
                                            <td class="align-middle text-secondary text-xs font-weight-bold px-0">{{ $item->nama }} ({{ $item->kode }})</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer py-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-main>
</x-app-layout>
