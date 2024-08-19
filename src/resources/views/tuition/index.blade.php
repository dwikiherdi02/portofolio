<x-app-layout>
    <x-main :title="__('Biaya SPP')">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center pb-0">
                        <div>
                            <h6>Daftar Biaya SPP (Tahunan)</h6>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="me-2">
                                <input id="search" class="form-control form-control-sm" type="text" placeholder="Cari..." aria-label="Cari" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <table id="table-list" class="table align-items-center mb-0 px-3">
                        <thead>
                            <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                Tahun</th>
                            <th class="text-uppercase align-middle text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                Status</th>
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
            "datatables": `{{ route('biaya_spp.datatabel') }}`,
        };
    </script>
    <script src="{{ asset('assets/js/pages/biaya/list.js') }}"></script>
    @endpush
</x-app-layout>
