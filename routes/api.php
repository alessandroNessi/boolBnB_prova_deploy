<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//returns all the apartments avaible
Route::get('/apartments', 'Api\ApartmentController@index')->name('api.index');

//returns the apartment with the given slug
Route::get('/apartments/{slug}', 'Api\ApartmentController@show')->name('api.apartment_show');

/*api/apartments/search/
1)&region=italia
2)&city=roma
3)&range=20
4)&rooms=[5-10]
5)&bathrooms=[1-2]
6)&guests=[1-6]
7)&sqm=[40-120]
8)&address=via roma 47
*/
Route::get('/apartments/search/{query}', 'Api\ApartmentController@search')->name('api.apartment_search');

//returns all the services avaible
Route::get('/services', 'Api\ServiceController@index')->name('api.services');

//returns the service associated with the given id
Route::get('/services/{id}', 'Api\ServiceController@getService')->name('api.get_service');

//returns the pic with the given id
Route::get('/pics/{id}', 'Api\PictureController@getApartmentImages')->name('api.get_images');

//stores views statistics
Route::post('/statistics/{id}/{ip}', 'Api\StatisticController@store')->name('api.store_views');