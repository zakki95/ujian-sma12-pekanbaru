<h1>Pratinjau Impor Siswa</h1>
@if(count($importErrors) > 0)
    <h2 style="color:red">Baris Bermasalah ({{ count($importErrors) }}) - tidak akan disimpan</h2>
    <ul>@foreach($importErrors as $e)<li>{{ $e }}</li>@endforeach</ul>
@endif
<h2>Baris Valid ({{ count($validRows) }})</h2>
<table border="1" cellpadding="6">
    <tr><th>No</th><th>Nama</th><th>Email</th><th>NIS</th></tr>
    @foreach($validRows as $r)
        <tr><td>{{ $r['no'] ?? '-' }}</td><td>{{ $r['nama_lengkap'] ?? '-' }}</td><td>{{ $r['email'] ?? '-' }}</td><td>{{ $r['nomor_induk'] ?? '-' }}</td></tr>
    @endforeach
</table>
<form method="POST" action="{{ route('import.siswa.process') }}">
    @csrf
    <input type="hidden" name="file_path" value="{{ $filePath }}">
    <button type="submit">Proses Impor ({{ count($validRows) }} baris valid)</button>
</form>