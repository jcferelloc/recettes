<?php

include 'parser.php';
require "modelExpander.php";

$model = getExpandedModel("modeles/modelExample.book");
//var_dump($model);

if (isset($_GET["nbPage"])){
    echo count($model->{"pages"});
}if (isset($_GET["summary"])){
    $idxPage=0;
    echo '[';
    foreach ($model->{"pages"} as $page){
        echo " { ";
        if ( isset($page->{"elements"})){
            $idxElement=0;
            foreach ($page->{"elements"} as $element ){
                if ( $element->{'type'} == "property"){
                    if ( $idxElement != 0){
                        echo ",";
                    }
                    echo "\"" . $element->{'name'} ."\":" ."\"" . $element->{'value'} ."\"";
                    
                }
                $idxElement++;
            }
        }
        echo "}"; 
        if ( $idxPage != count($model->{"pages"})-1 ){
            echo ",";
        }
        $idxPage++;
    }
    echo ']';
    count($model->{"pages"});
} else {
    $number = htmlspecialchars($_GET["number"]);
    $format = htmlspecialchars($_GET["format"]);
    if ($format == "html"){
        echo pageHTML($model, $number);
    } 
}

?>