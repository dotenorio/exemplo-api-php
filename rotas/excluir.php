<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod !== 'DELETE') include('../lib/405.php');

  include('../lib/db.php');

  $id = $_GET['id'] ?? '';

  if (!$id) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');  
    echo json_encode([
      'status' => 'notok',
      'errors' => [
        'id' => "Campo 'id' é obrigatório."
      ]
    ]);
    die();
  }

  try {
    $st = $db->prepare("
      DELETE
      FROM users
      WHERE id = ?
    ");

    $st->execute([
      $id
    ]);

    if ($st->rowCount() == 0) {
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
    'message' => "Usuário com id '$id' excluido com sucesso."
  ]);