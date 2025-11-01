<?php

namespace RaifuCore\Storage;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishesMigrations([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ]);
    }
}
