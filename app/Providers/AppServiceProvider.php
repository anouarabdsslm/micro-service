<?php

namespace App\Providers;

use App\Contracts\Ticket as TicketInterface;
use App\Jobs\CreateTicket;
use App\Jobs\SolveTicket;
use App\Ticket;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(TicketInterface $sendesk)
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
