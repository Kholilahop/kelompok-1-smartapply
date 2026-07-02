@extends('layouts.app')

@section('title', 'Apply Lamaran')
@section('breadcrumb', 'Form Lamaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Lamaran: {{ $job->title }}</h3>
    </div>
    <div class="card-body">
        
        <!-- Info Lowongan -->
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
                <button type="button" id="generate-btn" class="btn btn-success btn-sm mb-2">
                    🤖 Generate dengan AI
                </button>
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
    $(document).ready(function() {
        $('#generate-btn').on('click', function() {
            const btn = $(this);
            const originalText = btn.html();
            btn.prop('disabled', true).html('⏳ Generating...');

            $.ajax({
                url: '{{ route("applications.generate") }}',
                method: 'POST',
                data: {
                    job_id: '{{ $job->id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#cover_letter').val(response.cover_letter);
                    btn.prop('disabled', false).html(originalText);
                },
                error: function() {
                    alert('Gagal generate surat lamaran. Silakan coba lagi.');
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
@endpush