<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function create($jobId)
    {
        $job = Job::findOrFail($jobId);
        $user = Auth::user();
        return view('applications.create', compact('job', 'user'));
    }

    public function generateCoverLetter(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id'
        ]);

        $user = Auth::user();
        $job = Job::findOrFail($request->job_id);

        // Panggil GeminiService langsung
        $geminiService = new GeminiService();
        $coverLetter = $geminiService->generateCoverLetter($user, $job);

        return response()->json([
            'cover_letter' => $coverLetter
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
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

    public function history()
    {
        $applications = Application::with(['job'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('applications.history', compact('applications'));
    }
}