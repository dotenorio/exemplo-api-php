<?php
  function required ($fields, $object) {
    $errors = [];
    foreach ($fields as $field) {
      if (!$object->$field) $errors[$field] = "Campo '$field' é obrigatório.";
    }
    return $errors;
  }
