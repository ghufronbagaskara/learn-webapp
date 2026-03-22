<?php

namespace Tests;

class ExampleTest extends TestCase
{
  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_that_base_endpoint_returns_a_successful_response()
  {
    $this->get('/');

    $this->seeStatusCode(200);
    $this->seeJsonContains([
      'success' => true,
      'message' => 'Lumen E-Commerce API',
    ]);
  }
}
