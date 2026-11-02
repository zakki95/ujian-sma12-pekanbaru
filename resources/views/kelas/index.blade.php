<h1>Data Kelas</h1>
<form method="GET" action="{{ route('kelas.index') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas...">
    <button type="submit">Cari</button>
</form>
<table border="1" cellpadding="6">
    <tr><th>Nama Kelas</th><th>Tingkat</th><th>Tahun Ajaran</th><th>Wali Kelas</th></tr>
    @forelse($kelas as $k)
        <tr>
            <td>{{ $k->nama_kelas }}</td>
            <td>{{ $k->tingkat }}</td>
            <td>{{ $k->tahun_ajaran }}</td>
            <td>{{ $k->waliKelas?->nama_lengkap ?? '-' }}</td>
        </tr>
    @empty
        <tr><td colspan="4">Tidak ada data kelas.</td></tr>
    @endforelse
</table>
{{ $kelas->links() }}