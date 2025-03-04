<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Laravel\Jetstream\Jetstream;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

/**
 * Enhanced API Token Manager Component
 *
 * This component extends Jetstream's API Token Manager with additional features
 * specifically designed for project-level token management. It includes:
 * - Token usage metrics
 * - Enhanced permission management
 * - Detailed token information display
 * - Custom permission descriptions
 *
 * @property bool $showingTokenMetrics Controls the visibility of token metrics modal
 * @property ?\Laravel\Sanctum\PersonalAccessToken $selectedToken Currently selected token for metrics view
 * @property array $tokenMetrics Metrics data for the selected token
 */
#[Layout('layouts.app')]
class EnhancedApiTokenManager extends ApiTokenManager
{
    public $showingTokenMetrics = false;
    public $selectedToken = null;
    public $tokenMetrics = [];
    public $showingTokenInfo = false;
    public $tokenCreatedAt = null;
    public $tokenExpiresAt = null;

    /**
     * Mount component with additional initialization
     */
    public function mount(): void
    {
        parent::mount();
        $this->tokenExpiresAt = now()->addYear()->format('Y-m-d');
    }

    /**
     * Display metrics for a specific token
     *
     * @param int $tokenId The ID of the token to show metrics for
     */
    public function showTokenMetrics($tokenId): void
    {
        $this->selectedToken = Auth::user()->tokens()->findOrFail($tokenId);
        $this->tokenMetrics = $this->getTokenMetrics($tokenId);
        $this->showingTokenMetrics = true;
    }

    /**
     * Show detailed information about a token
     *
     * @param int $tokenId The ID of the token to show information for
     */
    public function showTokenInfo($tokenId): void
    {
        $this->selectedToken = Auth::user()->tokens()->findOrFail($tokenId);
        $this->tokenCreatedAt = $this->selectedToken->created_at;
        $this->showingTokenInfo = true;
    }

    /**
     * Get usage metrics for a specific token
     *
     * @param int $tokenId The ID of the token to get metrics for
     * @return array Array containing token usage metrics
     */
    protected function getTokenMetrics($tokenId): array
    {
        // Here you would typically integrate with your token usage tracking system
        // This is a placeholder implementation
        return [
            'requests_24h' => rand(0, 100), // Replace with actual metrics
            'total_requests' => rand(100, 1000), // Replace with actual metrics
            'last_used' => $this->selectedToken->last_used_at,
            'created_at' => $this->selectedToken->created_at,
            'expires_at' => $this->selectedToken->expires_at ?? 'Never',
            'active_status' => $this->getTokenStatus(),
        ];
    }

    /**
     * Get the current status of the selected token
     *
     * @return string The status of the token (Active, Expired, or Inactive)
     */
    protected function getTokenStatus(): string
    {
        if (!$this->selectedToken) {
            return 'Unknown';
        }

        if ($this->selectedToken->expires_at && Carbon::parse($this->selectedToken->expires_at)->isPast()) {
            return 'Expired';
        }

        if (!$this->selectedToken->last_used_at) {
            return 'Never Used';
        }

        return 'Active';
    }

    /**
     * Get descriptions for available permissions
     *
     * @return array Array of permission descriptions
     */
    public function getPermissionDescriptions(): array
    {
        return [
            'create' => 'Allows creating new resources in the project',
            'read' => 'Allows reading existing project resources',
            'update' => 'Allows updating existing project resources',
            'delete' => 'Allows deleting project resources',
            'upload' => 'Allows file uploads to the project',
            'download' => 'Allows file downloads from the project',
        ];
    }

    /**
     * Override the create token method to include expiration
     */
    public function createApiToken(): void
    {
        $this->resetErrorBag();

        $this->validate([
            'createApiTokenForm.name' => ['required', 'string', 'max:255'],
            'tokenExpiresAt' => ['nullable', 'date', 'after:today'],
        ]);

        $this->createApiTokenForm['expires_at'] = $this->tokenExpiresAt;

        parent::createApiToken();
    }
}
