<?php

namespace Origami\Support;

use Illuminate\Support\ServiceProvider;
use Origami\Support\Console\RepositoryMakeCommand;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositoryMakeCommand();

        $this->commands([
            RepositoryMakeCommand::class,
        ]);
    }

    public function boot()
    {
        $this->app->singleton(Filter::class, function ($app) {
            return new Filter(
                $app['request'],
                $app['url']
            );
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRepositoryMakeCommand()
    {
        $this->app->singleton(RepositoryMakeCommand::class, function ($app) {
            return new RepositoryMakeCommand($app['files']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            RepositoryMakeCommand::class
        ];
    }
}
