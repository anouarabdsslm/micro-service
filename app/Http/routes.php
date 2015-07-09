<?php
Route::group(['prefix' => 'v1'], function() {
    get('statistics', 'TicketController@stats');
    resource('tickets', 'TicketController');
});