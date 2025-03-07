<div class="relative">
    <button
        wire:click="$toggle('showNotifications')"
        class="btn btn-ghost btn-circle"
    >
        <div class="indicator">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if($this->unreadCount > 0)
                <span class="badge badge-sm badge-primary indicator-item">{{ $this->unreadCount }}</span>
            @endif
        </div>
    </button>

    @if($showNotifications)
        <div
            class="absolute right-0 mt-2 w-80 bg-base-100 rounded-lg shadow-xl border border-base-300 z-50"
            x-on:click.away="$wire.showNotifications = false"
        >
            <div class="p-4 border-b border-base-300">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium">{{ __('notifications.title') }}</h3>
                    @if($this->unreadCount > 0)
                        <button
                            wire:click="markAllAsRead"
                            class="text-sm text-primary hover:text-primary-focus"
                        >
                            {{ __('notifications.mark_all_read') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="divide-y divide-base-300 max-h-96 overflow-y-auto">
                @forelse($this->unreadNotifications as $notification)
                    <div class="p-4 hover:bg-base-200 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                @switch($notification->type)
                                    @case('App\Notifications\CollaborationInviteNotification')
                                        <p class="text-sm">
                                            {{ __('notifications.invite_received', [
                                                'case' => $notification->data['case_title'],
                                                'role' => $notification->data['role']
                                            ]) }}
                                        </p>
                                        <a
                                            href="{{ route('cases.show', $notification->data['case_file_id']) }}"
                                            class="text-primary text-sm hover:text-primary-focus"
                                        >
                                            {{ __('notifications.view_case') }}
                                        </a>
                                        @break

                                    @case('App\Notifications\CollaboratorRemovedNotification')
                                        <p class="text-sm">
                                            {{ __('notifications.access_revoked', [
                                                'case' => $notification->data['case_title']
                                            ]) }}
                                        </p>
                                        @break

                                    @case('App\Notifications\CollaboratorRoleChangedNotification')
                                        <p class="text-sm">
                                            {{ __('notifications.role_changed', [
                                                'case' => $notification->data['case_title'],
                                                'role' => $notification->data['new_role']
                                            ]) }}
                                        </p>
                                        @break
                                @endswitch
                                <span class="text-xs text-base-content/70">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <button
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="text-xs text-base-content/70 hover:text-base-content"
                            >
                                {{ __('notifications.mark_read') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-base-content/70">
                        {{ __('notifications.no_notifications') }}
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
