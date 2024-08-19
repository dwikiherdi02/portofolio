<x-app-layout>
    <x-main :title="__('Tahun Ajaran')">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center pb-0">
                        <div>
                            <h6>Daftar Tahun Ajaran</h6>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="me-2">
                                <input id="search" class="form-control form-control-sm" type="text" placeholder="Cari..." aria-label="Cari" autocomplete="off">
                            </div>
                            <div class="me-2">
                                <a href="{{ route('tahun_ajaran.tambah') }}" class="btn btn-success btn-sm bg-gradient-success">Tambah</a>
                            </div>
                                <a href="{{ route('tahun_ajaran.unduh_pdf') }}" class="btn btn-dark btn-sm bg-gradient-dark" target="_blank">Unduh</a>
                            <div>
                            </div>
                        </div>
                    </div>
                    <table id="table-list" class="table align-items-center mb-0 px-3">
                        <thead>
                            <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                Tahun</th>
                            <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                Keterangan</th>
                            <th class="align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7"></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-main>
    <x-datatables />
    @push('scripts')
    <script>
        let tableList;
        const url = {
            "datatables": `{{ route('tahun_ajaran.datatabel') }}`,
        };
    </script>
    <script src="{{ asset('assets/js/pages/tahun-ajaran/list.js') }}"></script>
    @endpush
</x-app-layout>
