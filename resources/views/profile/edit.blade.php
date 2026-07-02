<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Profil') }}
            </h2>
            <span class="text-sm text-gray-500">Lengkapi data dirimu</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" value="{{ $user->name }}" disabled class="w-full rounded-lg border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-lg border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">No HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Skills</label>
                                <input type="text" name="skills" value="{{ old('skills', $profile->skills ?? '') }}" class="w-full rounded-lg border-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300">{{ old('address', $profile->address ?? '') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Pengalaman</label>
                                <textarea name="experience" rows="4" class="w-full rounded-lg border-gray-300">{{ old('experience', $profile->experience ?? '') }}</textarea>
                            </div>

                            <div class="md:col-span-2 border-2 border-dashed border-blue-300 p-4 rounded-lg bg-blue-50">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload CV (PDF)</label>
                                
                                @if($profile && $profile->cv_path)
                                    <div class="bg-white p-3 rounded border border-blue-200 mb-3">
                                        <span class="text-sm text-green-600">✅ CV: </span>
                                        <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat PDF</a>
                                    </div>
                                @endif

                                <input type="file" name="cv" accept=".pdf" class="w-full">
                                <p class="text-xs text-gray-500 mt-2">Maksimal 10MB, format PDF</p>
                            </div>
                        </div>

                        <!-- TOMBOL DENGAN STYLE LANGSUNG -->
                        <div class="flex justify-end space-x-4 pt-6 border-t mt-6" style="padding: 20px 0; background: white;">
                            <a href="{{ route('dashboard') }}" style="background-color: #6b7280; color: white; padding: 10px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">
                                Batal
                            </a>
                            <button type="submit" style="background-color: #2563eb; color: white; padding: 10px 24px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: inline-block; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.4);">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>