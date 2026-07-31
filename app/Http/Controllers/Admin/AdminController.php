<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::query();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        $admins = $query->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        Admin::create($data);
        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $admin->update($data);
        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        if (auth()->guard('admin')->id() === $admin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'You cannot delete yourself.');
        }
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully.');
    }
}
