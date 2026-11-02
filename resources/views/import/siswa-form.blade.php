<h1>Impor Data Siswa</h1>
<form method="POST" action="{{ route('import.siswa.preview') }}" enctype="multipart/form-data">
    @csrf
    <p><input type="file" name="file_siswa" accept=".xlsx,.xls,.csv" required></p>
    <button type="submit">Pratinjau</button>
</form>