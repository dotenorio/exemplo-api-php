<?php
  $dir = 'rotas';

  if ($handle = opendir($dir)) {
    echo "<h1>Rotas:</h1>";
    echo "<ul>";
    echo "  <li>rotas/excluir.php</li>";
    echo "  <li>rotas/ler.php</li>";
    echo "  <li>rotas/atualizar.php</li>";
    echo "  <li>rotas/criar.php</li>";
    echo "</ul>";

    closedir($handle);
  }