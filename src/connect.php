<?php
$TABLE="";

function connect(){
	global $TABLES;
	if ( $_SERVER['SERVER_NAME'] == 'jc.ferelloc.free.fr' ){
		$sql["serveur"]='sql.free.fr'; //adresse de votre serveur ex:sql.free.fr
		$sql["login"]='jc.ferelloc'; //Nom d utilisateur vous permettant de vous connecter à votre base de donné
		$sql["pass"]='libellul'; //votre mot de passe
		$sql["base"]='jc.ferelloc'; //nom de votre base de donnés (le meme que le login chez free.fr)
/*
		$sql["connect"]=mysql_connect($sql["serveur"],$sql["login"],$sql["pass"])or die ("impossible de se connecter, réessayé plus tard");
    $sql["select_base"]=mysql_select_db($sql["base"],$sql["connect"])or die ("erreur de connexion base");
*/    
    $connection = new mysqli($sql["serveur"],$sql["login"],$sql["pass"], $sql["base"]);
		$TABLES["recettes"] = "ape2018_recettes";
    $TABLES["users"] = "ape2018_users";
    $TABLES["logs"] = "ape2018_logs";
    
	}
	else{
		/*$connection = mysql_connect("localhost","root");
		mysql_select_db("ape2018",$connection);*/
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
/*
function json_encode2($a=false)
{
  if (is_null($a)) return 'null';
  if ($a === false) return 'false';
  if ($a === true) return 'true';
  if (is_scalar($a))
  {
    if (is_float($a))
    {
      // Always use "." for floats.
      return floatval(str_replace(",", ".", strval($a)));
    }
    if (is_string($a))
    {
      static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
      return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
    }
    else
      return '"' . $a . '"' ;
  }
  $isList = true;
  for ($i = 0, reset($a); $i < count($a); $i++, next($a))
  {
    if (key($a) !== $i)
    {
      $isList = false;
      break;
    }
  }
  $result = array();
  if ($isList)
  {
    foreach ($a as $v) $result[] = json_encode2($v);
    return '[' . join(',', $result) . ']';
  }
  else
  {
    foreach ($a as $k => $v) $result[] = json_encode2($k).':'.json_encode2($v);
    return '{' . join(',', $result) . '}';
  }
}
*/

function logActivity($connection, $text){
  $login="";
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