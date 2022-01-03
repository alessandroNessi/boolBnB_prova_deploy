<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Auth::routes();

// Rotte area Admin non visibili se sloggati
Route::middleware('auth')->namespace('Admin')->name('admin.')->prefix('admin')->group(function() {
    Route::get('/', 'HomeController@index');
    Route::resource('/apartments', 'ApartmentController');
    Route::get('/users/edit', 'UserController@edit')->name('users.edit');
    Route::put('/users/{user}', 'UserController@update')->name('users.update');
    Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
    Route::get('/images/create/{id}', 'ImageController@create')->name('images.create');
    Route::post('/images/store/{id}', 'ImageController@store')->name('images.store');
    Route::delete('/images/{id}', 'ImageController@destroy')->name('images.destroy');
    Route::get('/statistics/{id}', 'StatisticController@index')->name('statistics');
    //braintree
    Route::get('/payment/{apartment_id}/{sponsorship_id}', 'PaymentsController@index')->name('sponsorships');
    Route::post('/payment/process/{apartment_id}/{sponsorship_id}', 'PaymentsController@process')->name('sponsorships.process');
    // Route::get('/', 'ApartmentController@index')->name('apartments.index');
    //reindirizzo le rotte /post su /PostController
    // Route::resource("posts","PostController");

    //show views statistics
    Route::get('/statistics/show/{id}/{month}', 'StatisticController@show')->name('statistics.show');
});

// rotte pubbliche
Route::get('/{any}', 'PageController@index')->where('any', '.*')->name('Guest');
Route::post('/messages/store/{id}', 'MessageController@store')->name('message.store');
// Route::get('/guest', 'PageController@index')->name('guest.index');
