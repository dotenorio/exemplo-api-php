<?php
  $dir = 'funcoes/';

  if ($handle = opendir($dir)) {
    echo "<h1>Funções:</h1>";

    echo "<ul>";
    while (false !== ($file = readdir($handle))) {
      if ($file !== '.' && $file !== '..') {
        echo "<li><a href='$dir$file'>$file</a></li>";
      }
    }
    echo "<ul>";

    closedir($handle);
  }