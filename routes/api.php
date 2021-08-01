<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\File\File;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('mobile/register', 'AuthController@register');
Route::post('mobile/login', 'AuthController@login');
Route::post('mobile/send-message', 'MessageController@sendMessage');
Route::get('mobile/read-message/{from_id}/{to_id}', 'MessageController@read');
Route::get('mobile/list-user-message/{query}/{user_id}', 'MessageController@listChatUser');
Route::get('mobile/message/{user_id_auth}/{id_user}', 'MessageController@message');
Route::get('mobile/get-image/{image}', function ($image){
    return asset('storage/app/images_profile/'.$image);
});

Route::post('mobile/hafalan/murojaah', 'HafalanController@postRequestMurojaah');
Route::post('mobile/hafalan/ziyadah', 'HafalanController@postRequestZiyadah');
Route::get('mobile/get-last-request-murojaah/{target_id_user}', 'HafalanController@getLastRequestMurojaah');
Route::get('mobile/get-last-request-ziyadah/{target_id_user}', 'HafalanController@getLastRequestZiyadah');
Route::post('mobile/post-jurnal', 'HafalanController@postToJurnal');
Route::get('mobile/get-jurnal/{id_user}', 'JurnalController@getJurnal');
Route::post('mobile/post-acara', 'PostingController@postAcara');
Route::get('mobile/get-acara/{id_user}', 'PostingController@getAcara');
Route::get('mobile/delete-acara/{id_posting}', 'PostingController@deleteAcara');

Route::get('mobile/get-image/storage/{filename}', function ($filename)
{
    $url = storage_path().'/app/public/'.$filename;
    return $url;
});