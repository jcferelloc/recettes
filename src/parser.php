<?php

include "formatSheet.php";


//$modelJS = file_get_contents("modeles/modelExample.book");

//var_dump( $modelJS );

//$model = json_decode ($modelJS);
//echo json_last_error();
//var_dump($model);
//var_dump($model->{'pages'}[0]);
//var_dump($model->{'pages'});
//error_log ( string $message [, int $message_type = 0 [, string $destination 


function pageHTML($model, $number){
    global  $formatSheet;
    
    $page = $model->{'pages'}[$number];

    if ( $model->{'orientation'} == "L"){
        $width = $formatSheet[$model->{'format'}][1];
        $height = $formatSheet[$model->{'format'}][0];
    } else {
        $width = $formatSheet[$model->{'format'}][0];
        $height = $formatSheet[$model->{'format'}][1];
    }
    $background = "background-color:#FFFFFF";
    if ( isset($page->{'background-color'})){
        $background = "background-color:".$page->{'background-color'};
    }
    if ( isset($page->{'background-img'})){
        $background = "background-image: url('".$page->{'background-img'} ."'); background-size:100% 100%;";
    }

    $font = "font-family:Arial;";
    if ( isset($model->{'font'})){
        $font = "font-family:".$model->{'font'} .";";
    }
    if ( isset($page->{'font'})){
        $font = "font-family:".$page->{'font'} .";";
    }
    if ( isset($page->{'background-img'})){
        $background = "background-image: url('".$page->{'background-img'} ."'); background-size:100% 100%;";
    }
    $html = "<div style=\"width:" . $width . "mm; height:" . $height . "mm; " . $background . ";" . $font ."\">";
    if ( isset($page->{'elements'})){
        foreach ($page->{"elements"} as $element ){
            $html .= elementHTML($element);
        }
    }
    $html .= "</div>";
    return $html;
}

function elementHTML($element){
    switch ( $element->{'type'}){
        case "text":
        return textHTML($element);
        case "image":
        return imageHTML($element);
    }
}

function textHTML($element){
    $value="";
    $align="";
    $left="left:0mm;";
    $top="top:0mm;";
    $width="";
    $fontsize="";
    $color="";
    $height="";
    

    if ( isset($element->{'value'})){
        $value = $element->{'value'};
    }
    if ( isset($element->{'align'})){
        $align = "text-align:" . $element->{'align'} ."; ";
    }
    if ( isset($element->{'left'})){
        $left = "left:" . $element->{'left'} ."mm; ";
    }
    if ( isset($element->{'top'})){
        $top = "top:" . $element->{'top'} ."mm; ";
    }
    if ( isset($element->{'width'})){
        $width = "width:" . $element->{'width'} ."mm; ";
    }
    if ( isset($element->{'height'})){
        $width = "height:" . $element->{'height'} ."mm; ";
    }
    if ( isset($element->{'fontsize'})){
        $fontsize = "font-size:" . $element->{'fontsize'} ."pt; ";
    }
    if ( isset($element->{'color'})){
        $color = "color:" . $element->{'color'} ."; ";
    }
    
    $html = "<div style=\"position:absolute; ".  $align. $left . $top . $width . $fontsize . $color ."\">";
    $html .= $value;
    $html .= "</div>";
    return $html;
}

function imageHTML($element){
    $url="";
    $left="left:0mm;";
    $top="top:0mm;";
    $width="";
    

    if ( isset($element->{'url'})){
        $url = $element->{'url'};
    }
    if ( isset($element->{'left'})){
        $left = "left:" . $element->{'left'} ."mm; ";
    }
    if ( isset($element->{'top'})){
        $top = "top:" . $element->{'top'} ."mm; ";
    }
    if ( isset($element->{'width'})){
        $width = "width:" . $element->{'width'} ."mm; ";
    }
    
    
    $html = "<img src=\"" . $url . "\" style=\"position:absolute; ".   $left . $top . $width ."\">";

    return $html;


}

?>