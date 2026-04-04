<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->where('is_admin', true)->latest()->paginate(20),
            'roles' => config('permissions.roles', []),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => config('permissions.roles', []),
        ]);
    }

    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        User::query()->create([
            ...$request->safe()->except('is_active'),
            'is_admin' => true,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(User $user): View
    {
        abort_unless($user->is_admin, 404);

        return view('admin.users.edit', [
            'adminUser' => $user,
            'roles' => config('permissions.roles', []),
        ]);
    }

    public function update(UpdateAdminUserRequest $request, User $user): RedirectResponse
    {
        abort_unless($user->is_admin, 404);

        $data = $request->safe()->except(['password', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Admin user updated successfully.');
    }
}
