@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<!-- Statistik Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>{{ \App\Models\Job::count() }}</h3>
                <p>Total Lowongan</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('jobs.index') }}" class="small-box-footer">
                Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>{{ Auth::user()->applications()->count() }}</h3>
                <p>Lamaran Dikirim</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('applications.history') }}" class="small-box-footer">
                Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>{{ \App\Models\Application::where('user_id', Auth::id())->where('status', 'pending')->count() }}</h3>
                <p>Lamaran Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('applications.history') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-danger">
            <div class="inner">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>Total Pelamar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('profile.edit') }}" class="small-box-footer">
                Kelola Profil <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-smile-beam mr-2"></i>
                    Selamat Datang, {{ Auth::user()->name }}!
                </h3>
                <div class="card-tools">
                    <span class="badge badge-primary">{{ now()->format('d F Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="lead">Kelola karirmu dengan mudah di SmartApply</p>
                        <p class="text-muted">
                            <i class="fas fa-check-circle text-success mr-1"></i> 
                            Lengkapi profilmu untuk mendapatkan rekomendasi terbaik
                            <br>
                            <i class="fas fa-check-circle text-success mr-1"></i> 
                            {{ \App\Models\Job::count() }} lowongan tersedia menantimu
                            <br>
                            <i class="fas fa-check-circle text-success mr-1"></i> 
                            Sudah {{ Auth::user()->applications()->count() }} lamaran yang kamu kirim
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        @if(Auth::user()->profile && Auth::user()->profile->cv_path)
                            <span class="badge badge-success p-2">
                                <i class="fas fa-check-circle mr-1"></i> CV Terupload
                            </span>
                        @else
                            <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-upload mr-1"></i> Upload CV Sekarang
                            </a>
                        @endif
                        <br>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-search mr-1"></i> Cari Lowongan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-2"></i>
                    Lamaran Terbaru
                </h3>
                <div class="card-tools">
                    <a href="{{ route('applications.history') }}" class="btn btn-sm btn-info">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $recentApplications = Auth::user()->applications()->with('job')->orderBy('created_at', 'desc')->limit(5)->get();
                @endphp
                
                @if($recentApplications->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada lamaran yang dikirim</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-briefcase mr-1"></i> Lamar Sekarang
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
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
                                @foreach($recentApplications as $app)
                                    <tr>
                                        <td>
                                            <strong>{{ $app->job->title }}</strong>
                                        </td>
                                        <td>{{ $app->job->company }}</td>
                                        <td>
                                            @if($app->status == 'pending')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-spinner fa-spin mr-1"></i> Pending
                                                </span>
                                            @elseif($app->status == 'accepted')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i> Diterima
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times mr-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $app->created_at->diffForHumans() }}</span>
                                            <br>
                                            <small class="text-muted">{{ $app->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <button onclick="showCoverLetter('{{ addslashes($app->cover_letter) }}')" class="btn btn-sm btn-info">
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
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('jobs.index') }}" class="btn btn-app">
                            <i class="fas fa-briefcase text-info"></i> Lowongan
                        </a>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-app">
                            <i class="fas fa-user-edit text-primary"></i> Edit Profil
                        </a>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('applications.history') }}" class="btn btn-app">
                            <i class="fas fa-history text-warning"></i> Riwayat
                        </a>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <a href="#" class="btn btn-app" onclick="window.location.reload();">
                            <i class="fas fa-sync text-success"></i> Refresh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-box .icon {
        font-size: 60px;
        opacity: 0.3;
        transition: all 0.3s ease;
    }
    .small-box:hover .icon {
        opacity: 0.6;
        transform: scale(1.1);
    }
    .small-box .inner h3 {
        font-size: 32px;
        font-weight: 700;
    }
    .btn-app {
        min-width: 80px;
        min-height: 80px;
        margin: 5px;
        padding: 15px 5px;
        border-radius: 10px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .btn-app:hover {
        background: #e9ecef;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-app i {
        font-size: 28px;
        display: block;
        margin-bottom: 5px;
    }
    .btn-app .badge {
        margin-top: 5px;
    }
    .table td {
        vertical-align: middle !important;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%) !important;
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%) !important;
    }
</style>
@endpush

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