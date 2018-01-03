<?php
$TABLE="";

function connect(){
	global $TABLES;
	if ( $_SERVER['SERVER_NAME'] == 'jc.ferelloc.free.fr' ){
		$sql["serveur"]='sql.free.fr'; //adresse de votre serveur ex:sql.free.fr
		$sql["login"]='jc.ferelloc'; //Nom d utilisateur vous permettant de vous connecter à votre base de donné
		$sql["pass"]='libellul'; //votre mot de passe
		$sql["base"]='jc.ferelloc'; //nom de votre base de donnés (le meme que le login chez free.fr)

    $connection = new mysqli($sql["serveur"],$sql["login"],$sql["pass"], $sql["base"]);
		$TABLES["recettes"] = "ape2018_recettes";
    $TABLES["users"] = "ape2018_users";
    $TABLES["logs"] = "ape2018_logs";
    
	}
	else{
    $connection = new mysqli("localhost","root","", "ape2018");
		$TABLES["recettes"] = "recettes";
    $TABLES["users"] = "users";
    $TABLES["logs"] = "logs";
  }
	return $connection;
}

function getTable($nom){
	global $TABLES;
	return  $TABLES[$nom];
}

function logActivity($connection, $text, $pLogin=""){
  $login="";
  if ( isset($pLogin)){
    $login = $pLogin;
  }
  if ( isset($_COOKIE["id"]) ){
    $login = $_COOKIE["id"];
  }
  $query = "INSERT INTO `". getTable("logs") ."` " ;
  $query .= " ( log, ip ";
  if ( $login != "" ) $query .= ", login";
    
    $query .= " ) VALUES (";
    $query .= "'" . $text . "', ";
    $query .= "'" . $_SERVER["REMOTE_ADDR"] . "' ";
    if ( $login != "" ) $query .= ", '$login'";
    $query .= ");" ;

    $result = $connection->query( $query );
}
?>