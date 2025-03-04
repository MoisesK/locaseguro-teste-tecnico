<?php

declare(strict_types=1);

namespace App\Providers;

use App\Shared\Infra\Services\NotificationApi\MockNotificationApi;
use App\Shared\Infra\Services\NotificationApi\NotificationApi;
use Illuminate\Support\ServiceProvider;
use App\Shared\Domain\Adapters\Contracts\LogSystem;
use App\Shared\Infra\Adapters\LogSystem\LaravelLog;
use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Infra\Services\ProposalApi\ProposalApi;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;
use App\Shared\Infra\Services\ProposalApi\MockProposalApi;
use App\Proposal\Infra\Repository\ProposalEloquentRepository;
use App\Shared\Domain\Adapters\MessageBroker\RabbitMqAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository
        $this->app->singleton(ProposalRepository::class, ProposalEloquentRepository::class);

        // Adapters
        $this->app->singleton(MessageBroker::class, RabbitMqAdapter::class);
        $this->app->singleton(LogSystem::class, LaravelLog::class);

        // Apis
        $this->app->singleton(ProposalApi::class, MockProposalApi::class);
        $this->app->singleton(NotificationApi::class, MockNotificationApi::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
