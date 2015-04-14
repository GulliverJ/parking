<?php
  require 'connect.php';
  header('Content-Type: application/json');
  $id = isset($_GET['id']) ? $_GET['id'] : "9";
  $query = 'SELECT occupied, restricted FROM bay_data_view WHERE bay_id = {$id}';   
  $result = mysql_query($query);
  echo '[';
  if (is_resource($result) && mysql_num_rows($result)) {
    $initial = true;
    while ($row = mysql_fetch_row($result)) {
      if (!$initial) {
        echo ',';
      }
      $initial = false;
      echo '{';
      echo '"occupied": ' . ($row[0] ? 'true' : 'false') . ',';
      echo '"restricted": "' . $row[1] . '"';
      echo '}';
    }
  }
  echo ']';
/*

  require 'connect.php';
  header('Content-Type: application/json');

  //$id = isset( $_GET['id'] ) ? $_GET['id'] : "10";
  $query = 'SELECT occupied FROM bay_data WHERE bay_id = 10';
  $result = mysql_query($query);
  echo '[';
  
  if (is_resource($result) && mysql_num_rows($result)) {
    $initial = true;
    while ($row = mysql_fetch_row($result)) {
      if (!$initial) {
        echo ',';
      }
      $initial = false;
      echo '{';
      echo '"occupied": {$row[0]}';
      //echo '"duration": {$row[1]},';
      //echo '"remaining": {$row[2]},';
      //echo '"legal": ' . ($row[3] ? 'Yes' : 'No') . ',';
      //echo '"restricted": {$row[2]},';
      //echo '"max_stay": {$row[3]},';
      //echo '"nearest_unoccupied": {$row[4]}';
      echo '}';
    }
  }
  echo ']';
*/
?>