<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class NotificationsList extends Component
{
    public bool $showNotifications = false;

    protected $listeners = [
        'echo-private:App.Models.User.{userId},Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => 'handleNewNotification',
        'echo-private:App.Models.User.{userId},NotificationRead' => 'handleNotificationRead'
    ];

    public function mount()
    {
        // Replace {userId} with actual user ID in listeners
        $this->listeners = [
            'echo-private:App.Models.User.' . auth()->id() . ',Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => 'handleNewNotification',
            'echo-private:App.Models.User.' . auth()->id() . ',NotificationRead' => 'handleNotificationRead'
        ];
    }

    #[On('refresh-notifications')]
    public function refreshNotifications()
    {
        // This method will be called when we need to refresh the notifications
        $this->dispatch('$refresh');
    }

    public function handleNewNotification($notification)
    {
        // Refresh the component when a new notification arrives
        $this->dispatch('$refresh');

        // Show a toast notification
        $this->dispatch('notify', [
            'message' => __('notifications.new_notification'),
            'type' => 'info'
        ]);
    }

    public function handleNotificationRead($notification)
    {
        $this->dispatch('$refresh');
    }

    #[Computed]
    public function unreadNotifications()
    {
        return auth()->user()->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function unreadCount()
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead(string $notificationId)
    {
        auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first()
            ->markAsRead();

        $this->dispatch('notification-read');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->showNotifications = false;
        $this->dispatch('all-notifications-read');
    }

    public function render()
    {
        return view('livewire.notifications.notifications-list');
    }
}
