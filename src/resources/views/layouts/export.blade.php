<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        tr {
            border-bottom: 2px solid #000000;
        }

        h1 {
            color: #333;
        }

        .logo img {
            max-width: 75px;
            max-height: 75px;
        }

        .address {
            margin-top: 20px;
            margin-bottom: 40px;
        }

        .content {
            line-height: 1.6;
            color: #333;
        }

        .footer {
            text-align: right;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    {{-- <table>
        <tr style="border-bottom: none !important;">
            <th colspan="2" style="text-align: left;">
                <div class="logo">
                    <img src="{{ $logo }}" alt="DW Project">
                </div>
            </th>
        </tr>
    </table> --}}

    <div class="content">
        {{ $slot }}
    </div>
    <div class="footer">
        <p>Bogor, {{ $date }} </p>
        <br> <br> <br>
        <p>Tim DW Project</p>
    </div>

</body>

</html>