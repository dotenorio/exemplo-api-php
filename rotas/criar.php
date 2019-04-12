<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod !== 'POST') include('../lib/405.php');

  include('../lib/db.php');
  include('../lib/validate.php');

  $body = file_get_contents("php://input");
  $body = json_decode($body);

  $errors = required(['name', 'email', 'password', 'bio'], $body);

  if (sizeof($errors) !== 0) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');  
    echo json_encode([
      'status' => 'notok',
      'errors' => $errors
    ]);
    die();
  }

  try {
    $st = $db->prepare("
      INSERT INTO users (name, email, password, bio)
      VALUES (?, ?, ?, ?)
    ");

    $st->execute([
      $body->name,
      $body->email,
      hash_hmac("sha256", $body->password, 'benchmark'),
      $body->bio
    ]);

    $body->id = $db->lastInsertId();
  } catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');  
    echo json_encode([
      'status' => 'notok',
      'errors' => $e->getMessage()
    ]);
    die();
  }

  unset($body->password);

  header('HTTP/1.1 200 OK');
  header('Content-Type: application/json');  
  echo json_encode([
    'status' => 'ok',
    'data' => $body
  ]);
  