<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\Paginator;

class ApiController extends Controller
{
    const HTTP_NOT_FOUND = 404;
    const HTTP_BAD_REQUEST = 400;
    private $statusCode = 200;

    protected function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }    
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    protected function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondPagination(Paginator $tickets, $data)
    {
         $data = array_merge($data, [
                'paginator' => [
                    'total_count' => $tickets->total(),
                    'total_pages' => ceil($tickets->total() / $tickets->perPage()),
                    'current_page' => $tickets->currentPage(),
                    'limit' => $tickets->perPage(),
                ]
            ]);
         return $this->respond($data);
    }

    protected function respondWithError($message)
    {
        return $this->respond([
                    'error' => [
                        'message' => $message,
                        'status_code' => $this->getStatusCode()
                    ]
            ]);
    }

    protected function responseNoteFound($message = 'Resource not found')
    {
        return $this->setStatusCode(self::HTTP_NOT_FOUND)->respondWithError($message);
    }

    protected function validationFails($message = 'Validation faild')
    {
        return $this->setStatusCode(self::HTTP_BAD_REQUEST)->respondWithError($message);
    }

    protected function respondSuccess()
    {
        return $this->respond([
                'status' => 'success'
            ]);
    }

}