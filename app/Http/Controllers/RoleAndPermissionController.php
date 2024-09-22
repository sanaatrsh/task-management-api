<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleAndPermissionController extends Controller
{
    public function assignRole(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'role' => 'required|exists:roles,name',
            ]);

            $user = User::findOrFail($validated['user_id']);
            $role = Role::findByName($validated['role']);

            $user->assignRole($role);

            return response()->json(['message' => 'Role assigned successfully.']);
        }
        return response()->json([
            'massage' => 'Access denied',
        ], 401);
    }
}
