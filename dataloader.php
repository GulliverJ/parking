<?php/*
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
  require 'connect.php';
  header('Content-Type: application/json');
  $query = 'select bay_id, occupied from bay_data order by bay_id asc';   
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
      echo "\"id\": {$row[0]},";
      echo '"occupied": ' . ($row[1] ? 'true' : 'false'); 
      echo '}';
    }
  }
  echo ']';
?>