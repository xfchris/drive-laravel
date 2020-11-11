<?php
//Rutas de sesion con oauth
Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
Route::get('auth/{provider}', 'Auth\SocialAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');
Route::get('/salir', 'Auth\SocialAuthController@logout');

//Panel de archivos
Route::get('/', 'Controller@getIndex')->middleware('guest')->name('login');
Route::get('/dashboard', 'Controller@getDashboard')->middleware('auth');
Route::get('/dashboard/json', 'Controller@getFilesJson')->middleware('auth');
Route::post('/dashboard/upload', 'Controller@postUploadFiles')->middleware('auth');
Route::post('/dashboard/eliminar', 'Controller@postEliminarArchivo')->middleware('auth');
Route::get('/dashboard/descargar/{id}', 'Controller@getDescargarArchivo')->middleware('auth');


//Cuenta de usuario y planes
Route::get('/cuenta', 'UserController@getIndex')->middleware('auth');
Route::post('/cuenta', 'UserController@postIndex')->middleware('auth');

Route::get('/cuenta/planes', 'UserController@getPlan')->middleware('auth');
Route::post('/cuenta/planes', 'UserController@postPlan')->middleware('auth');
