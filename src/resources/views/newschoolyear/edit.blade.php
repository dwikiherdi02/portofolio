<x-app-layout>
    <x-main :title="__('Ubah Tahun Ajaran')" :isPrevious="true">
        <div class="row mt-4">
            <div class="col-lg-7 mt-lg-0 order-lg-1 order-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Formulir Tahun Ajaran</h6>
                    </div>
                    <div class="card-body py-0">
                        <x-form-ajax :method="__('PUT')"
                            action="{{ route('tahun_ajaran.proses_ubah', $tahunAjaran->id) }}"
                            id="form-ubah-tahun-ajaran"
                            data-submit-button="#btn-ubah-tahun-ajaran" data-callback="">
                            <div class="mb-3">
                                <label for="tahun" class="form-label text-uppercase text-secondary">Tahun</label>
                                <input type="text" name="tahun" id="tahun" class="form-control" value="{{ $tahunAjaran->tahun }}" placeholder="Masukan tahun ajaran">
                                <x-form-input-error :forname="__('tahun')"></x-form-input-error>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label text-uppercase text-secondary">Keterangan</label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ $tahunAjaran->keterangan }}" placeholder="Masukan keterangan">
                                <x-form-input-error :forname="__('keterangan')"></x-form-input-error>
                            </div>
                        </x-form-ajax>
                    </div>
                    <div class="card-footer pt-4 pb-0 text-end">
                        <button type="submit" form="form-ubah-tahun-ajaran" id="btn-ubah-tahun-ajaran" class="btn btn-info bg-gradient-info">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </x-main>
</x-app-layout>
