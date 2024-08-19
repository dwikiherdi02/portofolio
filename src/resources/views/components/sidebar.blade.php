@php
    $routeName = Route::currentRouteName();
    $name = explode('.', $routeName);
    $dasborActive = $kriteriaActive = $tahunAjaranActive = $biayaSPPActive = $dataSiswaActive = $hitungBiayaActive = $hasilBiayaActive = '';
    switch ($name[0]) {
        case 'dasbor':
            $dasborActive = 'active';
            break;
        case 'kriteria':
            $kriteriaActive = 'active';
            break;
        case 'tahun_ajaran':
            $tahunAjaranActive = 'active';
            break;
        case 'biaya_spp':
            $biayaSPPActive = 'active';
            break;
        case 'data_siswa':
            $dataSiswaActive = 'active';
            break;
        case 'hitung_biaya_spp':
            $hitungBiayaActive = 'active';
            break;
        case 'hasil_biaya_spp':
            $hasilBiayaActive = 'active';
            break;
        default:
            $dasborActive = 'active';
            break;
    }
@endphp
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('dasbor') }}">
            <img src="{{ asset('assets/img/logo.png') }}" class="navbar-brand-img h-100" alt="logo">
            <span class="ms-1 font-weight-bold">{{ __('Aplikasi SPK Biaya SPP') }}</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ $dasborActive }}" href="{{ route('dasbor') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-desktop"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dasbor</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $kriteriaActive }}" href="{{ route('kriteria') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <span class="nav-link-text ms-1">Kriteria</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tahunAjaranActive }}" href="{{ route('tahun_ajaran') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tahun Ajaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $biayaSPPActive }}" href="{{ route('biaya_spp') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-money-bill-1-wave"></i>
                    </div>
                    <span class="nav-link-text ms-1">Biaya SPP</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $dataSiswaActive }}" href="{{ route('data_siswa') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Siswa</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Proses Penentuan</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $hitungBiayaActive }}" href="{{ route('hitung_biaya_spp') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <span class="nav-link-text ms-1">Hitung Biaya SPP</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $hasilBiayaActive }}" href="{{ route('hasil_biaya_spp') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-square-poll-horizontal"></i>
                    </div>
                    <span class="nav-link-text ms-1">Hasil Biaya SPP</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
