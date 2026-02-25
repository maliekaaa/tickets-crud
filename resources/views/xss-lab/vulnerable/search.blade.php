<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable Search</title>
</head>
<body>

    <h1>Hasil Pencarian</h1>

    {{-- RENTAN XSS --}}
    <p>Anda mencari: {{ $query }}</p>

    <form method="GET" action="{{ url('xss-lab/vulnerable/search') }}">
        <input type="text" name="q" placeholder="Cari sesuatu...">
        <button type="submit">Search</button>
    </form>

</body>
</html>
