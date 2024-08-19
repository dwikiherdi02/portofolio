<x-export-layout>
    <h2 style="text-align: center">BIAYA SPP</h2>

    <p>Perihal: Biaya SPP Tahun {{ $tahunAjaran->tahun }}</p>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Biaya SPP (Rp.)</th>
                <th>Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($biayaSPP as $item)
            @php
                if (!empty($item->bobot_minimal) && !empty($item->bobot_maksimal)) {
                    $bobot = "{$item->bobot_minimal} < Bobot <= {$item->bobot_maksimal}";
                } elseif (!empty($item->bobot_minimal) && empty($item->bobot_maksimal)) {
                    $bobot = "Bobot > {$item->bobot_minimal}";
                } elseif (empty($item->bobot_minimal) && !empty($item->bobot_maksimal)) {
                    $bobot = "Bobot <= {$item->bobot_maksimal}";
                } else {
                    $bobot = "-";
                }
            @endphp
            <tr>
                <td>{{ number_format($item->nilai, 0, '.', '.') }}</td>
                <td>{{ $bobot }}</td>
            </tr>
            @endforeach
        </tbody>
        <!-- Tambahkan mata pelajaran dan nilai sesuai kebutuhan -->
    </table>
</x-export-layout>