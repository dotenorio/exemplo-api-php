<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod !== 'GET') include('../lib/405.php');

  include('../lib/db.php');

  $whereId = '';

  $id = $_GET['id'] ?? '';

  if ($id) {
    $whereId = 'WHERE id = ?';
  }

  try {
    $st = $db->prepare("
      SELECT id, name, email, bio
      FROM users
      $whereId
      LIMIT 1000
    ");

    $st->execute([
      $id
    ]);

    $data = $st->fetchAll(PDO::FETCH_CLASS);

    if (sizeof($data) == 0) {
      header('HTTP/1.1 404 Not Found');
      header('Content-Type: application/json');  
      echo json_encode([
        'status' => 'notok',
        'message' => "Usuário com id '$id' não encontrado."
      ]);
      die();
    }  
  } catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');  
    echo json_encode([
      'status' => 'notok',
      'errors' => $e->getMessage()
    ]);
    die();
  }

  header('HTTP/1.1 200 OK');
  header('Content-Type: application/json');  
  echo json_encode([
    'status' => 'ok',
    'data' => $data
  ]);