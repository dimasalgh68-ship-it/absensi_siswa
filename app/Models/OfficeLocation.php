<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Calculate distance between two coordinates using Haversine formula.
     * Returns distance in meters.
     */
    public static function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Check if given coordinates are within this office location's radius.
     */
    public function isWithinRadius(float $latitude, float $longitude): bool
    {
        $distance = self::calculateDistance(
            $this->latitude,
            $this->longitude,
            $latitude,
            $longitude
        );

        return $distance <= $this->radius;
    }

    /**
     * Get the nearest office location to given coordinates.
     */
    public static function findNearest(float $latitude, float $longitude): ?self
    {
        try {
            $query = self::where('is_active', true);
            
            if (!$query) {
                \Log::error('OfficeLocation query builder is null');
                return null;
            }
            
            $locations = $query->get();

            if (!$locations || $locations->isEmpty()) {
                \Log::warning('No active office locations found');
                return null;
            }

            $nearest = null;
            $minDistance = PHP_FLOAT_MAX;

            foreach ($locations as $location) {
                $distance = self::calculateDistance(
                    $latitude,
                    $longitude,
                    $location->latitude,
                    $location->longitude
                );

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearest = $location;
                }
            }

            return $nearest;
        } catch (\Exception $e) {
            \Log::error('OfficeLocation::findNearest error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return null;
        }
    }
}
