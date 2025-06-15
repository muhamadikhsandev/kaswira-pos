<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil user.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update data user di tabel "users" (misalnya username, password, dsb.).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Update password jika diisi, jika tidak diubah maka hapus dari data
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Hapus email dari data yang akan diupdate
        unset($data['email']);

        // Update data user (username, password, dll.)
        $user->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'username Password (akun) berhasil diperbarui.',
                // Sertakan nama baru agar bisa diupdate di navbar
                 // 'name' => $user->name, // Baris ini dihapus agar tidak mengirim username ke navbar sebagai default
            ], 200);
        }

        return Redirect::route('admin.profile.edit')
            ->with([
                'status'  => 'profile-updated',
                'success' => 'Profil (akun) berhasil diperbarui.'
            ]);
    }

    /**
     * Update foto profil user (disimpan di user_profiles).
     */
    public function updateProfilePicture(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:12048',
        ]);

        $user = $request->user();
        // Jika profil belum ada, buat baru
        $userProfile = $user->profile ?? $user->profile()->create();

        // Hapus foto lama jika ada
        if (!empty($userProfile->profile_photo_path) && Storage::disk('public')->exists($userProfile->profile_photo_path)) {
            Storage::disk('public')->delete($userProfile->profile_photo_path);
        }

        // Simpan foto baru di folder 'profile_photos' pada disk 'public'
        $path = $request->file('profile_picture')->store('profile_photos', 'public');
        $userProfile->update([
            'profile_photo_path' => $path,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message'           => 'Foto profil berhasil diperbarui.',
                'profile_photo_url' => asset('storage/' . $path),
            ], 200);
        }

        return Redirect::route('admin.profile.edit')
            ->with([
                'status'  => 'profile-photo-updated',
                'success' => 'Foto profil berhasil diperbarui.'
            ]);
    }

    /**
     * Update informasi profil user (nama, telepon, alamat) di tabel user_profiles.
     */
    public function updateProfileInfo(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        // Buat profil jika belum ada
        $userProfile = $user->profile ?? $user->profile()->create();

        // Validasi data profil
        $data = $request->validate([
            'profile_name'    => 'required|string|max:255',
            'profile_phone'   => 'required|string|max:20',
            'profile_address' => 'required|string|max:255',
        ]);

        // Update kolom di tabel user_profiles
        $userProfile->update([
            'name'    => $data['profile_name'],
            'phone'   => $data['profile_phone'],
            'address' => $data['profile_address'],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message'      => 'Informasi profil berhasil diperbarui.',
                'profile_name' => $userProfile->name,
            ], 200);
        }

        return Redirect::route('admin.profile.edit')
            ->with([
                'status'  => 'profile-info-updated',
                'success' => 'Informasi profil berhasil diperbarui.'
            ]);
    }
}
