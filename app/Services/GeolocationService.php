<?php

namespace App\Services;

use App\Models\OfficeLocation;
use Illuminate\Support\Facades\Log;

class GeolocationService
{
    /**
     * Validate if coordinates are within any active office location.
     */
    public function validateLocation(
        float $latitude, 
        float $longitude,
        ?float $accuracy = null,
        ?float $altitude = null,
        ?float $speed = null,
        ?int $timestamp = null
    ): array
    {
        try {
            // Anti-spoofing checks
            $spoofingCheck = $this->detectGPSSpoofing(
                $latitude, 
                $longitude, 
                $accuracy, 
                $altitude, 
                $speed, 
                $timestamp
            );

            if (!$spoofingCheck['valid']) {
                return [
                    'valid' => false,
                    'message' => $spoofingCheck['message'],
                    'distance' => null,
                    'office' => null,
                    'spoofing_detected' => true,
                    'spoofing_reasons' => $spoofingCheck['reasons'],
                ];
            }

            $nearestOffice = OfficeLocation::findNearest($latitude, $longitude);

            if (!$nearestOffice) {
                return [
                    'valid' => false,
                    'message' => 'Tidak ada lokasi Sekolah yang terdaftar.',
                    'distance' => null,
                    'office' => null,
                    'spoofing_detected' => false,
                ];
            }

            $distance = OfficeLocation::calculateDistance(
                $latitude,
                $longitude,
                $nearestOffice->latitude,
                $nearestOffice->longitude
            );

            $isWithin = $nearestOffice->isWithinRadius($latitude, $longitude);

            return [
                'valid' => $isWithin,
                'message' => $isWithin 
                    ? 'Lokasi valid' 
                    : sprintf(
                        'Anda berada di luar jangkauan. Jarak: %.0f meter dari %s (radius: %d meter)',
                        $distance,
                        $nearestOffice->name,
                        $nearestOffice->radius_meters
                    ),
                'distance' => round($distance, 2),
                'office' => $nearestOffice,
                'spoofing_detected' => false,
            ];
        } catch (\Exception $e) {
            Log::error('GeolocationService error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return [
                'valid' => false,
                'message' => 'Error validasi lokasi: ' . $e->getMessage(),
                'distance' => null,
                'office' => null,
                'spoofing_detected' => false,
            ];
        }
    }

    /**
     * Get all active office locations.
     */
    public function getActiveOffices(): \Illuminate\Database\Eloquent\Collection
    {
        return OfficeLocation::where('is_active', true)->get();
    }

    /**
     * Calculate distance between two points.
     */
    public function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        return OfficeLocation::calculateDistance($lat1, $lon1, $lat2, $lon2);
    }

    /**
     * Detect GPS spoofing based on multiple indicators.
     */
    protected function detectGPSSpoofing(
        float $latitude,
        float $longitude,
        ?float $accuracy = null,
        ?float $altitude = null,
        ?float $speed = null,
        ?int $timestamp = null
    ): array {
        // Check if anti-spoofing is enabled
        $enabled = \App\Models\Setting::get('gps_anti_spoofing_enabled', true);
        
        if (!$enabled) {
            return [
                'valid' => true,
                'message' => 'GPS valid (anti-spoofing disabled)',
                'suspicion_score' => 0,
                'reasons' => [],
            ];
        }

        $reasons = [];
        $suspicionScore = 0;

        // 1. Check for impossible coordinates
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            $reasons[] = 'Koordinat tidak valid';
            $suspicionScore += 100;
        }

        // 2. Check for exact zero coordinates (common in fake GPS)
        if ($latitude == 0 && $longitude == 0) {
            $reasons[] = 'Koordinat null island (0,0) terdeteksi';
            $suspicionScore += 100;
        }

        // 3. Check for suspiciously high accuracy (too perfect)
        if ($accuracy !== null && $accuracy < 5) {
            $reasons[] = 'Akurasi GPS terlalu sempurna (< 5 meter)';
            $suspicionScore += 30;
        }

        // 4. Check for suspiciously low accuracy (very inaccurate)
        if ($accuracy !== null && $accuracy > 100) {
            $reasons[] = 'Akurasi GPS sangat rendah (> 100 meter)';
            $suspicionScore += 20;
        }

        // 5. Check for impossible altitude (if provided)
        if ($altitude !== null) {
            if ($altitude < -500 || $altitude > 9000) {
                $reasons[] = 'Ketinggian tidak wajar';
                $suspicionScore += 40;
            }
        }

        // 6. Check for impossible speed (if provided)
        if ($speed !== null && $speed > 50) { // > 180 km/h
            $reasons[] = 'Kecepatan tidak wajar (> 180 km/h)';
            $suspicionScore += 50;
        }

        // 7. Check timestamp freshness (if provided)
        if ($timestamp !== null) {
            $timeDiff = abs(time() - $timestamp);
            if ($timeDiff > 60) { // More than 1 minute old
                $reasons[] = 'Data GPS tidak fresh (> 1 menit)';
                $suspicionScore += 30;
            }
        }

        // 8. Check for common fake GPS coordinates patterns
        // Coordinates with too many repeating decimals
        $latStr = (string) $latitude;
        $lonStr = (string) $longitude;
        
        if ($this->hasRepeatingPattern($latStr) || $this->hasRepeatingPattern($lonStr)) {
            $reasons[] = 'Pola koordinat mencurigakan';
            $suspicionScore += 25;
        }

        // 9. Check for coordinates with too few decimal places (rounded)
        $latDecimals = strlen(substr(strrchr($latStr, "."), 1));
        $lonDecimals = strlen(substr(strrchr($lonStr, "."), 1));
        
        if ($latDecimals < 4 || $lonDecimals < 4) {
            $reasons[] = 'Koordinat terlalu dibulatkan';
            $suspicionScore += 15;
        }

        // Determine if spoofing is detected based on score
        $threshold = (int) \App\Models\Setting::get('gps_anti_spoofing_threshold', 50);
        $isValid = $suspicionScore < $threshold;

        if (!$isValid) {
            Log::warning('GPS Spoofing detected', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'accuracy' => $accuracy,
                'altitude' => $altitude,
                'speed' => $speed,
                'timestamp' => $timestamp,
                'suspicion_score' => $suspicionScore,
                'threshold' => $threshold,
                'reasons' => $reasons,
            ]);
        }

        return [
            'valid' => $isValid,
            'message' => $isValid 
                ? 'GPS valid' 
                : 'GPS spoofing terdeteksi: ' . implode(', ', $reasons),
            'suspicion_score' => $suspicionScore,
            'reasons' => $reasons,
        ];
    }

    /**
     * Check if a string has repeating patterns (e.g., 1.111111, 2.222222).
     */
    protected function hasRepeatingPattern(string $value): bool
    {
        // Check for patterns like 1.111111 or 2.222222
        if (preg_match('/\.(\d)\1{4,}/', $value)) {
            return true;
        }

        // Check for patterns like 1.123123123
        if (preg_match('/\.(\d{2,3})\1{2,}/', $value)) {
            return true;
        }

        return false;
    }
}
