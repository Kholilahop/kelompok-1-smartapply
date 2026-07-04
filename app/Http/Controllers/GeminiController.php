<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class GeminiController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        return view('gemini.form');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'skill' => 'required|string|max:1000',
        ]);

        $result = $this->geminiService->generateCoverLetter([
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'skill' => $request->skill,
        ]);

        return response()->json($result);
    }
}