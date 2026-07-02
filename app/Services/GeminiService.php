<?php

namespace App\Services;

use YasserElgammal\LaravelGemini\Facades\Gemini;

class GeminiService
{
    public function generateCoverLetter($user, $job)
    {
        $profile = $user->profile;
        
        $prompt = "Buatkan surat lamaran kerja yang profesional dan menarik untuk posisi {$job->title} di perusahaan {$job->company}.\n\n";
        $prompt .= "Data Pelamar:\n";
        $prompt .= "Nama: {$user->name}\n";
        $prompt .= "Email: {$user->email}\n";
        
        if ($profile) {
            $prompt .= "No HP: {$profile->phone}\n";
            $prompt .= "Alamat: {$profile->address}\n";
            $prompt .= "Skill: {$profile->skills}\n";
            $prompt .= "Pengalaman: {$profile->experience}\n";
        }
        
        $prompt .= "\nInformasi Lowongan:\n";
        $prompt .= "Deskripsi: {$job->description}\n";
        $prompt .= "Persyaratan: {$job->requirements}\n";
        $prompt .= "Lokasi: {$job->location}\n";
        
        $prompt .= "\nBuat surat lamaran dengan format:\n";
        $prompt .= "1. Pembukaan yang menarik\n";
        $prompt .= "2. Perkenalan diri dan latar belakang\n";
        $prompt .= "3. Penjelasan mengapa kandidat cocok untuk posisi ini\n";
        $prompt .= "4. Penutup yang profesional\n";
        $prompt .= "5. Jangan lupa cantumkan nama lengkap dan kontak di bagian bawah\n";
        $prompt .= "\nSurat lamaran harus dalam bahasa Indonesia yang formal dan profesional.";

        try {
            // Coba pake model yang berbeda
            $response = Gemini::generate($prompt);
            return $response;
            
        } catch (\Exception $e) {
            \Log::error('Gemini error: ' . $e->getMessage());
            return $this->generateFallbackCoverLetter($user, $job);
        }
    }

    private function generateFallbackCoverLetter($user, $job)
    {
        $profile = $user->profile;
        $skills = $profile ? $profile->skills : 'Skill belum diisi';
        $experience = $profile ? $profile->experience : 'Pengalaman belum diisi';
        $phone = $profile ? $profile->phone : 'Nomor HP belum diisi';
        $address = $profile ? $profile->address : 'Alamat belum diisi';
        
        return "Kepada Yth. HRD {$job->company}\n\n" .
               "Dengan hormat,\n\n" .
               "Saya {$user->name}, dengan ini mengajukan lamaran untuk posisi {$job->title} di {$job->company}.\n\n" .
               "Biodata singkat:\n" .
               "Nama: {$user->name}\n" .
               "Email: {$user->email}\n" .
               "No HP: {$phone}\n" .
               "Alamat: {$address}\n\n" .
               "Saya memiliki keahlian sebagai berikut:\n" .
               "{$skills}\n\n" .
               "Pengalaman kerja saya:\n" .
               "{$experience}\n\n" .
               "Saya tertarik dengan posisi ini karena sesuai dengan pengalaman dan keahlian saya.\n\n" .
               "Saya siap untuk bergabung dan berkontribusi untuk perusahaan {$job->company}.\n\n" .
               "Demikian surat lamaran ini saya buat. Terima kasih atas perhatiannya.\n\n" .
               "Hormat saya,\n" .
               "{$user->name}\n" .
               "Email: {$user->email}";
    }
}