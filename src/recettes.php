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
		$rowEncoded = array();
        foreach( $row as $rowEl){
            if ( gettype($rowEl) == "string"){
                $rowEncoded[] = utf8_encode($rowEl);
            }else{
                $rowEncoded[] = $rowEl;
            }
        }
        $recettes[] = $row;
	}	
} 
var_dump($recettes);
if (isset($_GET["js"])){
    echo json_encode($recettes);
}

?>