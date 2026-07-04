<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $timeout;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
        $this->model = config('gemini.model', 'gemini-pro');
        $this->timeout = config('gemini.timeout', 60);
    }

    public function generateCoverLetter(array $data)
    {
        set_time_limit($this->timeout);
        
        $prompt = $this->buildPrompt($data);

        try {
            $response = Http::timeout($this->timeout)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/' . $this->model . ':generateContent?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $result = $response->json();
            Log::info('Gemini API Response:', $result);

            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'success' => true,
                    'cover_letter' => $result['candidates'][0]['content']['parts'][0]['text']
                ];
            }

            if (isset($result['error'])) {
                return [
                    'success' => false,
                    'message' => 'Gemini API Error: ' . $result['error']['message']
                ];
            }

            return [
                'success' => false,
                'message' => 'Response tidak valid dari Gemini API'
            ];

        } catch (\Exception $e) {
            Log::error('Gemini API Exception:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    private function buildPrompt(array $data)
    {
        return "Buat surat lamaran kerja yang profesional dan singkat untuk posisi {$data['posisi']}. 
        Nama pelamar: {$data['nama']}. 
        Keahlian yang dimiliki: {$data['skill']}. 
        
        Surat harus terdiri dari 3-4 paragraf dengan format:
        - Paragraf 1: Perkenalan dan minat melamar
        - Paragraf 2: Keahlian dan pengalaman relevan
        - Paragraf 3: Penutup dan harapan
        
        Gunakan bahasa formal dan profesional.";
    }
}