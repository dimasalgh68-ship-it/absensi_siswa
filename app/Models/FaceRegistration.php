<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'face_embedding',
        'photo_path',
        'is_active',
        'registered_at',
    ];

    protected $casts = [
        'face_embedding' => 'array',
        'is_active' => 'boolean',
        'registered_at' => 'datetime',
    ];

    /**
     * Get the user that owns the face registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the face embedding as array.
     */
    public function getEmbeddingVector(): array
    {
        return $this->face_embedding ?? [];
    }

    /**
     * Calculate Euclidean distance between two embeddings.
     */
    public static function calculateEuclideanDistance(array $embedding1, array $embedding2): float
    {
        if (count($embedding1) !== count($embedding2)) {
            throw new \InvalidArgumentException('Embeddings must have the same dimension');
        }

        $sum = 0;
        for ($i = 0; $i < count($embedding1); $i++) {
            $sum += pow($embedding1[$i] - $embedding2[$i], 2);
        }

        return sqrt($sum);
    }

    /**
     * Calculate Cosine similarity between two embeddings.
     */
    public static function calculateCosineSimilarity(array $embedding1, array $embedding2): float
    {
        if (count($embedding1) !== count($embedding2)) {
            throw new \InvalidArgumentException('Embeddings must have the same dimension');
        }

        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;

        for ($i = 0; $i < count($embedding1); $i++) {
            $dotProduct += $embedding1[$i] * $embedding2[$i];
            $magnitude1 += pow($embedding1[$i], 2);
            $magnitude2 += pow($embedding2[$i], 2);
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }
}
