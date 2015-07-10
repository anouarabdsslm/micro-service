<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use App\Ticket;
use App\Transformers\TicketTransformer;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Request;

class TicketController extends ApiController
{
    private $ticketTransformer;
    private $zendesk;
    function __construct(TicketTransformer $ticketTransformer) {
        $this->ticketTransformer = $ticketTransformer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 50);
        $tickets = Ticket::paginate($limit);
        return $this->respondPagination($tickets, [
                'tickets' => $this->ticketTransformer->transformCollection($tickets->all())
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //In real world only authenticated users allowed to create resource
        //lets go with simple validation (we In real world we will have dedicted service for this)
        if ( !$request->get('name') or !$request->get('email')
            or !$request->get('subject') or !$request->get('body'))
        {
            return $this->validationFails('Validation faild for ticket');
        }
        //Sometimes we return the new created resource 
        //For now let go with status info only 
        Ticket::create([
                "name" => $request->input('name'), 
                "email" => $request->input('email'),
                'subject' => $request->input('subject'),
                'body' => $request->input('body')
            ]);
        return $this->respondSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);

        if( ! $ticket)
        {
            return $this->responseNoteFound('Ticket does not exist');
        }
        return $this->respond([
                'ticket' => $this->ticketTransformer->transform($ticket->toArray())
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::whereId($id)->first();
        if( !$ticket)
        {
            return $this->responseNoteFound();
        }
        $ticket->delete();
        return $this->respondSuccess();
    }

    public function stats(Cache $cache)
    {
        if (! $cache->has('tickets.total') && ! $cache->has('tickets.open'))
        {
            return $this->responseNoteFound('No stats found');
        }
        return $this->respond([
                'tickets' => [
                    'open' => $cache->get('tickets.open') ,
                    'total' => $cache->get('tickets.total') 
                ]
            ]);
    }
}
