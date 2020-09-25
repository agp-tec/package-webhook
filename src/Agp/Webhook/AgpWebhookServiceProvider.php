<?php

namespace Agp\Webhook;

use Illuminate\Support\ServiceProvider;

class AgpWebhookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/webhook.php' => config_path('webhook.php'),
        ], 'config');
    }

    public function register()
    {
    }
}
