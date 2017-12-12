<?php
include 'connect.php';
$connection = connect();


$query = "SELECT * FROM `". getTable("users") ."`;" ;

$result = $connection->query( $query );


$users = array();


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc())
	{
		if ( count($row) == 0 ){
			continue;
		}
		$users[] = $row;
	}	
} 



if (isset($_GET["js"])){
    echo json_encode($users);
}
$connection->close();
?>