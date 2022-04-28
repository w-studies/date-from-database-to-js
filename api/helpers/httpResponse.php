<?php
  /**
   * @param int $code
   * @param string|null $message
   * @return void
   */
  function jsonResponse(string|array $data = null, int $code = 200): void
  {
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);

    // treat this as json
    header('Content-Type: application/json');

    $status = [
      200 => '200 OK',
      400 => '400 Bad Request',
      404 => '400 Not Found',
      422 => 'Unprocessable Entity',
      500 => '500 Internal Server Error'
    ];

    // ok, validation error, or failure
    header('Status: ' . $status[$code]);

    // return the encoded json
    die(json_encode($data));
  }
