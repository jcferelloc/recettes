<?php
$params = $_POST;
if (sizeof($_POST)==0){
	$params = $_GET;
}

$login = htmlspecialchars($params["login"]);
$passwordRow = htmlspecialchars($params["password"]);
$password = substr($passwordRow,4,4) . "-" . substr($passwordRow,2,2) . "-" . substr($passwordRow,0,2);

include 'connect.php';
$connection = connect();
$return = new stdClass;
$return->{'status'}=false;


$query = "SELECT * FROM `". getTable("users") ."` WHERE login='".$login."' ";

$result = $connection->query( $query );

$pwdChecked=false;

while($row = $result->fetch_assoc())
{
	if ( count($row) == 0 ){
		continue;
	}
	if ( $password == $row["date_1"] ){
		$pwdChecked = true ;
		break;
	}
}

if ( $pwdChecked ){
	$return->{'status'}=true;
	foreach($row as $key => $value){
		$return->{$key}=$value;
	}
}
echo json_encode($return);

?> 