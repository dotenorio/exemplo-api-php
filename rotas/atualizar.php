<?php
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod !== 'PUT') include('../lib/405.php');

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

  $body = file_get_contents("php://input");
  $body = json_decode($body);

  $set = [];
  $options = [];

  if ($body->name) {
    array_push($set, 'name = ?');
    array_push($options, $body->name);
  }

  if ($body->email) {
    array_push($set, 'email = ?');
    array_push($options, $body->email);
  }

  if ($body->password) {
    array_push($set, 'password = ?');
    array_push($options, hash_hmac("sha256", $body->password, 'benchmark'));
  }

  if ($body->bio) {
    array_push($set, 'bio = ?');
    array_push($options, $body->bio);
  }

  if (sizeof($set) == 0) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');  
    echo json_encode([
      'status' => 'notok',
      'message' => 'Nenhum campo foi encontrado para atualização'
    ]);
    die();
  }

  $set = join(', ', $set);

  array_push($options, $id);
  
  try {
    $st = $db->prepare("
      UPDATE users
      SET $set
      WHERE id = ?;
    ");

    $st->execute($options);

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
      'errors' => $e->getMessage(),
      'st' => $st,
      'options' => $options
    ]);
    die();
  }

  header('HTTP/1.1 200 OK');
  header('Content-Type: application/json');  
  echo json_encode([
    'status' => 'ok',
    'message' => "Usuário com id '$id' atualizado com sucesso."
  ]);