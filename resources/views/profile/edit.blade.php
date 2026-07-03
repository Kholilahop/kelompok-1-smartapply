@extends('layouts.app')
@section('title', 'Edit Profil')
@section('breadcrumb', 'Profil')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Profil & Biodata Pelamar</h3></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Nama Lengkap</label><input type="text" value="{{ $user->name }}" class="form-control" disabled></div></div>
                <div class="col-md-6"><div class="form-group"><label>Email</label><input type="email" value="{{ $user->email }}" class="form-control" disabled></div></div>
                <div class="col-md-6"><div class="form-group"><label>No HP</label><input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="form-control"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Skills</label><input type="text" name="skills" value="{{ old('skills', $profile->skills ?? '') }}" class="form-control"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Alamat</label><textarea name="address" rows="2" class="form-control">{{ old('address', $profile->address ?? '') }}</textarea></div></div>
                <div class="col-md-12"><div class="form-group"><label>Pengalaman Kerja</label><textarea name="experience" rows="4" class="form-control">{{ old('experience', $profile->experience ?? '') }}</textarea></div></div>
                
                <!-- Upload CV -->
                <div class="col-md-12"><div class="form-group"><label>Upload CV (PDF)</label>@if($profile && $profile->cv_path)<div class="mb-2"><span class="text-success">✅ CV saat ini: </span><a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat PDF</a></div>@endif<input type="file" name="cv" accept=".pdf" class="form-control-file"><small class="text-muted">Maksimal 10MB, format PDF</small></div></div>

                <!-- Upload Foto Profil (TAMBAHKAN INI!) -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Foto Profil (JPG/PNG)</label>
                        @if($profile && $profile->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $profile->photo) }}" alt="Foto Profil" class="img-circle" width="100" height="100" style="object-fit: cover;">
                                <br>
                                <span class="text-success">✅ Foto saat ini</span>
                            </div>
                        @else
                            <div class="mb-2">
                                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="Foto Default" class="img-circle" width="100" height="100" style="object-fit: cover;">
                                <br>
                                <span class="text-muted">Belum ada foto</span>
                            </div>
                        @endif
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg" class="form-control-file">
                        <small class="text-muted">Maksimal 2MB, format JPG/PNG</small>
                    </div>
                </div>
            </div>
            <div class="form-group text-right"><a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button></div>
        </form>
    </div>
</div>
@endsection