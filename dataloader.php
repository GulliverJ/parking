<?php
  $server_name = "localhost";
  $db_name = "orangeparking";
  $db_username = "parking";
  $db_password = "parking";  

  try {
    $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );


    $id = isset( $_GET['id'] ) ? $_GET['id'] : "10"; // Application field isn't required

    // Retrieve the current max sensor_id belonging to this user
    $sql_statement = $connection->prepare('SELECT occupied, restricted FROM bay_data_view WHERE bay_id = ?');
    $sql_statement->execute( array($id));

    $results = $sql_statement->fetchAll();

    } catch(Exception $e) {
      die(var_dump($e));
    }
    echo '[';
    foreach($results as $row) {
      echo '{';
      echo '"occupied": ' . ($row[0] ? 'true' : 'false') . ',';
      echo '"restricted": "' . $row[1] . '"';
      echo '}';
    }
    echo ']';


/*
  require 'connect.php';
  header('Content-Type: application/json');
  $id = 10;
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