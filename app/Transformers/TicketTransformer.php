<?php 
namespace App\Transformers;

use App\Transformers\Transformer;

class TicketTransformer extends Transformer
{
    public function transform($item)
    {
        return [
            'id' => (int) $item['id'],
            'name' => $item['name'],
            'email' => $item['email'],
            'subject' => $item['subject'],
            'body' => $item['body'],
            'zendesk_ticket_id' => $item['zendesk_ticket_id']
        ];
    }
}