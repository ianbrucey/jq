<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'email',
        'phone',
        'relationship',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the comments for this relationship type.
     *
     * @return string
     */
    public static function getRelationshipTypes(): array
    {
        return ['attorney', 'court', 'opponent'];
    }

    /**
     * Create parties from document analysis results
     *
     * @param array $extractedParties Array of party data from AI analysis
     * @param int $userId The user ID to associate the parties with
     * @return array Array of created Party models
     */
    public static function createFromDocumentAnalysis(array $extractedParties, int $userId): array
    {
        $createdParties = [];

        foreach ($extractedParties as $partyData) {
            // Check if party with same name and address already exists for this user
            $existing = self::where('user_id', $userId)
                ->where('name', $partyData['name'])
                ->where('address_line1', $partyData['address_line1'])
                ->first();

            if (!$existing) {
                $partyData['user_id'] = $userId;
                $createdParties[] = self::create($partyData);
            }
        }

        return $createdParties;
    }
}
