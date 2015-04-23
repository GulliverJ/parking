<?php
/*

  $server_name = "localhost";
  $db_name = "orangeparking";
  $db_username = "parking";
  $db_password = "parking";  

  try {
    $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $id = isset( $_GET['id'] ) ? $_GET['id'] : "0";

    $sql_statement = $connection->prepare('SELECT v.occupied, v.state_time, v.time_remaining, v.max_stay, v.restricted, v.legally_occupied, v.nearest_unoccupied_bay, r.start, r.end, r.hourly_charge FROM bay_data_view v INNER JOIN bays b ON v.bay_id = b.bay_id INNER JOIN restrictions r ON b.restriction_id = r.restriction_id WHERE v.bay_id = ?');
    $sql_statement->execute( array($id));

    $results = $sql_statement->fetchAll();

    } catch(Exception $e) {
      die(var_dump($e));
    }

*/
// The message
$message = "Notice:\r\nYour vehicle is currently illegally parked.\r\n";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Send
mail('gully.johnson@gmail.com', 'Warning from Orange Parking', $message);


?>