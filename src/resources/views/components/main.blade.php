@props(['title', 'isPrevious'])
@php
    $isPrevious = isset($isPrevious) ? $isPrevious : false;
    $title = isset($title) ? $title : '';
@endphp
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <x-navbar :title="$title" :isPrevious="$isPrevious" />
    <div class="container-fluid py-4">
        {{ $slot }}
        <footer class="footer pt-3">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            <strong>Copyright &copy; 2024 <a href="https://www.dwikiherdiansyah.web.id" class="text-secondary"><span class="text-primary">DW</span> Project</a>.</strong> All rights reserved.
                        </div>
                    </div>
                    <div class="col-lg-6">
                    </div>
                </div>
            </div>
        </footer>
    </div>
</main>
