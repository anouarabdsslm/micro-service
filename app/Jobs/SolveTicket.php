<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolveTicket extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $sendesk;
    private $ticket;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticket, $sendesk)
    {
        $this->ticket = $ticket;
        $this->sendesk = $sendesk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendesk->solved($this->ticket->zendesk_ticket_id);
    }
}
