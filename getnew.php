<?php
  error_reporting(E_ALL);
  ini_set("log_errors", 1);
  ini_set('display_errors', 1);
  date_default_timezone_set('Europe/London');

  header('Content-Type: application/json');
  $server_name = "localhost";
  $db_name = "orangeparking";
  $db_username = "parking";
  $db_password = "parking";  

  try {
    $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    // Set a date to check from - the AJAX should update very 3 seconds, so let's check from the past 4 seconds to be safe
    $init = isset( $_GET['initial'] ) ? $_GET['initial'] : 1;
    $newtime = $init == 1 ? date("Y-m-d H:i:s", mktime(0, 0, 0, 4, 10, 2015)) : date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s")-4, date("m"), date("d"), date("Y")));

    // Statement will return the data for rows ONLY if they have updated since the last check
    $sql_statement = $connection->prepare("SELECT b.bay_id, v.occupied, nearest_unoccupied_bay, r.max_stay, start, end, legally_occupied FROM bay_data_view v INNER JOIN bays b ON v.bay_id = b.bay_id INNER JOIN restrictions r ON b.restriction_id = r.restriction_id INNER JOIN bay_data d ON d.bay_id = v.bay_id WHERE d.state_timespan >= ? ORDER BY v.bay_id asc");
    $sql_statement->execute( array($newtime) );

    $results = $sql_statement->fetchAll();
    $numrows = count($results);

  } catch(Exception $e) {
      die(var_dump($e));
  }
  $count = 0;
  echo '[';
  foreach($results as $row) {
    $count = $count + 1;
    echo '{';
    echo '"id": "' . $row[0] . '",';
    echo '"occupied": ' . ($row[1] ? 'true' : 'false') . ',';
    echo '"nearest-unoccupied-bay": "' . $row[2] . '",';
    echo '"max-stay": "' . $row[3] . '",';
    echo '"start": "' . $row[4] . '",';
    echo '"end": "' . $row[5] . '",';
    echo '"legal": "' . $row[6] . '"';
    echo '}';
    if($numrows > $count) {
      echo ', ';
    }
  }
  echo ']';
?>