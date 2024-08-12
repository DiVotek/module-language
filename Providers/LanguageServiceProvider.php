<?php

namespace Modules\Language\Providers;

use App;
use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'MultiLanguage';

    public function boot(): void
    {
    }

    public function register(): void {}

}
