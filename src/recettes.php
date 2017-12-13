<?php

include 'connect.php';
$connection = connect();


$query = "SELECT * FROM `". getTable("recettes") ."`;" ;

$result = $connection->query( $query );




$recettes = array();


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc())
	{
		if ( count($row) == 0 ){
			continue;
		}
		$rowEncode = array();
    foreach( $row as $key => $value){
        $rowEncode[$key] = utf8_encode($value);
    }
	$recettes[] = $rowEncode;
	}	
} 
//var_dump($recettes);
if (isset($_GET["js"])){
    echo json_encode($recettes);
}

?>