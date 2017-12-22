<?php

$params = $_POST;
if (sizeof($_POST)==0){
	$params = $_GET;
}
/*
var_dump($_POST);
var_dump($_GET);
var_dump($params);
*/

function textTreatment($text){
    return utf8_decode (htmlspecialchars(str_replace("'","\\'",$text)));
}

$action = htmlspecialchars($params["action"]);

if ( $action != "delete"){
    $id = htmlspecialchars($params["id"]);
    $nom = textTreatment($params["nom"]);
    $categorie = htmlspecialchars($params["categorie"]);
    $titre = textTreatment($params["titre"]);
    $presentation = textTreatment($params["presentation"]);
    $ingredients = textTreatment($params["ingredients"]);
    $preparation = textTreatment($params["preparation"]);
    $indications = textTreatment($params["indications"]);
    $idPhotoPlat = htmlspecialchars($params["idPhotoPlat"]);
    $idPhotoChef = htmlspecialchars($params["idPhotoChef"]);
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
    $query .= "'" . $indications . "'";
    if ( $idPhotoPlat != "" ) $query .= ", 'upload/plat_" . $id . "_" . $idPhotoPlat. ".jpg'";
    if ( $idPhotoChef != "" ) $query .= ", 'upload/chef_" . $id . "_" . $idPhotoChef. ".jpg'";
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
    if ( $idPhotoPlat != "" ) $query .= ", url_plat = " . "'upload/plat_" . $id . "_" . $idPhotoPlat. ".jpg'";
    if ( $idPhotoChef != "" ) $query .= ", url_chef = " . "'upload/chef_" . $id . "_" . $idPhotoChef. ".jpg'";
    $query .= "WHERE id = " . $id . ";";
var_dump($query);
    $result = $connection->query( $query );
}
if ( ! $result ){
	print $connection->error . " : " . $query ;
}
echo 'done';
?>


