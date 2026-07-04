<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Services\GeminiService;

class ApplicationController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Show the form for creating a new application.
     */
    public function create(Job $job)
    {
        return view('applications.create', compact('job'));
    }

    /**
     * Generate cover letter using Gemini AI.
     */
    public function generateCoverLetter(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
        ]);

        // Ambil data job
        $job = Job::find($request->job_id);
        
        // Ambil data user/profile
        $user = auth()->user();
        $nama = $user->name ?? 'Pelamar';
        $posisi = $job->title;
        $skill = $job->requirements ?? 'Skill yang relevan';

        $result = $this->geminiService->generateCoverLetter([
            'nama' => $nama,
            'posisi' => $posisi,
            'skill' => $skill,
        ]);

        if ($result['success']) {
            return response()->json([
                'cover_letter' => $result['cover_letter']
            ]);
        }

        return response()->json([
            'message' => $result['message'] ?? 'Gagal generate surat lamaran'
        ], 500);
    }

    /**
     * Store a newly created application.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'cover_letter' => 'required|string',
        ]);

        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
        ]);

        return redirect()->route('applications.history')
            ->with('success', 'Lamaran berhasil dikirim!');
    }

    /**
     * Show application history.
     */
    public function history()
    {
        $applications = Application::with('job')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.history', compact('applications'));
    }
}