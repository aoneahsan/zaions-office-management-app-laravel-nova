<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    protected $observers = [
        \App\Models\User::class => [\App\Observers\UserObserver::class],
        \App\Models\Attachment::class => [\App\Observers\AttachmentObserver::class],
        \App\Models\Task::class => [\App\Observers\TaskObserver::class],
        \App\Models\History::class => [\App\Observers\HistoryObserver::class],
        \App\Models\Comment::class => [\App\Observers\CommentObserver::class],
        \App\Models\Reply::class => [\App\Observers\ReplyObserver::class],
    ];
}
