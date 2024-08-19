<x-export-layout>
    <h2 style="text-align: center">DAFTAR SISWA TAHUN {{ $tahunAjaran->tahun }}</h2>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama Siswa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $item)
            <tr>
                <td>{{ $item->nis }}</td>
                <td>{{ $item->nama }}</td>
            </tr>
            @endforeach
        </tbody>
        <!-- Tambahkan mata pelajaran dan nilai sesuai kebutuhan -->
    </table>
</x-export-layout>