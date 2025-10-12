<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function adminDataUser()
    {
        $users = User::where('role', 'User')->get();
        return view('home/view_admin/users.index', compact('users'));
    }

    public function adminCreateUser()
    {
        return view('home/view_admin/users.create');
    }

    public function adminStoreUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'phone' => 'nullable|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'device_name' => 'required|string|max:255',
        ]);

        // Simpan user baru
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => 'User',
            'password' => Hash::make($request->username),
        ]);

        // Simpan tracking kosong (koordinat belum masuk)
        Tracking::create([
            'user_id' => $user->id,
            'device_name' => $request->device_name,
        ]);

        return redirect()->route('adminDataUser')->with('success', 'User berhasil ditambahkan. Password default sama dengan username.');
    }

    public function adminEditUser($id)
    {
        $user = User::findOrFail($id);
        return view('home/view_admin/users.edit', compact('user'));
    }

    public function adminUpdateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
            'phone' => 'nullable|string|unique:users,phone,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
        ]);

        $user->name = $request->name;

        if ($user->username !== $request->username) {
            $user->username = $request->username;
            $user->password = Hash::make($request->username); // reset password sesuai username baru
        }

        $user->phone = $request->phone;
        $user->email = $request->email;

        $user->save();

        return redirect()->route('adminDataUser')->with('success', 'Data user berhasil diperbarui.');
    }

    public function adminDeleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('adminDataUser')->with('success', 'User berhasil dihapus.');
    }
}
