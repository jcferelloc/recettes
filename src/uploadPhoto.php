<?php
include 'connect.php';
$connection = connect();

if ( !isset($_POST["name"]) || ( substr($_POST["name"], 0, 4) !== "img_" ) ){
    die;
}

if ( !isset($_POST["recetteID"]) ){
    die;
}
$target_file = "upload/" . substr($_POST["name"], 4 ) . "_" . $_POST["recetteID"] . ".jpg";

$uploadOk = 0;
// Check if image file is a actual image or fake image
if(isset($_FILES) && count($_FILES['file'] > 0)) {
    
    $uploadOk = 1;
    /*
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image." . $_FILES["file"]["name"];
        $uploadOk = 0;
    }
    */
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    logActivity($connection,"upload photo error ". $_FILES["file"]["name"] . "(" . $target_file .")");
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
        logActivity($connection,"upload photo ". $_FILES["file"]["name"] . "(" . $target_file .")");
    } else {
        echo "Sorry, there was an error uploading your file.";
        logActivity($connection,"upload photo error (not moved)". $_FILES["file"]["name"] . "(" . $target_file .")");
    }
}
?>