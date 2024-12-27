<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('employee')->where('role', 'user')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        Employee::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $user->employee->update([
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Karyawan berhasil dihapus');
    }
} 