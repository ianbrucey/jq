<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenAI\Laravel\Facades\OpenAI;

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

    /**
     * Process voice input and create party records
     *
     * @param string $voiceText The transcribed voice input
     * @param int $userId The user ID to associate the parties with
     * @return array Array of created Party models
     */
    public static function createFromVoiceInput(string $voiceText, int $userId): array
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a contact information extraction specialist. Extract names and addresses from the provided text and format them as JSON. Return only valid JSON with no additional text.'
                ],
                [
                    'role' => 'user',
                    'content' => "Please analyze this text and extract any names and addresses. Format the results as JSON in this structure:\n" .
                        "{\n" .
                        "    \"parties\": [\n" .
                        "        {\n" .
                        "            \"name\": \"...\",\n" .
                        "            \"address_line1\": \"...\",\n" .
                        "            \"address_line2\": null,\n" .
                        "            \"city\": \"...\",\n" .
                        "            \"state\": \"...\",\n" .
                        "            \"zip\": \"...\",\n" .
                        "            \"email\": null,\n" .
                        "            \"phone\": null,\n" .
                        "            \"relationship\": null\n" .
                        "        }\n" .
                        "    ]\n" .
                        "}\n\n" .
                        "Here's the text to analyze:\n" . $voiceText
                ],
            ],
            'temperature' => 0.2,
        ]);

        $content = $response->choices[0]->message->content;
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['parties'])) {
            throw new \Exception('Failed to parse AI response: ' . json_last_error_msg());
        }

        return self::createFromDocumentAnalysis($data['parties'], $userId);
    }
}
