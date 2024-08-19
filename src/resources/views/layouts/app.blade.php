<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <title>{{ env('APP_NAME') }}</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Pligins -->
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate.css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bs-callout/bs-callout.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-alert.css/bootstrap-alert.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate-loader/animate-loader.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css') }}" rel="stylesheet" />
    @stack('styles')
</head>
<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main -->
    {{ $slot }}

    <!-- Modal -->
    @stack('modals')

    <script type="text/javascript">
        const dttbl = {
            "language": `{{ asset('assets/plugins/datatables/language/id.json') }}`,
        }
    </script>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!--   Core JS Files   -->
    <script src="{{ asset('/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/fontawesome/js/all.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js') }}"></script>
    <!-- App -->
    <script src="{{ asset('assets/js/const.js') }}"></script>
    <script src="{{ asset('assets/js/func.js') }}"></script>
    <script src="{{ asset('assets/js/notify.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script type="text/javascript">
        var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        $(function () {
            @if (!empty($notifSuccess))
                callout("Sukses", "{{ $notifSuccess }}", {type: "success"});
            @endif

            @if (!empty($notifDanger))
                callout("Gagal", "{{ $notifDanger }}", {type: "danger"});
            @endif
        });
    </script>
    <!-- Pages -->
    @stack('scripts')
</body>
</html>
