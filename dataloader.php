<?php
require 'connect.php';
  header('Content-Type: application/json');
  $query = 'select bays.bay_id, occupied, nearest_unoccupied_bay, restrictions.max_stay, start, end from bay_data_view inner join bays on bay_data_view.bay_id = bays.bay_id inner join restrictions on bays.restriction_id = restrictions.restriction_id order by bay_data_view.bay_id asc';   
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
      echo '"id": ' . $row[0] . ',';
      echo '"occupied": ' . ($row[1] ? 'true' : 'false') . ',';
      echo '"nearest-unoccupied-bay": ' . $row[2] . ',';
      echo '"max-stay": "' . $row[3] . '",';
      echo '"start": "' . $row[4] . '",';
      echo '"end": "' . $row[5] . '"';
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