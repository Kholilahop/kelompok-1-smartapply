@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\Job::count() }}</h3>
                <p>Total Lowongan</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('jobs.index') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ Auth::user()->applications()->count() }}</h3>
                <p>Lamaran Dikirim</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('applications.history') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\Application::where('status', 'pending')->count() }}</h3>
                <p>Lamaran Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('applications.history') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>Total Pelamar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Grafik (Opsional) -->
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Statistik Lamaran</h3>
            </div>
            <div class="card-body">
                <canvas id="chartLamaran" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart lamaran per status
    const ctx = document.getElementById('chartLamaran').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    {{ \App\Models\Application::where('status', 'pending')->count() }},
                    {{ \App\Models\Application::where('status', 'accepted')->count() }},
                    {{ \App\Models\Application::where('status', 'rejected')->count() }}
                ],
                backgroundColor: ['#ffc107', '#28a745', '#dc3545']
            }]
        }
    });
</script>
@endpush