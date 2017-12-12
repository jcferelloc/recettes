<?php

include 'parser.php';
require "modelExpander.php";

$model = getExpandedModel("modeles/modelExample.book");
//var_dump($model);

if (isset($_GET["nbPage"])){
    echo count($model->{"pages"});
} else {
    $number = htmlspecialchars($_GET["number"]);
    $format = htmlspecialchars($_GET["format"]);
    if ($format == "html"){
        echo pageHTML($model, $number);
    } 
}

?>