<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $client = auth()->guard('client')->user();
        return view('client.profile.edit', compact('client'));
    }

    public function update(Request $request)
    {
        $client = auth()->guard('client')->user();
        $request->validate([
            'firm_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'mobile_number' => 'required|numeric|unique:clients,mobile_number,' . $client->id,
            'email' => 'required|email|max:255|unique:clients,email,' . $client->id,
        ]);

        $client->update($request->only('firm_name', 'client_name', 'mobile_number', 'email'));
        return redirect()->route('client.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $client = auth()->guard('client')->user();

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        $client->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('client.profile.edit')->with('success', 'Password updated successfully.');
    }
}
