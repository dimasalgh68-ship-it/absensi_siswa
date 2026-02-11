<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show settings page.
     */
    public function index()
    {
        $logo = Setting::get('app_logo');
        $appName = Setting::get('app_name', config('app.name'));
        
        // Attendance settings
        $lateToleranceMinutes = Setting::get('late_tolerance_minutes', 15);
        $clockOutEarlyMinutes = Setting::get('clock_out_early_minutes', 30);
        $clockInEarlyMinutes = Setting::get('clock_in_early_minutes', 60);
        $clockInLateMinutes = Setting::get('clock_in_late_minutes', 120);
        
        // Face recognition settings
        $faceSimilarityThreshold = Setting::get('face_similarity_threshold', 70);

        return view('admin.settings', compact(
            'logo', 
            'appName', 
            'lateToleranceMinutes', 
            'clockOutEarlyMinutes',
            'clockInEarlyMinutes',
            'clockInLateMinutes',
            'faceSimilarityThreshold'
        ));
    }

    /**
     * Update logo.
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        try {
            // Delete old logo if exists
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $path = $request->file('logo')->store('logos', 'public');

            // Update setting
            Setting::set('app_logo', $path);

            return back()->with('success', 'Logo berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui logo: ' . $e->getMessage());
        }
    }

    /**
     * Update app name.
     */
    public function updateAppName(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        try {
            Setting::set('app_name', $request->app_name);

            return back()->with('success', 'Nama aplikasi berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui nama aplikasi: ' . $e->getMessage());
        }
    }

    /**
     * Reset logo to default.
     */
    public function resetLogo()
    {
        try {
            // Delete logo file
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Reset setting
            Setting::set('app_logo', null);

            return back()->with('success', 'Logo berhasil direset ke default!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mereset logo: ' . $e->getMessage());
        }
    }

    /**
     * Update attendance settings.
     */
    public function updateAttendanceSettings(Request $request)
    {
        $request->validate([
            'late_tolerance_minutes' => 'required|integer|min:0|max:120',
            'clock_out_early_minutes' => 'required|integer|min:0|max:120',
            'clock_in_early_minutes' => 'required|integer|min:0|max:240',
            'clock_in_late_minutes' => 'required|integer|min:0|max:480',
        ]);

        try {
            Setting::set('late_tolerance_minutes', $request->late_tolerance_minutes);
            Setting::set('clock_out_early_minutes', $request->clock_out_early_minutes);
            Setting::set('clock_in_early_minutes', $request->clock_in_early_minutes);
            Setting::set('clock_in_late_minutes', $request->clock_in_late_minutes);

            return back()->with('success', 'Pengaturan absensi berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Update face recognition settings.
     */
    public function updateFaceRecognitionSettings(Request $request)
    {
        $request->validate([
            'face_similarity_threshold' => 'required|integer|min:50|max:95',
        ]);

        try {
            Setting::set('face_similarity_threshold', $request->face_similarity_threshold);

            return back()->with('success', 'Pengaturan face recognition berhasil diperbarui! Persentase kemiripan minimum sekarang ' . $request->face_similarity_threshold . '%');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan face recognition: ' . $e->getMessage());
        }
    }
}
