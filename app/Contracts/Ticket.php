<?php 
namespace App\Contracts;
interface Ticket {
    /*
        Create the ticket and return the ticket ID
     */
    public function create(array $ticket);
    public function solved($ticketId);
}