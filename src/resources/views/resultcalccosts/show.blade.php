<x-app-layout>
    <x-main :title="__('Hasil Perhitungan Tahun '.$tahunAjaran->tahun)" :isPrevious="true">
        <div class="row">
            <div class="col-12">
                <div class="nav-wrapper position-relative end-0">
                    <ul id="show-tab" class="nav nav-pills nav-fill nav-pills-dark p-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#v-detail" href="javascript:;" role="tab"
                                aria-controls="detail" aria-selected="true">
                                Detail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#v-calculation" href="javascript:;"
                                role="tab" aria-controls="calculation" aria-selected="false">
                                Normalisasi & Perhitungan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#v-result" href="javascript:;" role="tab"
                                aria-controls="result" aria-selected="false">
                                Biaya SPP Siswa
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-detail" role="tabpanel" aria-labelledby="v-detail-tab">
                        @include('resultcalccosts.show.detail')
                    </div>
                    <div class="tab-pane fade" id="v-calculation" role="tabpanel"
                        aria-labelledby="v-calculation-tab">
                        @include('resultcalccosts.show.calc')
                    </div>
                    <div class="tab-pane fade" id="v-result" role="tabpanel" aria-labelledby="v-result-tab">
                        @include('resultcalccosts.show.result')
                    </div>
                </div>
            </div>
        </div>
    </x-main>
    <x-datatables />
    @push('scripts')
    <script>
        const criteria = @json($kriteriaJson);
        const url = {
            datatables: {
                detail: `{{ route('hasil_biaya_spp.lihat.datatabel.detail', $tahunAjaran->id) }}`,
                calc: {
                    criteria: {
                        student: `{{ route('hasil_biaya_spp.lihat.datatabel.calc.students', $tahunAjaran->id) }}`,
                        weight: `{{ route('hasil_biaya_spp.lihat.datatabel.calc.weight', $tahunAjaran->id) }}`,
                        value: `{{ route('hasil_biaya_spp.lihat.datatabel.calc.value', $tahunAjaran->id) }}`,
                        result: `{{ route('hasil_biaya_spp.lihat.datatabel.calc.result', $tahunAjaran->id) }}`
                    },
                    preference: {
                        weight: `{{ route('hasil_biaya_spp.lihat.datatabel.calc.preference.weight', $tahunAjaran->id) }}`,
                        result: `{{ route('hasil_biaya_spp.lihat.datatabel.calc.preference.result', $tahunAjaran->id) }}`
                    }
                },
                result: `{{ route('hasil_biaya_spp.lihat.datatabel.result', $tahunAjaran->id) }}`
            },
        };
    </script>
    <script src="{{ asset('assets/js/pages/proses-penentuan/hasil.detail.js') }}"></script>
    @endpush
</x-app-layout>