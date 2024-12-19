@extends('master')

@section('content')
<h1>Buat Artikel Baru</h1>

<!-- Form untuk input artikel -->
<form action="{{ route('store.articles') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Input untuk Title -->
    <div>
        <label for="title">Judul Artikel:</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        @error('title')
            <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>

    <!-- Input untuk Banner -->
    <div>
        <label for="banner">URL Banner (Opsional):</label>
        <input type="file" id="banner" name="banner" accept="image/*">
        @error('banner')
            <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Input untuk Konten -->
    <div>
        <label for="content">Konten Artikel:</label>
        <textarea class="jodit-editor" name="content"></textarea>
        @error('content')
            <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tombol Submit -->
    <div>
        <button type="submit">Simpan Artikel</button>
    </div>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3.24.3/build/jodit.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@3.24.3/build/jodit.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editors = document.querySelectorAll('.jodit-editor');
        editors.forEach(editor => {
            new Jodit(editor, {
                height: 400,
                uploader: {
                    insertImageAsBase64URI: true
                }
            });
        });
    });
</script>
@endsection