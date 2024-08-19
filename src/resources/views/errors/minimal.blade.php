<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <link href='https://fonts.googleapis.com/css?family=Monoton' rel='stylesheet' type='text/css'>
        <style>
            body { background-image: linear-gradient(310deg, #39135e 0%, #4a0327 100%); } .board { position: absolute; top: 50%; left: 50%; height: 150px; width: 512px; margin: -75px 0 0 -250px; padding: 20px; font: 75px/75px Monoton, cursive; text-align: center; text-transform: uppercase; text-shadow: 0 0 80px red, 0 0 30px FireBrick, 0 0 6px DarkRed; color: red; } #error { color: #fff; text-shadow: 0 0 80px #ffffff, 0 0 30px #008000, 0 0 6px #0000ff; margin-bottom: 25px; }
        </style>
    </head>
    <body>
        <div class="board">
            <div id="error">
                @yield('message')
            </div>
            <div id="code">
                @yield('code')
            </div>
        </div>
    </body>
    {{-- <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                        @yield('code')
                    </div>

                    <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                        @yield('message')
                    </div>
                </div>
            </div>
        </div>
    </body> --}}

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/novacancy/jquery.novacancy.min.js') }}"></script>
    <script>
        $(function() {
            $('#error').novacancy({
                'reblinkProbability': 0.1,
                'blinkMin': 0.2,
                'blinkMax': 0.6,
                'loopMin': 8,
                'loopMax': 10,
                'color': '#ffffff',
                'glow': ['0 0 80px #CB0C9F', '0 0 30px #fec3ff', '0 0 6px #fd87ff']
            });
            $('#code').novacancy({
                'blink': 1,
                'off': 1,
                'color': 'Red',
                'glow': ['0 0 80px Red', '0 0 30px FireBrick', '0 0 6px DarkRed']
            });
        });
    </script>
</html>
