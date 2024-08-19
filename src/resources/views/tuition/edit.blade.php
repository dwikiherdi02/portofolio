<x-app-layout>
    <x-main :title="__('Entri Data - '.$tahunAjaran->tahun)" :isPrevious="true">
        <div class="row mt-4">
            <div class="col-lg-7 mt-lg-0 order-lg-1 order-2">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center pb-0">
                        <div>
                            <h6>Entri Data</h6>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between">
                            <div>
                                <button id="btn-tambah" class="btn btn-success btn-md bg-gradient-success my-0" title="Tambah Entri Data"><i class="fa-solid fa-file-circle-plus fa-xl" style="color: #ffffff;"></i></button>
                                <a href="{{ route('biaya_spp.data_entri.unduh_pdf', $tahunAjaran->id) }}" class="btn btn-dark btn-md bg-gradient-dark my-0" title="Tambah Entri Data" target="_blank"><i class="fa-solid fa-file-download fa-xl" style="color: #ffffff;"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <x-form-ajax :method="__('POST')" action="{{ route('biaya_spp.proses_data_entri', $tahunAjaran->id) }}" id="form-data-entry" data-submit-button="#btn-data-entry" data-callback="callbackValidation(form, errors)">
                            @php
                                $count = 1;
                            @endphp
                            @if ($biayaSPP->count() > 0)
                                @foreach ($biayaSPP as $key => $item)
                                    <div id="row-{{ $count }}" class="row mt-3">
                                        <div class="col-lg-5 order-lg-1 order-2">
                                            <label for="nilai_{{ $count }}" class="form-label text-uppercase text-secondary">Nilai</label>
                                            <input type="number" name="biaya[{{ $count }}][nilai]" id="nilai_{{ $count }}" class="form-control" value="{{ $item->nilai }}" placeholder="Masukan nilai biaya">
                                            <x-form-input-error :forname="__('biaya.'.$count.'.nilai')"></x-form-input-error>
                                        </div>
                                        <div class="col-lg-3 order-lg-2 order-3">
                                            <label for="bobot_minimal_{{ $count }}" class="form-label text-uppercase text-secondary">Bobot Min</label>
                                            <input type="number" name="biaya[{{ $count }}][bobot_minimal]" id="bobot_minimal_{{ $count }}" class="form-control" value="{{ $item->bobot_minimal }}" placeholder="Masukan bobot min">
                                            <x-form-input-error :forname="__('biaya.'.$count.'.bobot_minimal')"></x-form-input-error>
                                        </div>
                                        <div class="col-lg-3 order-lg-3 order-4">
                                            <label for="bobot_maksimal_{{ $count }}" class="form-label text-uppercase text-secondary">Bobot Maks</label>
                                            <input type="number" name="biaya[{{ $count }}][bobot_maksimal]" id="bobot_maksimal_{{ $count }}" class="form-control" value="{{ $item->bobot_maksimal }}" placeholder="Masukan bobot maks">
                                            <x-form-input-error :forname="__('biaya.'.$count.'.bobot_maksimal')"></x-form-input-error>
                                        </div>
                                        <div class="col-lg-1 order-lg-4 order-1 pb-lg-0 pb-3 align-self-center">
                                            @if ($key > 0)
                                            <a class="btn btn-link btn-md btn-hapus my-0 mt-4 p-0" title="Hapus Entri Data" data-row="{{ $count }}">
                                                <i class="fa-solid fa-trash-can fa-xl text-danger"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                        $count++;
                                    @endphp
                                @endforeach
                            @else
                                <div id="row-1" class="row mt-3">
                                    <div class="col-lg-5 order-lg-1 order-2">
                                        <label for="nilai_1" class="form-label text-uppercase text-secondary">Nilai</label>
                                        <input type="number" name="biaya[1][nilai]" id="nilai_1" class="form-control" placeholder="Masukan nilai biaya">
                                        <x-form-input-error :forname="__('biaya.1.nilai')"></x-form-input-error>
                                    </div>
                                    <div class="col-lg-3 order-lg-2 order-3">
                                        <label for="bobot_minimal_1" class="form-label text-uppercase text-secondary">Bobot Min</label>
                                        <input type="number" name="biaya[1][bobot_minimal]" id="bobot_minimal_1" class="form-control" placeholder="Masukan bobot min">
                                        <x-form-input-error :forname="__('biaya.1.bobot_minimal')"></x-form-input-error>
                                    </div>
                                    <div class="col-lg-3 order-lg-3 order-4">
                                        <label for="bobot_maksimal_1" class="form-label text-uppercase text-secondary">Bobot Maks</label>
                                        <input type="number" name="biaya[1][bobot_maksimal]" id="bobot_maksimal_1" class="form-control" placeholder="Masukan bobot maks">
                                        <x-form-input-error :forname="__('biaya.1.bobot_maksimal')"></x-form-input-error>
                                    </div>
                                    <div class="col-lg-1 order-lg-4 order-1 pb-lg-0 pb-3 align-self-center"></div>
                                </div>
                            @endif
                        </x-form-ajax>
                    </div>
                    <div class="card-footer pt-4 pb-0 text-end">
                        <button type="submit" form="form-data-entry" id="btn-data-entry" class="btn btn-info bg-gradient-info">Simpan</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 order-lg-2 order-1">
                <div id="keterangan-ops" class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ket-ops-bobot" aria-expanded="true" aria-controls="ket-ops-bobot">
                                        Keterangan Entri data
                                    </button>
                                </h6>
                            </div>
                            <div id="ket-ops-bobot" class="card-body py-0 accordion-collapse collapse show"
                                data-bs-parent="#keterangan-ops">
                                <ol class="list-group list-group-flush list-group-numbered">
                                    <li class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                        Jika min konsong dan maks diisi maka <b class="font-weight-bolder">nilai =< maks.</b>
                                    </li>
                                    <li class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                        Jika min dan maks diisi maka <b class="font-weight-bolder">min < nilai =< maks.</b>
                                    </li>
                                    <li class="list-group-item text-secondary text-xs font-weight-bold px-0 py-3">
                                        Jika min diisi dan maks kosong maka <b class="font-weight-bolder">hasil > min.</b>
                                    </li>
                                </ol>
                            </div>
                            <div class="card-footer py-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-main>
    @push('scripts')
    <script>
        let countRow = {{ ($count - 1 < 1) ? $count : $count - 1 }};
    </script>
    <script src="{{ asset('assets/js/pages/biaya/detail.js') }}"></script>
    @endpush
</x-app-layout>
