@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>📄 Generate Surat Lamaran dengan AI</h3>
        </div>
        <div class="card-body">
            <form id="coverLetterForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Posisi yang Dilamar</label>
                    <input type="text" id="posisi" class="form-control" placeholder="Contoh: Software Engineer" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Skill / Keahlian</label>
                    <textarea id="skill" class="form-control" rows="3" placeholder="Tuliskan keahlian yang dimiliki (pisahkan dengan koma)" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="generateBtn">
                    <span id="btnText">🚀 Generate Surat Lamaran</span>
                    <span id="btnLoading" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        Generating...
                    </span>
                </button>
            </form>

            <div id="result" class="mt-4" style="display:none;">
                <div class="alert alert-success">
                    <h4>✅ Hasil Surat Lamaran:</h4>
                    <div class="border p-3 bg-light" style="white-space: pre-wrap; word-wrap: break-word; max-height: 500px; overflow-y: auto;">
                        <pre id="coverLetterText" style="font-family: inherit; white-space: pre-wrap; word-wrap: break-word;"></pre>
                    </div>
                </div>
            </div>

            <div id="errorResult" class="mt-4" style="display:none;">
                <div class="alert alert-danger">
                    <h4>❌ Error:</h4>
                    <p id="errorText"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('coverLetterForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const nama = document.getElementById('nama').value;
    const posisi = document.getElementById('posisi').value;
    const skill = document.getElementById('skill').value;

    // Show loading
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnLoading').style.display = 'inline';
    document.getElementById('generateBtn').disabled = true;

    // Hide previous results
    document.getElementById('result').style.display = 'none';
    document.getElementById('errorResult').style.display = 'none';

    fetch('/generate-cover-letter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ nama, posisi, skill })
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading
        document.getElementById('btnText').style.display = 'inline';
        document.getElementById('btnLoading').style.display = 'none';
        document.getElementById('generateBtn').disabled = false;

        if (data.success) {
            document.getElementById('coverLetterText').innerText = data.cover_letter;
            document.getElementById('result').style.display = 'block';
        } else {
            document.getElementById('errorText').innerText = data.message || 'Gagal generate surat lamaran';
            document.getElementById('errorResult').style.display = 'block';
        }
    })
    .catch(error => {
        // Hide loading
        document.getElementById('btnText').style.display = 'inline';
        document.getElementById('btnLoading').style.display = 'none';
        document.getElementById('generateBtn').disabled = false;

        document.getElementById('errorText').innerText = 'Terjadi kesalahan: ' + error.message;
        document.getElementById('errorResult').style.display = 'block';
    });
});
</script>
@endsection