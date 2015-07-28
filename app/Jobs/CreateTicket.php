<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTicket extends Job implements SelfHandling, ShouldQueue
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
        $newTicket = $this->sendesk->create([
            "subject" => $this->ticket->subject,
            "comment" => [
                "body" => $this->ticket->body
            ],
            "requester" => [
                "name" => $this->ticket->name, 
                "email" => $this->ticket->email
            ],
            'priority' => 'normal'
        ]);
        $this->ticket->zendesk_ticket_id = $newTicket->id;
        $this->ticket->save();
    }
}
