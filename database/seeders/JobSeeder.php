<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Software Engineer',
                'company' => 'Tech Company Indonesia',
                'description' => 'Kami mencari Software Engineer yang berpengalaman dalam pengembangan web.',
                'requirements' => 'Laravel, PHP, MySQL, JavaScript',
                'location' => 'Jakarta',
                'salary' => 'Rp 10.000.000 - 15.000.000'
            ],
            [
                'title' => 'UI/UX Designer',
                'company' => 'Design Studio',
                'description' => 'Membuat desain interface yang menarik dan user-friendly.',
                'requirements' => 'Figma, Adobe XD, User Research',
                'location' => 'Bandung',
                'salary' => 'Rp 8.000.000 - 12.000.000'
            ],
            [
                'title' => 'Data Analyst',
                'company' => 'Data Solutions',
                'description' => 'Menganalisis data dan memberikan insight untuk bisnis.',
                'requirements' => 'Python, SQL, Tableau',
                'location' => 'Surabaya',
                'salary' => 'Rp 9.000.000 - 14.000.000'
            ],
            [
                'title' => 'DevOps Engineer',
                'company' => 'Cloud Services',
                'description' => 'Mengelola infrastruktur dan deployment aplikasi.',
                'requirements' => 'AWS, Docker, Kubernetes',
                'location' => 'Yogyakarta',
                'salary' => 'Rp 12.000.000 - 18.000.000'
            ],
            [
                'title' => 'Mobile Developer',
                'company' => 'App Factory',
                'description' => 'Mengembangkan aplikasi mobile untuk iOS dan Android.',
                'requirements' => 'React Native, Flutter, Firebase',
                'location' => 'Jakarta',
                'salary' => 'Rp 11.000.000 - 16.000.000'
            ]
        ];

        foreach ($jobs as $job) {
            Job::create($job);
        }
    }
}