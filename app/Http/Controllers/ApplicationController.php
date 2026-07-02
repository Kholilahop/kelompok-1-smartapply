<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Menampilkan form lamaran untuk job tertentu
     */
    public function create($jobId)
    {
        $job = Job::findOrFail($jobId);
        $user = Auth::user();
        return view('applications.create', compact('job', 'user'));
    }

    /**
     * Generate surat lamaran menggunakan Gemini AI
     */
    public function generateCoverLetter(Request $request)
    {
        try {
            $request->validate([
                'job_id' => 'required|exists:job_listings,id'
            ]);

            $user = Auth::user();
            $job = Job::findOrFail($request->job_id);

            // PAKAI GEMINI ASLI!
            $geminiService = new GeminiService();
            $coverLetter = $geminiService->generateCoverLetter($user, $job);

            return response()->json([
                'cover_letter' => $coverLetter
            ]);

        } catch (\Exception $e) {
            \Log::error('Generate error: ' . $e->getMessage());
            
            // FALLBACK MANUAL JIKA GEMINI ERROR
            $profile = $user->profile ?? null;
            $skills = $profile ? $profile->skills : 'Skill belum diisi';
            $experience = $profile ? $profile->experience : 'Pengalaman belum diisi';
            $phone = $profile ? $profile->phone : 'Nomor HP belum diisi';
            $address = $profile ? $profile->address : 'Alamat belum diisi';

            $coverLetter = "Kepada Yth. HRD {$job->company}\n\n" .
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

            return response()->json([
                'cover_letter' => $coverLetter,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menyimpan lamaran ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:job_listings,id',
            'cover_letter' => 'required|string'
        ]);

        Application::create([
            'user_id' => Auth::id(),
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Lamaran berhasil dikirim!');
    }

    /**
     * Menampilkan riwayat lamaran user
     */
    public function history()
    {
        $applications = Application::with(['job'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('applications.history', compact('applications'));
    }
}