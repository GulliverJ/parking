<?php
  error_reporting(E_ALL);
  ini_set("log_errors", 1);
  ini_set('display_errors', 1);


  date_default_timezone_set('Europe/London');
/*
  header('Content-Type: application/json');
  $server_name = "localhost";
  $db_name = "orangeparking";
  $db_username = "parking";
  $db_password = "parking";  

  try {
    $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $time = isset( $_GET['time'] ) ? $_GET['time'] : "2015-01-01 00:00:00";
    $newtime = date("Y-m-d H:i:s", strtotime('-4 seconds'));
*/

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
    //$lastcheck = mktime(date("s")-3, date("i"), date("H"), date("d"), date("m"), date("Y"));
    //$testdate = "2015-04-16 12:15:00"; 
/*
    $sql_statement = $connection->prepare("SELECT bay_id FROM bay_data WHERE state_timespan >= ?");
    $sql_statement->execute( array($testdate) );
    $results = $sql_statement->fetchAll();
    $numrows = count($results);
    $inclause = '(';
    $count = 0;
    foreach($results as $row) {
      $count++;
      $inclause += '' . $row[0] . '';
      if($count < $numrows) {
        $inclause += ', ';
      }
    }
    $inclause += ')';

    $tester = '(1, 2, 3)';
*/
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