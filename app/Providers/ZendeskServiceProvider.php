<?php

namespace App\Providers;

use App\Contracts\Ticket;
use App\Services\ZendeskTicket;
use Illuminate\Support\ServiceProvider;
use Zendesk\API\Client;
class ZendeskServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Ticket::class,function() {
            $client = new Client(getenv('ZENDESK_SUBDOMAIN'), getenv('ZENDESK_USERNAME'));
            $client->setAuth('token', getenv('ZENDESK_TOKEN'));
            return new ZendeskTicket($client);
        });
    }
}
