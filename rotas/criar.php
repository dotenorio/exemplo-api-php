<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod !== 'POST') {
    include('../lib/405.php');
  }

  echo json_encode([
    '_timestamp' => time()
  ]);
  header('HTTP/1.1 200 OK');
  header('Content-Type: application/json');
  