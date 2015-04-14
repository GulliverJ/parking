<?php
  require 'connect.php';
  header('Content-Type: application/json');

  $result = mysql_query($_GET['query']);
  echo '[';
  if (is_resource($result) && mysql_num_rows($result)) {
    $initial = true;
    while ($row = mysql_fetch_row($result)) {
      if (!$initial) {
        echo ',';
      }
      $initial = false;
      echo '{';
      echo '"occupied": ' . ({$row[0]} ? 'Yes' : 'No') . ',';
      echo '"duration": {$row[1]},';
      echo '"remaining": {$row[2]},';
      echo '"legal": ' . ({$row[3] ? 'Yes' : 'No'} . ',';
      echo '"restricted": ' . ({$row[4] ? 'Yes' : 'No'} . ',';
      echo '"max_stay": {$row[5]},';
      echo '"nearest_unoccupied": {$row[6]}';
      echo '}';
    }
  }
  echo ']';
?>