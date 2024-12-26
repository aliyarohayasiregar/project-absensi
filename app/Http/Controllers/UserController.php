<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        // Hanya admin yang bisa melihat semua user
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $users = User::with('employee')->get();
        return response()->json([
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        // Validasi hanya admin yang bisa menambah user
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:admin,user',
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // Buat data employee jika rolenya user
        if ($request->role === 'user') {
            Employee::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address
            ]);
        }

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => $user->load('employee')
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'phone' => 'required_if:role,user|string',
            'address' => 'required_if:role,user|string'
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        // Update atau create employee jika role user
        if ($request->role === 'user') {
            Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $request->phone,
                    'address' => $request->address
                ]
            );
        }

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'data' => $user->load('employee')
        ]);
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Cek jika user yang akan dihapus adalah admin terakhir
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'Tidak dapat menghapus admin terakhir'
                ], 422);
            }
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'password' => ['required', Password::defaults()]
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password berhasil direset'
        ]);
    }
} 