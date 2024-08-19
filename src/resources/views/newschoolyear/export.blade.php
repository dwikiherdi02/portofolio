<x-export-layout>
    <h2 style="text-align: center">TAHUN AJARAN</h2>

    <p>Perihal: Daftar Tahun Ajaran</p>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Tahun</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tahunAjaran as $item)
            <tr>
                <td>{{ $item->tahun }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
        <!-- Tambahkan mata pelajaran dan nilai sesuai kebutuhan -->
    </table>
</x-export-layout>