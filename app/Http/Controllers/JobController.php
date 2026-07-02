<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{
    public function index()
    {
        return view('jobs.index');
    }

    public function getData()
{
    $jobs = Job::query();
    
    return DataTables::of($jobs)
        ->addColumn('action', function($job) {
            return '<a href="'.route('applications.create', $job->id).'" class="btn btn-primary btn-sm">Lamar</a>';
        })
        ->editColumn('salary', function($job) {
            return $job->salary ?? 'Rp 0';
        })
        ->rawColumns(['action'])
        ->make(true);
}
}