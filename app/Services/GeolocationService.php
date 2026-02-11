<?php

namespace App\Services;

use App\Models\OfficeLocation;

class GeolocationService
{
    /**
     * Validate if coordinates are within any active office location.
     */
    public function validateLocation(float $latitude, float $longitude): array
    {
        try {
            $nearestOffice = OfficeLocation::findNearest($latitude, $longitude);

            if (!$nearestOffice) {
                return [
                    'valid' => false,
                    'message' => 'Tidak ada lokasi Sekolah yang terdaftar.',
                    'distance' => null,
                    'office' => null,
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
            ];
        } catch (\Exception $e) {
            \Log::error('GeolocationService error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return [
                'valid' => false,
                'message' => 'Error validasi lokasi: ' . $e->getMessage(),
                'distance' => null,
                'office' => null,
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
}
