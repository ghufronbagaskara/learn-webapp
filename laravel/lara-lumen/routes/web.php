<?php // routes/web.php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
  return response()->json([
    'success' => true,
    'message' => 'Lumen E-Commerce API',
    'data' => [
      'version' => $router->app->version(),
    ],
  ], 200);
});

$router->group(['prefix' => 'api'], function () use ($router) {
  $router->post('auth/register', 'AuthController@register');
  $router->post('auth/login', 'AuthController@login');

  $router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('auth/profile', 'AuthController@profile');
    $router->post('auth/logout', 'AuthController@logout');

    $router->post('orders', 'OrderController@store');
    $router->get('orders', 'OrderController@index');
    $router->get('orders/{id}', 'OrderController@show');

    $router->post('payments', 'PaymentController@process');
    $router->get('payments/{order_id}', 'PaymentController@show');
  });

  $router->get('products', 'ProductController@index');
  $router->get('products/{id}', 'ProductController@show');

  $router->group(['middleware' => ['auth', 'role:admin']], function () use ($router) {
    $router->post('products', 'ProductController@store');
    $router->put('products/{id}', 'ProductController@update');
    $router->delete('products/{id}', 'ProductController@destroy');
  });
});
