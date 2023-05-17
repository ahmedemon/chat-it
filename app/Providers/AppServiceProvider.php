<?php

namespace App\Providers;

use App\Models\Conversation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $conversations = Conversation::all();
        config([
            'conversations' => $conversations
        ]);
    }
}
