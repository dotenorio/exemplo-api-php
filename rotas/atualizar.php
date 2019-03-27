<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod === 'PUT') {
    echo json_encode([
      '_timestamp' => time()
    ]);
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json');
  } else {
    echo "Cannot $requestMethod " . $_SERVER['SCRIPT_NAME'];
    header('HTTP/1.1 404 Not Found', true, 404);
  }