<?php
  echo "Cannot $requestMethod " . $_SERVER['SCRIPT_NAME'];
  header('HTTP/1.1 405 Method Not Allowed', true, 405);
  exit;