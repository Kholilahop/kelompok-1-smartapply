<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return view('jobs.index');
    }

    public function getData()
    {
        // Menggunakan Yajra DataTables seperti di PDF
        $jobs = Job::select(['id', 'title', 'company', 'location', 'salary']);
        
        return DataTables::of($jobs)
            ->addColumn('action', function($job) {
                return '<a href="'.route('applications.create', $job->id).'" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-3 rounded-md text-sm transition duration-150">Lamar</a>';
            })
            ->editColumn('salary', function($job) {
                return $job->salary ?? 'Rp 0';
            })
            ->rawColumns(['action'])
            ->make(true);  // ← Ini yang di PDF!
    }
}