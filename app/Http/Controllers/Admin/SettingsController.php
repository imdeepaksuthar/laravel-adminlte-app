<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $user->update($request->only('name'));

        // Flash the success message to the session
        Session::flash('success', 'Profile updated successfully!');

        return redirect()->route('admin.settings'); // Redirect to settings page
    }

    public function updateSite(Request $request)
    {
        // Handle site name/logo logic
        // Save to .env, config, or database based on your structure
        return back()->with('success', 'Site settings updated!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:8',
        ]);

        if (! Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        auth()->user()->update(['password' => bcrypt($request->new_password)]);

        // Flash the success message to the session
        Session::flash('success', 'Password changed successfully!');

        return redirect()->route('admin.settings'); // Redirect to settings page
    }

}
