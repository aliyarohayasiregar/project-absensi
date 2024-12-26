<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // Hanya admin yang bisa melihat semua karyawan
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $employees = Employee::with('user')->get();
        return response()->json([
            'data' => $employees
        ]);
    }

    public function show()
    {
        $employee = auth()->user()->employee;
        return response()->json([
            'data' => $employee
        ]);
    }

    public function update(Request $request)
    {
        $employee = auth()->user()->employee;
        
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
        ]);

        $employee->update($request->only([
            'phone',
            'address'
        ]));

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $employee
        ]);
    }
} 