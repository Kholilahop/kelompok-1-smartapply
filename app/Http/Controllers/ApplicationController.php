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

        // Sementara pake fallback dulu (nanti diganti Mhs 3)
        $coverLetter = "Kepada Yth. HRD {$job->company}\n\n" .
                       "Dengan hormat,\n\n" .
                       "Saya {$user->name}, dengan ini mengajukan lamaran untuk posisi {$job->title} di {$job->company}.\n\n" .
                       "Saya memiliki skill: " . ($user->profile ? $user->profile->skills : 'Skill belum diisi') . "\n\n" .
                       "Saya tertarik dengan posisi ini karena sesuai dengan pengalaman dan keahlian saya.\n\n" .
                       "Demikian surat lamaran ini saya buat. Terima kasih atas perhatiannya.\n\n" .
                       "Hormat saya,\n" .
                       "{$user->name}\n" .
                       "Email: {$user->email}\n";

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