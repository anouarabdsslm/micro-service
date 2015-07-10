<?php

namespace App\Providers;

use App\Contracts\Ticket as TicketInterface;
use App\Ticket;
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
        //On the creation time add ticket to our Zendesk API ,
        //then set the zendesk ID
        Ticket::creating(function ($ticket) use($sendesk){
            //We may throw this to the queue
            $newTicket = $sendesk->create([
                "subject" => $ticket->subject,
                "comment" => [
                    "body" => $ticket->body
                ],
                "requester" => [
                    "name" => $ticket->name, 
                    "email" => $ticket->email
                ],
                'priority' => 'normal'
            ]);
            $ticket->zendesk_ticket_id = $newTicket->id;
        });
        Ticket::deleting(function ($ticket) use($sendesk){
            //We may throw this to the queue
            $sendesk->solved($ticket->zendesk_ticket_id);
        });

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
