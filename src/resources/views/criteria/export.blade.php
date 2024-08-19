<x-export-layout>
    <h2 style="text-align: center">KRITERIA</h2>

    <p>Perihal: Daftar Kriteria</p>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kode</th>
                <th>Jenis Kriteria</th>
                <th>Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kriteria as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->jenisKriteria->nama ?? '-' }}</td>
                    <td>{{ $item->bobot }}</td>
                </tr>
            @endforeach
        </tbody>
        <!-- Tambahkan mata pelajaran dan nilai sesuai kebutuhan -->
    </table>
</x-export-layout>