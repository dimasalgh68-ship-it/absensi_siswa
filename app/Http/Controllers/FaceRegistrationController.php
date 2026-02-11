<?php

namespace App\Http\Controllers;

use App\Models\FaceRegistration;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FaceRegistrationController extends Controller
{
    protected FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Show face registration form.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $registration = FaceRegistration::where('user_id', $user->id)
            ->where('is_active', true)
            ->latest()
            ->first();

        return view('face-registration.index', compact('registration'));
    }

    /**
     * Register user's face.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'descriptor' => 'nullable|json',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Check if user already has active registration
            $existingRegistration = FaceRegistration::where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if ($existingRegistration) {
                return back()->with('error', 'Anda sudah memiliki registrasi wajah aktif. Hubungi administrator jika perlu mengubah registrasi.');
            }

            // Get descriptor from JavaScript or generate in PHP
            $embedding = null;
            
            if ($request->has('descriptor') && !empty($request->descriptor)) {
                // Use descriptor from JavaScript (face-api.js)
                try {
                    $embedding = json_decode($request->descriptor, true);
                    
                    if (!is_array($embedding) || count($embedding) !== 128) {
                        throw new \Exception('Invalid descriptor format');
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to parse descriptor: ' . $e->getMessage());
                    $embedding = null;
                }
            }
            
            // If no valid descriptor from JS, generate in PHP
            if (!$embedding) {
                $registration = $this->faceService->registerFace($user, $request->file('photo'));
                
                DB::commit();

                return redirect()
                    ->route('face-registration.index')
                    ->with('success', 'Wajah berhasil didaftarkan!');
            }

            // Save photo
            $photoPath = $request->file('photo')->store('face-registrations', 'public');

            // Create registration with JavaScript descriptor
            $registration = FaceRegistration::create([
                'user_id' => $user->id,
                'face_embedding' => $embedding,
                'photo_path' => $photoPath,
                'is_active' => true,
                'registered_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('face-registration.index')
                ->with('success', 'Wajah berhasil didaftarkan! Anda sekarang dapat melakukan absensi menggunakan face recognition.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Face registration error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Gagal mendaftarkan wajah: ' . $e->getMessage());
        }
    }

    /**
     * Get face descriptor for browser-based verification.
     */
    public function getDescriptor()
    {
        $user = Auth::user();
        
        $registration = FaceRegistration::where('user_id', $user->id)
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Wajah belum terdaftar'
            ]);
        }

        return response()->json([
            'success' => true,
            'descriptor' => $registration->getEmbeddingVector()
        ]);
    }
}
