<?php

namespace App\Console\Commands;

use App\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as Cache;
class CacheTicketStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache the ticket statistics.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Cache $cache)
    {
        $this->info('Calculating stats >>>>>');
        $expiresAt = Carbon::now()->addMinutes(10);
        $tickets = Ticket::withTrashed()->get();
        $openTickets = $tickets->filter(function($ticket) {
            return !$ticket->trashed();
        });
        $cache->put('tickets.open',  $openTickets->count(), $expiresAt);
        $cache->put('tickets.total',  $tickets->count(), $expiresAt);
        $this->info('Calculation done and results are cached');
    }
}
