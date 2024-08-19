<div class="col-12 mb-4 pe-0">
    <div class="card">
        <div class="card-header pb-0">
            <h6>
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#criteria-students"
                    aria-expanded="true" aria-controls="criteria-students">
                    Rating Kriteria Siswa
                </button>
            </h6>
        </div>
        <div id="criteria-students" class="card-body p-0 accordion-collapse collapse show" data-bs-parent="#normalisasi">
            <div class="card shadow-none">
                <table id="table-calc-criteria-students" class="table align-items-center mb-0 px-3">
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
        <div class="card-footer py-2"></div>
    </div>
</div>