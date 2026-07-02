<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'cv' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        $profile = $user->profile ?? new UserProfile();
        $profile->user_id = $user->id;
        $profile->phone = $request->phone;
        $profile->address = $request->address;
        $profile->skills = $request->skills;
        $profile->experience = $request->experience;

        if ($request->hasFile('cv')) {
            if ($profile->cv_path && Storage::disk('public')->exists($profile->cv_path)) {
                Storage::disk('public')->delete($profile->cv_path);
            }
            
            $path = $request->file('cv')->store('cvs', 'public');
            $profile->cv_path = $path;
        }

        $profile->save();

        return redirect()->back()->with('success', '✅ Profil berhasil diperbarui!');
    }
}