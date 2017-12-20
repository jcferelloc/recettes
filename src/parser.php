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
    //var_dump($element);
    switch ( $element->{'type'}){
        case "text":
        return textHTML($element);
        case "image":
        return imageHTML($element);
        case "property":
        return propertyHTML($element);
        case "list":
        return listHTML($element);
    }
}

function propertyHTML($element){
    $value="";
    $name="";
    if ( isset($element->{'name'})){
        $name = $element->{'name'};
    }
    if ( isset($element->{'value'})){
        $value = $element->{'value'};
    }
    $html = "<div style=\"display:none;\" id=\"".  $name . "\">";
    
    $html .= $value;
    $html .= "</div>";
    return $html;
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
    $name="";
    $class="";
    

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
    if ( isset($element->{'name'})){
        $name = " id=\"" . $element->{'name'} ."\" ";
    }
    if ( isset($element->{'class'})){
        $class = " class=\"" . $element->{'class'} ."\" ";
    }
    
    $html = "<div " . $class . $name . " style=\"position:absolute; ".  $align. $left . $top . $width . $fontsize . $color ."\">";
    
    $html .= preg_replace("/\r\n/","<br>",$value);
    $html .= "</div>";
    return $html;
}

function imageHTML($element){
    $url="";
    $left="left:0mm;";
    $top="top:0mm;";
    $width="";
    $name="";

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
    if ( isset($element->{'name'})){
        $name = " id=\"" . $element->{'name'} ."\" ";
    }
    
    
    $html = "<img " . $name . " src=\"" . $url . "\" alt=\"Pas de photo.\" style=\"position:absolute; ".   $left . $top . $width ."\" onerror=\"if (handleImgError) handleImgError(this);\">";

    return $html;
}

function listHTML($element){
    $fontsizeList="";
    $topList=0;
    $leftList=0;
    $colorList="";
    $heightList=0;
    $alignList= " text-align:left; ";
    $classList = "";

    if ( isset($element->{'fontsize'})){
        $fontsizeList =  " font-size:" . $element->{'fontsize'} ."pt; ";
    }
    if ( isset($element->{'top'})){
        $topList = $element->{'top'};
    }
    if ( isset($element->{'left'})){
        $leftList = $element->{'left'};
    }
    if ( isset($element->{'color'})){
        $colorList = "color:" . $element->{'color'} ."; ";
    }
    if ( isset($element->{'height'})){
        $heightList = $element->{'height'};
    }
    if ( isset($element->{'align'})){
        $alignList =  " text-align:" . $element->{'align'} ."; ";
    }
    if ( isset($element->{'class'})){
        $classList = " class=\"" . $element->{'class'} ."\" ";
    }
    $html ="";

    foreach ($element->{'fields'} as $listElement){
        $left = $leftList;
        foreach($element->{'elements_fields'} as $displayField){
            if ( isset($displayField->{'name'}) && isset($listElement->{$displayField->{'name'}})){
                if ( isset($displayField->{'align'})){
                    $align = " text-align:" . $displayField->{'align'} ."; ";
                }else{
                    $align = $alignList;
                }
                if ( isset($displayField->{'color'})){
                    $color = "color:" . $displayField->{'color'} ."; ";
                }else{
                    $color = $colorList;
                }
                if ( isset($displayField->{'fontsize'})){
                    $fontsize = "font-size:" . $displayField->{'fontsize'} ."pt; ";
                }else{
                    $fontsize = $fontsizeList;
                }
                if ( isset($displayField->{'width'})){
                    $width = "width:" . $displayField->{'width'} ."mm; ";
                }else{
                    $width = "";
                }
                $attribute="";
                if ( isset($displayField->{'attribute'}) && isset($listElement->{$displayField->{'attribute'}})){
                    $attribute = " attribute=\"" . $listElement->{$displayField->{'attribute'}} ."\" ";
                }

                $top = " top:" . $topList ."mm; ";
                $html .= "<div " . $classList . $attribute. " style=\"position:absolute; ".  $align . $color . $fontsize . $top .  $width . " left:" . $left ."mm; "."\">";
                $html .= preg_replace("/\r\n/","<br>",$listElement->{$displayField->{'name'}});
                $html .= "</div>";
            }
            if ( isset($displayField->{'width'})){
                $left += $displayField->{'width'} ;
            }
        }
        if ( isset($listElement->{'height'})){
            $topList += $listElement->{'height'} ;
        }else{
            $topList += $heightList ;
        }
         
    }
    return $html;
}
?>