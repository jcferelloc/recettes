<?php

include 'parser.php';
require "modelExpander.php";

$model = getExpandedModel("modeles/modelExample.book");

function getModelJS($model){
    $modelJS ="";
    $idxPage=0;
    $modelJS .= '[';
    foreach ($model->{"pages"} as $page){
        $modelJS .=  " { ";
        if ( isset($page->{"elements"})){
            $idxElement=0;
            foreach ($page->{"elements"} as $element ){
                if ( $element->{'type'} == "property"){
                    if ( $idxElement != 0){
                        $modelJS .=  ",";
                    }
                    $modelJS .=  "\"" . $element->{'name'} ."\":" ."\"" . $element->{'value'} ."\"";
                    
                }
                $idxElement++;
            }
        }
        $modelJS .=  "}"; 
        if ( $idxPage != count($model->{"pages"})-1 ){
            $modelJS .=  ",";
        }
        $idxPage++;
    }
    $modelJS .=  ']';

    return $modelJS;
}

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

    if ( isset($_GET["number"])){
        $number = htmlspecialchars($_GET["number"]);
        echo pageHTML($model, $number);
    } else {
        $idxPage=0;
       
        foreach ($model->{"pages"} as $page){
            echo pageHTML($model, $idxPage);
            echo "BOUNDARY--MODEL";
            $idxPage++;
        }
        echo "<script id='scriptModel'>";
        echo "model=" . getmodelJS($model);
        echo "</script>";
         

    }

}

?>