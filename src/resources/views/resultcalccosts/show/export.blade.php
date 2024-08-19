<x-export-layout>
    <h2 style="text-align: center">BIAYA SPP SISWA TAHUN {{ $tahunAjaran->tahun }}</h2>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Total Preferensi</th>
                <th>Biaya (Rp.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasil as $item)
            <tr>
                <td>{{ $item->siswa->nis }}</td>
                <td>{{ $item->siswa->nama }}</td>
                <td style="background-color: #83d615ff; color: #ffffff">{{ number_format($item->total_preferensi, 2, ',', '.') }}</td>
                <td style="background-color: #334666ff; color: #ffffff">{{ number_format($item->biaya, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <!-- Tambahkan mata pelajaran dan nilai sesuai kebutuhan -->
    </table>
</x-export-layout>