<?php 

namespace App\Services;
use App\Contracts;
use App\Contracts\Ticket;
use Zendesk\API\Client;
class ZendeskTicket implements Ticket
{
    private $zendesk;
    function __construct(Client $zendesk) {
        $this->zendesk = $zendesk;
    }
    public function create(array $data)
    {
        $result = $this->zendesk->tickets()->create($data);
        return $result->ticket;
    }
    public function solved($ticketId)
    {
        return $this->zendesk->tickets([$ticketId])
                            ->update(['status' => 'solved']);
    }
}