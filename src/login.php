<?php
$params = $_POST;
if (sizeof($_POST)==0){
	$params = $_GET;
}

$login = htmlspecialchars($params["login"]);
$password = htmlspecialchars($params["password"]);

include 'connect.php';
$connection = connect();


$query = "SELECT pwd FROM `". getTable("members") ."` WHERE id='".$login."' ";

$result = mysql_query( $query );

$pwdChecked=false;

while($row =mysql_fetch_assoc($result))
{
	if ( count($row) == 0 ){
		continue;
	}
	if ( $password == $row["pwd"] ){
		$pwdChecked = true ;
	}
}

if ( $pwdChecked ){
	setcookie( "MEMBERID", $login, time()+86400*320 );
	setcookie( "KOIJKLP", md5($password), time()+86400*320 );
	echo "Ok! " ;
}else{
	setcookie("MEMBERID", "", time()-3600);
	echo "bouh!";
}

?> 