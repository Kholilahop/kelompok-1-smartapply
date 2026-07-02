<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Apply Lamaran') }}
            </h2>
            <span class="text-sm text-gray-500">{{ $job->title }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Job Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $job->title }}</h3>
                                <p class="text-gray-600 text-lg">{{ $job->company }}</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $job->location ?? 'Lokasi tidak tersedia' }}
                                    </span>
                                    <span class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $job->salary ?? 'Gaji tidak tersedia' }}
                                    </span>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Aktif</span>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">{{ $job->description }}</p>
                        </div>
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700">Persyaratan:</p>
                            <p class="text-sm text-gray-600">{{ $job->requirements }}</p>
                        </div>
                    </div>

                    <!-- Form Lamaran -->
                    <form id="application-form" method="POST" action="{{ route('applications.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Surat Lamaran</label>
                                <button type="button" id="generate-btn" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition duration-150 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Generate dengan AI
                                </button>
                            </div>
                            <textarea name="cover_letter" id="cover_letter" rows="15" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 font-mono text-sm" required placeholder="Tulis surat lamaranmu di sini...">{{ old('cover_letter') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Klik tombol "Generate dengan AI" untuk membuat surat lamaran otomatis</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t">
                            <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Batal</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition duration-150 shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Kirim Lamaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#generate-btn').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();
                btn.prop('disabled', true)
                   .html('<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...');

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
                        
                        // Animasi flash
                        $('#cover_letter').animate({ backgroundColor: '#f0fdf4' }, 300)
                                         .animate({ backgroundColor: '#ffffff' }, 300);
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal generate surat lamaran. Silahkan coba lagi.',
                            confirmButtonColor: '#2563eb'
                        });
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>