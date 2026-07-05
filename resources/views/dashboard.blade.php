@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

<!-- Hero Banner -->
<div class="sa-hero">
    <div class="sa-hero-text">
        <span class="sa-hero-date">{{ now()->format('d F Y') }}</span>
        <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p class="sa-hero-sub">Kelola karirmu dengan mudah di SmartApply — temukan lowongan, lengkapi profil, dan lamar hanya dengan beberapa klik.</p>
        <ul>
            <li><i class="fas fa-check-circle"></i> Lengkapi profilmu untuk mendapatkan rekomendasi terbaik</li>
            <li><i class="fas fa-check-circle"></i> {{ \App\Models\Job::count() }} lowongan tersedia menantimu</li>
            <li><i class="fas fa-check-circle"></i> Sudah {{ Auth::user()->applications()->count() }} lamaran yang kamu kirim</li>
        </ul>
    </div>
    <div class="sa-hero-actions">
        @if(Auth::user()->profile && Auth::user()->profile->cv_path)
            <span class="btn btn-light"><i class="fas fa-check-circle text-success mr-1"></i> CV Terupload</span>
        @else
            <a href="{{ route('profile.edit') }}" class="btn btn-warning"><i class="fas fa-upload mr-1"></i> Upload CV Sekarang</a>
        @endif
        <a href="{{ route('jobs.index') }}" class="btn btn-light"><i class="fas fa-search mr-1"></i> Cari Lowongan</a>
    </div>
</div>

<!-- Stat Cards -->
<div class="sa-stat-grid">
    <div class="sa-stat-card">
        <div class="sa-stat-icon sa-icon-blue"><i class="fas fa-briefcase"></i></div>
        <div class="sa-stat-value">{{ \App\Models\Job::count() }}</div>
        <div class="sa-stat-label">Total Lowongan</div>
        <a href="{{ route('jobs.index') }}" class="sa-stat-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="sa-stat-card">
        <div class="sa-stat-icon sa-icon-green"><i class="fas fa-file-alt"></i></div>
        <div class="sa-stat-value">{{ Auth::user()->applications()->count() }}</div>
        <div class="sa-stat-label">Lamaran Dikirim</div>
        <a href="{{ route('applications.history') }}" class="sa-stat-link">Lihat Riwayat <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="sa-stat-card">
        <div class="sa-stat-icon sa-icon-amber"><i class="fas fa-clock"></i></div>
        <div class="sa-stat-value">{{ \App\Models\Application::where('user_id', Auth::id())->where('status', 'pending')->count() }}</div>
        <div class="sa-stat-label">Lamaran Pending</div>
        <a href="{{ route('applications.history') }}" class="sa-stat-link">Lihat Detail <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="sa-stat-card">
        <div class="sa-stat-icon sa-icon-red"><i class="fas fa-users"></i></div>
        <div class="sa-stat-value">{{ \App\Models\User::count() }}</div>
        <div class="sa-stat-label">Total Pelamar</div>
        <a href="{{ route('profile.edit') }}" class="sa-stat-link">Kelola Profil <i class="fas fa-arrow-right"></i></a>
    </div>
</div>

<!-- Recent Applications -->
<div class="sa-card">
    <div class="sa-card-header">
        <h3><i class="fas fa-clock mr-2"></i> Lamaran Terbaru</h3>
        <a href="{{ route('applications.history') }}" class="btn btn-sm btn-primary">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
    <div class="sa-card-body p-0">
        @php
            $recentApplications = Auth::user()->applications()->with('job')->orderBy('created_at', 'desc')->limit(5)->get();
        @endphp

        @if($recentApplications->isEmpty())
            <div class="text-center py-5">
                <p class="text-muted mb-3">Belum ada lamaran yang dikirim</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary"><i class="fas fa-briefcase mr-1"></i> Lamar Sekarang</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="pl-4">Posisi</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApplications as $app)
                            <tr>
                                <td class="pl-4"><strong>{{ $app->job->title }}</strong></td>
                                <td>{{ $app->job->company }}</td>
                                <td>
                                    @if($app->status == 'pending')
                                        <span class="badge badge-warning"><i class="fas fa-spinner fa-spin mr-1"></i> Pending</span>
                                    @elseif($app->status == 'accepted')
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Diterima</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times mr-1"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $app->created_at->diffForHumans() }}</span><br>
                                    <small class="text-muted">{{ $app->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <button onclick="showCoverLetter('{{ addslashes($app->cover_letter) }}')" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-alt"></i> Lihat
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="sa-section-title"><i class="fas fa-bolt"></i> Aksi Cepat</div>
<div class="sa-tile-grid">
    <a href="{{ route('jobs.index') }}" class="sa-tile"><i class="fas fa-briefcase"></i> Lowongan</a>
    <a href="{{ route('profile.edit') }}" class="sa-tile"><i class="fas fa-user-edit"></i> Edit Profil</a>
    <a href="{{ route('applications.history') }}" class="sa-tile"><i class="fas fa-history"></i> Riwayat</a>
    <a href="#" class="sa-tile" onclick="window.location.reload(); return false;"><i class="fas fa-sync"></i> Refresh</a>
</div>

@endsection

@push('scripts')
<script>
function showCoverLetter(content) {
    if (!$('#coverLetterModal').length) {
        var modalHtml = `
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
        `;
        $('body').append(modalHtml);
    }
    document.getElementById('coverLetterContent').textContent = content;
    $('#coverLetterModal').modal('show');
}
</script>
@endpush
