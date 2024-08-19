@props(['title', 'isPrevious'])
@php
    $isPrevious = isset($isPrevious) ? $isPrevious : false;
    $title = isset($title) ? $title : '';
    $currentParams = Route::current()->parameters();
    $routeStr = Route::currentRouteName();
    $routeArr = $routeArrPop = explode('.', $routeStr);
    array_pop($routeArrPop);
    $breadcrumbs = [];
    $route = '';
    $previousLink = '';
    if ($routeArr[0] != 'dasbor') {
        foreach ($routeArr as $k => $v) {
            $breadcrumbName = str_replace('Spp', 'SPP', ucwords(str_replace('_', ' ', $v)));
            $dot = ($k > 0) ? '.' : '';
            $route .= "{$dot}{$v}";
            $link = '#';
            try { $link = route($route); }
            catch(Exception $e) { $link = route($route, $currentParams); }
            if (count($routeArr) == $k+2) {
                $previousLink = $link;
            }
            $breadcrumbs[] = [
                'name' => $breadcrumbName,
                'link' => $link
            ];
        }
    }
@endphp
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('dasbor') }}"><i class="fa fa-solid fa-desktop"></i></a></li>
                @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                    @if ($loop->last)
                        {{ $breadcrumb['name'] }}
                    @else
                        <a href="{{ $breadcrumb['link'] }}">
                            {{ $breadcrumb['name'] }}
                        </a>
                    @endif
                </li>
                @endforeach
            </ol>
            <h6 class="font-weight-bolder mb-0">{{ $title }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
            <ul class="navbar-nav  justify-content-end">
                @if ($isPrevious)
                <li class="nav-item d-flex align-items-center">
                    <a href="{{ $previousLink }}" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa-solid fa-chevron-left me-sm-1" aria-hidden="true"></i>
                        <span class="d-sm-inline d-none">Kembali</span>
                    </a>
                </li>
                @endif
                <li class="nav-item d-xl-none ps-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
