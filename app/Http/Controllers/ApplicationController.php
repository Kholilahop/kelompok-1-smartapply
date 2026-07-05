<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            $request->validate([
                'job_id' => 'required|exists:job_listings,id'
            ]);

            $user = Auth::user();
            $job = Job::findOrFail($request->job_id);

            $result = $this->geminiService->generateCoverLetter([
                'user' => $user,
                'job' => $job,
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Generate error: ' . $e->getMessage());

            // FALLBACK
            try {
                $user = Auth::user();
                $job = Job::findOrFail($request->job_id);

                $fallback = $this->geminiService->generateFallbackCoverLetter($user, $job);

                return response()->json([
                    'success' => true,
                    'cover_letter' => $fallback,
                    'fallback' => true
                ]);
            } catch (\Exception $e2) {
                return response()->json([
                    'success' => false,
                    'error' => $e2->getMessage()
                ], 500);
            }
        }
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