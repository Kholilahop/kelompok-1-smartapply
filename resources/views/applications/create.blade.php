@extends('layouts.app')
@section('title', 'Apply Lamaran')
@section('breadcrumb', 'Form Lamaran')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Lamaran: {{ $job->title }}</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h5><strong>{{ $job->title }}</strong></h5>
            <p><strong>Perusahaan:</strong> {{ $job->company }}</p>
            <p><strong>Lokasi:</strong> {{ $job->location ?? 'Tidak tersedia' }}</p>
            <p><strong>Gaji:</strong> {{ $job->salary ?? 'Tidak tersedia' }}</p>
            <p><strong>Deskripsi:</strong> {{ $job->description }}</p>
            <p><strong>Persyaratan:</strong> {{ $job->requirements }}</p>
        </div>
        <form method="POST" action="{{ route('applications.store') }}">
            @csrf
            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <div class="form-group">
                <label>Surat Lamaran</label>
                <button type="button" id="generate-btn" class="btn btn-success btn-sm mb-2">🤖 Generate dengan AI</button>
                <div id="loading" style="display:none; color: #0d6efd; margin-bottom: 10px;">
                    ⏳ Sedang membuat surat lamaran...
                </div>
                <textarea name="cover_letter" id="cover_letter" rows="10" class="form-control" placeholder="Tulis surat lamaranmu di sini..." required>{{ old('cover_letter') }}</textarea>
            </div>
            <div class="form-group text-right">
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">📤 Kirim Lamaran</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#generate-btn').on('click', function(){
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('⏳ Generating...');
        $('#loading').show();
        
        $.ajax({
            url: '{{ route("applications.generate") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                job_id: '{{ $job->id }}',
                _token: '{{ csrf_token() }}'
            },
            success: function(response){
                console.log('Success:', response);
                if (response.cover_letter) {
                    $('#cover_letter').val(response.cover_letter);
                } else {
                    alert('Gagal: ' + (response.message || 'Tidak ada response'));
                }
                btn.prop('disabled', false).html(originalText);
                $('#loading').hide();
            },
            error: function(xhr){
                console.log('Error:', xhr);
                if (xhr.status === 302) {
                    alert('Sesi login habis! Silakan refresh halaman dan login ulang.');
                    window.location.href = '{{ route("login") }}';
                } else {
                    alert('Gagal generate: ' + (xhr.responseJSON?.message || xhr.statusText || 'Unknown error'));
                }
                btn.prop('disabled', false).html(originalText);
                $('#loading').hide();
            }
        });
    });
});
</script>
@endpush