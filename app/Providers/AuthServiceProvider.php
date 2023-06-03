<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        // Default Folder Models Policies
        'App\Models\Default\Attachment' => 'App\Policies\Default\AttachmentPolicy',
        'App\Models\Default\Comment' => 'App\Policies\Default\CommentPolicy',
        'App\Models\Default\Reply' => 'App\Policies\Default\ReplyPolicy',
        // FPI Folder Models Policies
        'App\Models\FPI\Project' => 'App\Policies\FPI\ProjectPolicy',
        'App\Models\FPI\ProjectTransaction' => 'App\Policies\FPI\ProjectTransactionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
