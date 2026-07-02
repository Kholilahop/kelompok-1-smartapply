@extends('layouts.app')

@section('title', 'Edit Profil')
@section('breadcrumb', 'Profil')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Profil & Biodata Pelamar</h3>
    </div>
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="form-control" placeholder="081234567890">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Keahlian / Skills</label>
                        <input type="text" name="skills" value="{{ old('skills', $profile->skills ?? '') }}" class="form-control" placeholder="PHP, Laravel, JavaScript">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alamat Domisili</label>
                        <textarea name="address" rows="2" class="form-control" placeholder="Alamat lengkap">{{ old('address', $profile->address ?? '') }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Pengalaman Kerja</label>
                        <textarea name="experience" rows="4" class="form-control" placeholder="Ceritakan pengalaman kerjamu">{{ old('experience', $profile->experience ?? '') }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Upload CV (PDF)</label>
                        @if($profile && $profile->cv_path)
                            <div class="mb-2">
                                <span class="text-success">✅ CV saat ini: </span>
                                <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat PDF</a>
                            </div>
                        @endif
                        <input type="file" name="cv" accept=".pdf" class="form-control-file">
                        <small class="text-muted">Maksimal 10MB, format PDF</small>
                    </div>
                </div>
            </div>

            <div class="form-group text-right">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
@endsection