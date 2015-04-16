<?php
  error_reporting(E_ALL);
  ini_set("log_errors", 1);
  ini_set('display_errors', 1);

  header('Content-Type: application/json');
  $server_name = "localhost";
  $db_name = "orangeparking";
  $db_username = "parking";
  $db_password = "parking";  

  try {
    $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $time = isset( $_GET['time'] ) ? $_GET['time'] : "2015-01-01 00:00:00";
    $newtime = date("Y-m-d H:i:s", $time);

    $sql_statement = $connection->prepare("SELECT b.bay_id, v.occupied, nearest_unoccupied_bay, r.max_stay, start, end, legally_occupied, d.state_timespan FROM bay_data_view v INNER JOIN bays b ON v.bay_id = b.bay_id INNER JOIN restrictions r ON b.restriction_id = r.restriction_id INNER JOIN bay_data d ON d.bay_id = v.bay_id WHERE d.state_timespan > '?' ORDER BY v.bay_id asc");
    $sql_statement->execute( array($newtime));

    $results = $sql_statement->fetchAll();

  } catch(Exception $e) {
      die(var_dump($e));
  }
  $count = 0;
  echo '[';
  foreach($results as $row) {
    $count++;
    echo '{';
    echo '"id": ' . $row[0] . ',';
    echo '"occupied": ' . ($row[1] ? 'true' : 'false') . ',';
    echo '"nearest-unoccupied-bay": ' . $row[2] . ',';
    echo '"max-stay": "' . $row[3] . '",';
    echo '"start": "' . $row[4] . '",';
    echo '"end": "' . $row[5] . '",';
    echo '"legal": "' . $row[6] . '"';
    echo '}';
    if($results->rowCount() > $count) {
      echo ', ';
    }
  }
  echo ']';
?>