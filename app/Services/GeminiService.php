<?php

namespace App\Services;

use YasserElgammal\LaravelGemini\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $model;
    protected $timeout;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->model = config('gemini.model');
        $this->timeout = config('gemini.timeout', 60);
    }

    public function generateCoverLetter(array $data)
    {
        set_time_limit((int) $this->timeout);

        if (empty($this->apiKey)) {
            Log::error('Gemini API key kosong. Set GEMINI_API_KEY di file .env.');
            return [
                'success' => false,
                'message' => 'GEMINI_API_KEY belum diisi di file .env. Ambil API key gratis di https://aistudio.google.com/apikey lalu isi ke .env, setelah itu jalankan php artisan config:clear.'
            ];
        }

        $prompt = $this->buildPrompt($data);

        try {
            Log::info('Sending request to Gemini API via yasser-elgammal');

            $response = Gemini::generate($prompt);

            Log::info('Gemini response received');

            // Normalisasi respon jadi string
            if (is_string($response)) {
                $text = $response;
            } elseif (is_array($response) && isset($response['text'])) {
                $text = $response['text'];
            } elseif (is_object($response) && isset($response->text)) {
                $text = $response->text;
            } else {
                $text = json_encode($response);
            }

            return [
                'success' => true,
                'cover_letter' => $text,
            ];

        } catch (\Exception $e) {
            Log::error('Gemini API error: ' . $e->getMessage());

            // Jika data mengandung user & job, gunakan fallback berdasarkan object
            if (isset($data['user']) && isset($data['job'])) {
                $fallback = $this->generateFallbackCoverLetter($data['user'], $data['job']);
            } else {
                // Jika data berbentuk form sederhana (nama/posisi/skill)
                $user = (object) [
                    'name' => $data['nama'] ?? 'Pelamar',
                    'email' => $data['email'] ?? 'email@domain.tld',
                    'profile' => (object) [
                        'skills' => $data['skill'] ?? 'Skill belum diisi',
                        'experience' => $data['experience'] ?? 'Pengalaman belum diisi',
                        'phone' => $data['phone'] ?? 'Nomor HP belum diisi',
                        'address' => $data['address'] ?? 'Alamat belum diisi',
                    ]
                ];

                $job = (object) [
                    'title' => $data['posisi'] ?? ($data['position'] ?? 'Posisi'),
                    'company' => $data['company'] ?? 'Perusahaan'
                ];

                $fallback = $this->generateFallbackCoverLetter($user, $job);
            }

            return [
                'success' => true,
                'cover_letter' => $fallback,
                'fallback' => true,
            ];
        }
    }

    public function generateFallbackCoverLetter($user, $job)
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

    protected function buildPrompt(array $data)
    {
        // Jika diberikan objek user & job (dari backend), gunakan data tersebut
        if (isset($data['user']) && isset($data['job'])) {
            $user = $data['user'];
            $job = $data['job'];

            $skills = isset($user->profile->skills) ? $user->profile->skills : 'Skill belum diisi';
            $experience = isset($user->profile->experience) ? $user->profile->experience : 'Pengalaman belum diisi';

            $prompt = "Buatkan surat lamaran singkat dan profesional untuk posisi {$job->title} di {$job->company} untuk pelamar bernama {$user->name}.\n";
            $prompt .= "Sertakan ringkasan keterampilan: {$skills} dan pengalaman: {$experience}. Gunakan bahasa Indonesia formal.";

            return $prompt;
        }

        // Jika diberikan data form sederhana
        $nama = $data['nama'] ?? ($data['name'] ?? 'Pelamar');
        $posisi = $data['posisi'] ?? ($data['position'] ?? 'Posisi');
        $skill = $data['skill'] ?? 'Skill belum diisi';

        $prompt = "Buatkan surat lamaran singkat dan profesional untuk posisi {$posisi} untuk pelamar bernama {$nama}.\n";
        $prompt .= "Sertakan keterampilan: {$skill}. Gunakan bahasa Indonesia formal.";

        return $prompt;
    }
}