<?php namespace WilliGant\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * @package WilliGant\Providers
 */
class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'WilliGant\Services\Registrar'
        );

        $this->registerLastFm();
    }

    /**
     * Sets the API key and secret for the last fm client
     *
     * @author Will
     */
    private function registerLastFm()
    {
        $this->app->bind('LastFmClient\Client', function () {
            $auth = new \LastFmClient\Auth();
            $auth->setApiKey(config('services.lastfm.api_key'));
            $auth->setSecret(config('services.lastfm.secret'));

            $transport = new \LastFmClient\Transport\Curl();

            $client = new \LastFmClient\Client($auth, $transport);

            return $client;
        });

        $this->app->bind('LastFmClient\Service\User', function () {
            $userService = new \LastFmClient\Service\User();
            $userService->setClient($this->app->make('LastFmClient\Client'));

            return $userService;

        });
    }

}
