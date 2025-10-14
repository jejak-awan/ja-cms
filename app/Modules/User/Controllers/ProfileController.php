<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        // Cache user profile for 5 minutes
        $user = cache()->remember("user_profile_{$userId}", 300, function() {
            return Auth::user();
        });
        return view('admin.users.profile.show', compact('user'));
    }

    public function edit()
    {
        $userId = Auth::id();
        // Cache user profile for 5 minutes
        $user = cache()->remember("user_profile_{$userId}", 300, function() {
            return Auth::user();
        });
        return view('admin.users.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url',
            'social_links.twitter' => 'nullable|url',
            'social_links.linkedin' => 'nullable|url',
            'social_links.instagram' => 'nullable|url',
            'social_links.github' => 'nullable|url',
        ]);

        $data = $request->only([
            'name', 'email', 'bio', 'phone', 'website', 
            'location', 'birth_date', 'gender', 'social_links'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatar = $request->file('avatar');
            $filename = 'avatars/' . Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            
            // Resize and optimize image
            $image = Image::make($avatar)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 85);

            Storage::disk('public')->put($filename, $image);
            $data['avatar'] = $filename;
        }

                $user->update($data);

        // Clear cached user profile after update
        cache()->forget("user_profile_{$user->id}");

        return redirect()
            ->route('admin.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('admin.users.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Password updated successfully!');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Avatar deleted successfully!');
    }

    public function showUserProfile(User $user)
    {
        return view('admin.users.profile.show-user', compact('user'));
    }

    public function editUserProfile(User $user)
    {
        return view('admin.users.profile.edit-user', compact('user'));
    }

    public function updateUserProfile(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,editor,author,contributor,subscriber',
            'status' => 'required|string|in:active,inactive,suspended',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url',
            'social_links.twitter' => 'nullable|url',
            'social_links.linkedin' => 'nullable|url',
            'social_links.instagram' => 'nullable|url',
            'social_links.github' => 'nullable|url',
        ]);

        $data = $request->only([
            'name', 'email', 'role', 'status', 'bio', 'phone', 'website', 
            'location', 'birth_date', 'gender', 'social_links'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatar = $request->file('avatar');
            $filename = 'avatars/' . Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            
            // Resize and optimize image
            $image = Image::make($avatar)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 85);

            Storage::disk('public')->put($filename, $image);
            $data['avatar'] = $filename;
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User profile updated successfully!');
    }
}
