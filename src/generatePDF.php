<?php

require 'fpdf.php';
require "modelExpander.php";


include "formatSheet.php";

$start = 0;
if (isset($_GET["start"])){
    $start = htmlspecialchars($_GET["start"]);
}

$nb = 0;
if (isset($_GET["nb"])){
    $nb = htmlspecialchars($_GET["nb"]);
}


$model = getExpandedModel("modeles/modelExample.book");
if ($nb==0){
    $nb = count($model->{"pages"});
}

$pdf = new FPDF($model->{'orientation'},'mm', $model->{'format'});
$pdf->AddFont('latoLight','','latolight.php');
$pdf->SetFont('latoLight','',16);
$pdf->SetMargins(0,0);
$pdf->SetAutoPageBreak(false);

for ( $number = $start; $number < $start+$nb ; $number++ ){
    pagePDF($number);
}
$pdf->Output("I", "Livre recettes APE 2018" . date("j-m-Y") . ".pdf");


function pagePDF($number){
    global $formatSheet, $pdf, $model;
    
    $pdf->AddPage();
    $page = $model->{'pages'}[$number];
    
    if ( $model->{'orientation'} == "L"){
        $width = $formatSheet[$model->{'format'}][1];
        $height = $formatSheet[$model->{'format'}][0];
    } else {
        $width = $formatSheet[$model->{'format'}][0];
        $height = $formatSheet[$model->{'format'}][1];
    }

    if ( isset($page->{'background-color'})){
        $rgb = toRGB($page->{'background-color'});
        $pdf->SetFillColor($rgb[0],$rgb[1],$rgb[2]);
        $pdf->Rect(0,0,$width,$height,'F');
    }else if ( isset($page->{'background-img'})){
        $pdf->Image($page->{'background-img'},0,0,$width,$height);
    } /*else{
        $pdf->Rect(0,0,$width,$height,'L');
        $pdf->Rect(10,10,$width-20,$height-20,'L');
    }*/
    
    if ( isset($page->{'elements'})){
        foreach ($page->{"elements"} as $element ){
            elementPDF($element);
        }
    }
}


function elementPDF($element){
    switch ( $element->{'type'}){
        case "text":
        return textPDF($element);
        case "image":
        return imagePDF($element);
        case "list" :
        return listPDF($element);
    }
}


function textPDF($element){
    global $pdf;
    $value="";
    $align="";
    $left=0;
    $top=0;
    $width=0;
    $height=0;
    $fontsize=10;
    $color="#000000";
    

    if ( isset($element->{'value'})){
        $value = $element->{'value'};
    }
    if ( isset($element->{'align'})){
        switch ($element->{'align'}) {
            case "right" :
            $align ="R";
            break;
        case "left" :
            $align ="L";
            break;
        case "center" :
            $align ="C";
            break;
        }
    }
    if ( isset($element->{'left'})){
        $left = $element->{'left'} ;
    }
    if ( isset($element->{'top'})){
        $top = $element->{'top'} ;
    }
    if ( isset($element->{'width'})){
        $width =  $element->{'width'} ;
    }
    if ( isset($element->{'fontsize'})){
        $fontsize = $element->{'fontsize'} ;
    }
    if ( isset($element->{'height'})){
        $height =  $element->{'height'} ;
    }else{
        $height = $fontsize / 2.83464566929134;
    }
    if ( isset($element->{'color'})){
        $color = $element->{'color'} ;
    }

    $pdf->SetXY($left, $top);
    $pdf->SetFontSize($fontsize);
    $rgb = toRGB($color);
    $pdf->SetTextColor($rgb[0],$rgb[1],$rgb[2]);
    $pdf->MultiCell($width,  $height , utf8_decode($value), 0, $align);
}

function imagePDF($element){
    global $pdf;
    $url="";
    $left="0";
    $top="0";
    $width="0";
    

    if ( isset($element->{'url'})){
        $url = $element->{'url'};
    }
    if ( isset($element->{'left'})){
        $left = $element->{'left'};
    }
    if ( isset($element->{'top'})){
        $top =  $element->{'top'} ;
    }
    if ( isset($element->{'width'})){
        $width = $element->{'width'};
    }
    if (file_exists($url)){
        $pdf->Image($url,$left,$top,$width);
    }else{
        $pdf->Image("img/missing.jpg",$left,$top,$width);
    }

    
}
function listPDF($element){
    global $pdf;
    $fontsizeList="";
    $topList=0;
    $leftList=0;
    $colorList="";
    $heightList=0;
    $alignList= " text-align:left; ";
    $classList = "";
    $height = 0;

    if ( isset($element->{'fontsize'})){
        $fontsizeList =  $element->{'fontsize'};
    }
    if ( isset($element->{'top'})){
        $topList = $element->{'top'};
    }
    if ( isset($element->{'left'})){
        $leftList = $element->{'left'};
    }
    if ( isset($element->{'color'})){
        $colorList = $element->{'color'} ;
    }
    if ( isset($element->{'height'})){
        $heightList = $element->{'height'};
    }
    if ( isset($element->{'align'})){
        $alignList =  $element->{'align'};
    }
   

    foreach ($element->{'fields'} as $listElement){
        $left = $leftList;
        foreach($element->{'elements_fields'} as $displayField){
            if ( isset($displayField->{'name'}) && isset($listElement->{$displayField->{'name'}})){
               
                if ( isset($displayField->{'color'})){
                    $color = $displayField->{'color'};
                }else{
                    $color = $colorList;
                }
                if ( isset($displayField->{'fontsize'})){
                    $fontsize = $displayField->{'fontsize'} ;
                }else{
                    $fontsize = $fontsizeList;
                }
                if ( isset($displayField->{'width'})){
                    $width = $displayField->{'width'};
                }else{
                    $width = "";
                }
                if ( isset($listElement->{'height'})){
                    $height = $listElement->{'height'} ;
                }else{
                    $height = $heightList ;
                }
                
                if ( isset($displayField->{'align'})){
                    $align = $displayField->{'align'};
                }else{
                    $align = $alignList;
                }
                switch ($align) {
                    case "right" :
                    $align ="R";
                    break;
                case "left" :
                    $align ="L";
                    break;
                case "center" :
                    $align ="C";
                    break;
                }

                $pdf->SetXY($left, $topList);
                $pdf->SetFontSize($fontsize);
                $rgb = toRGB($color);
                $pdf->SetTextColor($rgb[0],$rgb[1],$rgb[2]);
                
                //echo $width .", ". $height .", ".utf8_decode($listElement->{$displayField->{'name'}}) .", ". $align ."<br>";
                $pdf->MultiCell($width,  $height , utf8_decode($listElement->{$displayField->{'name'}}), 0, $align);

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
}

function toRGB ($hex){
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return array( $r, $g, $b);
}
?>