@extends('layouts.app')

@section('title', 'Riwayat Lamaran')
@section('breadcrumb', 'Riwayat')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Lamaran</h3>
    </div>
    <div class="card-body">
        @if($applications->isEmpty())
            <div class="text-center py-4">
                <p class="text-muted">Belum ada lamaran yang dikirim.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">Lihat Lowongan</a>
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Posisi</th>
                        <th>Perusahaan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->job->title }}</td>
                            <td>{{ $app->job->company }}</td>
                            <td>
                                @if($app->status == 'pending')
                                    <span class="badge badge-warning">⏳ Pending</span>
                                @elseif($app->status == 'accepted')
                                    <span class="badge badge-success">✅ Diterima</span>
                                @else
                                    <span class="badge badge-danger">❌ Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $app->created_at->format('d M Y') }}</td>
                            <td>
                                <button onclick="showCoverLetter({{ json_encode($app->cover_letter) }})" class="btn btn-info btn-sm">
                                    📄 Lihat Surat
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- Modal -->
<div id="coverLetterModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📄 Surat Lamaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="coverLetterContent" class="p-3 bg-light" style="white-space: pre-wrap; max-height: 400px; overflow-y: auto; border-radius: 5px;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showCoverLetter(content) {
    document.getElementById('coverLetterContent').textContent = content;
    $('#coverLetterModal').modal('show');
}
</script>
@endpush