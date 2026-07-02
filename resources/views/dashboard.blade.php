@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ \App\Models\Job::count() }}</h3><p>Total Lowongan</p></div>
            <div class="icon"><i class="fas fa-briefcase"></i></div>
            <a href="{{ route('jobs.index') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ Auth::user()->applications()->count() }}</h3><p>Lamaran Dikirim</p></div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="{{ route('applications.history') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endsection