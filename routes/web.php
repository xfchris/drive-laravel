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
Route::get('/dashboard/json', 'Controller@getFilesJson');
Route::post('/dashboard/upload', 'Controller@postUploadFiles');
Route::post('/dashboard/eliminar', 'Controller@postEliminarArchivo');

//Cuenta de usuario y planes
Route::get('/cuenta', 'UserController@getIndex')->middleware('auth');
