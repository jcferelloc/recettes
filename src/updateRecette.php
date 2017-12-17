<?php

$params = $_POST;
if (sizeof($_POST)==0){
	$params = $_GET;
}
var_dump($_POST);
var_dump($_GET);
var_dump($params);

$action = htmlspecialchars($params["action"]);

if ( $action != "delete"){
    $id = htmlspecialchars($params["id"]);
    $nom = htmlspecialchars($params["nom"]);
    $categorie = htmlspecialchars($params["categorie"]);
    $titre = htmlspecialchars($params["titre"]);
    $presentation = htmlspecialchars($params["presentation"]);
    $ingredients = htmlspecialchars($params["ingredients"]);
    $preparation = htmlspecialchars($params["preparation"]);
    $indications = htmlspecialchars($params["indications"]);
}

include 'connect.php';
$connection = connect();

if ( $action == "new" ){
    $query = "INSERT INTO `". getTable("recettes") ."` " ;
    $query .= " ( id, nom, categorie, titre, presentation, ingredients, preparation, indications, url_plat, url_chef) ";
    $query .= " VALUES (";
    $query .= "'" . $id . "', ";
    $query .= "'" . $nom . "', ";
    $query .= "'" . $categorie . "', ";
    $query .= "'" . $titre . "', ";
    $query .= "'" . $presentation . "', ";
    $query .= "'" . $ingredients . "', ";
    $query .= "'" . $preparation . "', ";
    $query .= "'" . $indications . "',";
    $query .= "'upload/plat_" . $id . ".jpg',";
    $query .= "'upload/chef_" . $id . ".jpg'";
    $query .= ");" ;

    $result = $connection->query( $query );
}else if ($action == "delete" ){
    $query = "DELETE FROM `". getTable("recettes") ."` " ;
    $query .= "WHERE id = '" . $id . "';";

    $result = $connection->query( $query );
}else{
    $query = "UPDATE `". getTable("recettes") ."` " ;
    $query .= " SET ";
    $query .= "nom = '" . $nom . "', ";
    $query .= "categorie = '" . $categorie . "', ";
    $query .= "titre = '" . $titre . "', ";
    $query .= "presentation = '" . $presentation . "', ";
    $query .= "ingredients = '" . $ingredients . "', ";
    $query .= "preparation = '" . $preparation . "', ";
    $query .= "indications = '" . $indications  . "' ";
    $query .= "WHERE id = " . $id . ";";

    $result = $connection->query( $query );
}
if ( ! $result ){
	print $connection->error . " : " . $query ;
}
echo 'done';
?>


