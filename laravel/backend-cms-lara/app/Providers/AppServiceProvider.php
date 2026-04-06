<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Survey;
use App\Models\User;
use App\Policies\PagePolicy;
use App\Policies\PostPolicy;
use App\Policies\SurveyPolicy;
use App\Policies\UserPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
    Gate::policy(Page::class, PagePolicy::class);
    Gate::policy(Post::class, PostPolicy::class);
    Gate::policy(Survey::class, SurveyPolicy::class);
    Gate::policy(User::class, UserPolicy::class);

    Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
