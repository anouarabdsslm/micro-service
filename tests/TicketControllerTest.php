<?php
use App\Ticket;
use App\Transformers\TicketTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TicketControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $ticketTransformer;
    private $zendesk;
    public function setUp()
    {
      parent::setUp();
      $this->ticketTransformer = $this->mock(TicketTransformer::class);
    }
      
    public function mock($class)
    {
      $mock = Mockery::mock($class);
      $this->app->instance($class, $mock);
      return $mock;
    }

    /** @test */
    public function it_fetch_tickets_as_json()
    {
        factory(Ticket::class, 5)->create();
        $this->ticketTransformer->shouldReceive('transformCollection')->once();
        $this->get('/v1/tickets')
             ->seeJson();
        $this->assertResponseStatus(200);
    }

   /** @test */
    public function it_fetch_single_ticket_as_json()
    {
        factory(Ticket::class, 5)->create();
        $this->ticketTransformer->shouldReceive('transform')->once();
        $this->get('/v1/tickets/3')
                ->seeJson();
        $this->assertResponseStatus(200);
    }

   /** @test */
    public function it_add_new_ticket_and_return_success()
    {
        $ticket = [
                "name" => "Anouar",
                "email" => "dtekind@gmail.com",
                'subject' =>"Test for add a ticket",
                'body' =>"Still in my way to learn more about testing :D"
            ];
        $this->post('/v1/tickets', $ticket)
             ->seeJsonEquals([
                 'status' => 'success'
             ]);
        $this->assertResponseStatus(200);
    }
    public function it_remove_single_ticket()
    {
        $this->delete('/v1/tickets/3')
             ->seeJsonEquals([
                'status' => 'success',
             ]);
        $this->assertResponseStatus(200);
    }
}
