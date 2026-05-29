<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'current_password' => 'nullable',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->new_password) {
            if (!$request->current_password) {
                return back()->with('error', 'Password lama wajib diisi.');
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Password lama salah.');
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect('/profile')->with('success', 'Profil admin berhasil diperbarui.');
    }
}