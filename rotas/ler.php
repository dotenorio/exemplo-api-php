<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod === 'GET') {
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json');
    $i = 0;
    $string = '';
    while($i < 10000) {
      $string .= 'a';
      $i++;
    }
    echo json_encode([
      '_timestamp' => time(),
      'string' => $string
    ]);
  } else {
    header('HTTP/1.1 404 Not Found', true, 404);
    echo "Cannot $requestMethod " . $_SERVER['SCRIPT_NAME'];
  }