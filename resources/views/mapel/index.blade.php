<h1>Data Mata Pelajaran</h1>
<table border="1" cellpadding="6">
    <tr><th>Nama</th><th>Kode</th></tr>
    @foreach($mapel as $m)
        <tr><td><a href="{{ url('mata-pelajaran/'.$m->id) }}">{{ $m->nama_mapel }}</a></td><td>{{ $m->kode_mapel }}</td></tr>
    @endforeach
</table>
{{ $mapel->links() }}