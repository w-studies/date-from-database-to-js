<?php
  /**
   * @param string|array|null $data
   * @param int $code
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

    if ($code !== 200) {
      // guarda os dados do backtrace
      $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

      if (is_array($data)) {
        $data['debug'] = $debug;
      } else {
        $data .= "<small class='text-secondary'><b>FILE</b>: $debug[file], <b>LINE</b>: $debug[line]<small>";
      }
    }
    // return the encoded json
    die(is_array($data) ? json_encode($data) : '"' . $data . '"');
  }
