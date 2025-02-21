<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

class ApiTokenManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        if (!Features::hasApiFeatures()) {
            $this->markTestSkipped('API support is not enabled.');
        }
    }

    public function test_user_can_create_multiple_api_tokens()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        // Create first token
        Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Test Token 1',
                'permissions' => ['read', 'create']
            ]])
            ->call('createApiToken');

        // Create second token with different permissions
        Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Test Token 2',
                'permissions' => ['delete']
            ]])
            ->call('createApiToken');

        $this->assertCount(2, $user->fresh()->tokens);
        
        $tokens = $user->fresh()->tokens;
        $this->assertTrue($tokens[0]->can('read'));
        $this->assertTrue($tokens[0]->can('create'));
        $this->assertFalse($tokens[0]->can('delete'));
        
        $this->assertTrue($tokens[1]->can('delete'));
        $this->assertFalse($tokens[1]->can('read'));
    }

    public function test_tokens_have_unique_names()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Duplicate Name',
                'permissions' => ['read']
            ]])
            ->call('createApiToken');

        Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Duplicate Name',
                'permissions' => ['read']
            ]])
            ->call('createApiToken')
            ->assertHasErrors(['createApiTokenForm.name']);
    }

    public function test_token_permissions_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $component = Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Test Token',
                'permissions' => ['read']
            ]])
            ->call('createApiToken');

        $token = $user->fresh()->tokens->first();

        $component->set(['managingPermissionsFor' => $token])
            ->set(['updateApiTokenForm' => [
                'permissions' => ['read', 'update', 'delete']
            ]])
            ->call('updateApiToken');

        $this->assertTrue($token->fresh()->can('read'));
        $this->assertTrue($token->fresh()->can('update'));
        $this->assertTrue($token->fresh()->can('delete'));
    }

    public function test_tokens_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $component = Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Test Token',
                'permissions' => ['read']
            ]])
            ->call('createApiToken');

        $token = $user->fresh()->tokens->first();

        $component->set(['apiTokenIdBeingDeleted' => $token->id])
            ->call('deleteApiToken');

        $this->assertCount(0, $user->fresh()->tokens);
    }

    public function test_token_usage_is_tracked()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Livewire::test(ApiTokenManager::class)
            ->set(['createApiTokenForm' => [
                'name' => 'Test Token',
                'permissions' => ['read']
            ]])
            ->call('createApiToken');

        $token = $user->fresh()->tokens->first();

        // Simulate API request
        $response = $this->get('/api/test', [
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ]);

        $this->assertNotNull($token->fresh()->last_used_at);
    }
}