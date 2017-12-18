<?php

include "recettes.php";



$data = $recettes;


function getExpandedModel($modelFileName){
	//$opts = array('http'=>array('method'=>"GET",'header'=>"Content-Type: text/xml; charset=utf-8");
	//$context = stream_context_create($opts);
	$modelJS = file_get_contents("models/modelExample.book");
	
	
	$model = json_decode ($modelJS);
	if ( json_last_error() != 0 ) {
		echo "error read model";
		exit;
	}

	$idx=0;
	foreach ( $model->{"pages"} as $page ){
		if ( isset($page->{"section"} ) ){
			$section = $page->{"section"};
			$criteria = $section->{"criteria"};
			$criteria_value = $section->{"criteria_value"};
			
			$dataPages = getDataPages( $criteria, $criteria_value);
			$pageExpanded = array();
			
			foreach($dataPages as $dataPage){
				foreach($section->{"pages"} as $pageSection){
					$newPage =  cloneJS( $pageSection );
					
					replaceVars($model, $newPage, $section, $dataPage );
					$pageExpanded[] = $newPage;
				}
				
			}  
			array_splice ($model->{"pages"}, $idx, 1, $pageExpanded);
			$idx+=count($pageExpanded);
		} else{
			$pageExpanded = array();
			$newPage =  cloneJS( $page );
			replaceVars($model, $newPage,[],[] );
			$pageExpanded[] = $newPage;
			array_splice ($model->{"pages"}, $idx, 1, $pageExpanded);
			$idx++;
		}
		
		
	}

	// page number replacement
	$idx=0;
	foreach ( $model->{"pages"} as $page ){
		$pageExpanded = array();
		$newPage =  cloneJS( $page );
		replaceNumPage($model, $newPage, $idx);
		$pageExpanded[] = $newPage;
		array_splice ($model->{"pages"}, $idx, 1, $pageExpanded);
		$idx++;
	}

	//file_put_contents ( "models/expanded",json_encode($model) );

	return $model;
}

function replaceNumPage($model, $newPage, $idx){
	foreach( $newPage as $key => $value){
		$newPage->{$key}=replaceElementNumPage($model, $value, $idx );
	}
	return $newPage;
}
function replaceElementNumPage($model, $value, $idx ){
	if ( gettype( $value ) == "string"){
		if ( preg_match('/@(\w+)@(\w+)@(.*)/', $value, $vars) == 1 && count($vars)==4 ){
			if ( $vars[1] == "numero_page" ) {
				if ( $vars[2] == "current"){
					return $idx;
				}
				return getNumPage($model, $vars[2], $vars[3]);
			}
			return $value;
		}
	} else if ( gettype( $value ) == "array"){
		$values = array();
		foreach( $value as $e){
			$values[] = replaceElementNumPage($model, $e, $idx );
		}
		return $values;
	}
	else{
		
		foreach( $value as $key => $subValue){
			$value->{$key} = replaceElementNumPage($model, $subValue, $idx );
		}
		return $value;
	}
	return $value;
}

function getNumPage($model, $property, $value){
	//echo "GetnumPage";
	for ( $number = 0; $number < count($model->{"pages"}) ; $number++ ){
		if ( isset($model->{"pages"}[$number]->{'elements'})){
			foreach ($model->{"pages"}[$number]->{"elements"} as $element ){
				//echo $element->{'type'} ." / " . $element->{'name'} ." / " . $element->{'value'} ." =? " . $property ." / " . $value ;
				if ( $element->{'type'} == "property" && $element->{'name'}==$property && $element->{'value'}==$value){
					return $number;
				}
			}
		}
	}
}


function replaceVars($model, $newPage, $section, $dataPage ){
	
	foreach( $newPage as $key => $value){
		$newPage->{$key}=replaceElementVars($model, $value, $section, $dataPage );
	}
	return $newPage;
}

function replaceElementVars($model, $value, $section, $dataPage ){

	if ( gettype( $value ) == "string"){
		if ( preg_match('/@(\w+)@(.+)/', $value, $vars) == 1 && count($vars)==3 ){
			if ( $vars[1] == "document" ) {
				return $model->{$vars[2]};
			}
			if ( $vars[1] == "section" ) {
				return $section->{$vars[2]};
			}
			if ( $vars[1] == "page" ) {
				return $dataPage[$vars[2]];
			}
			if ( $vars[1] == "url" ) {
				$content = file_get_contents($vars[2]);
				//var_dump($content);
				return $content;
			}
			return $value;
		}
	} else if ( gettype( $value ) == "array"){
		$values = array();
		foreach( $value as $e){
			$values[] = replaceElementVars($model, $e, $section, $dataPage );
		}
		return $values;
	}
	else{
		
		foreach( $value as $key => $subValue){
			$value->{$key} = replaceElementVars($model, $subValue, $section, $dataPage );
		}
		return $value;
	}
	return $value;
}

function getDataPages( $criteria, $criteria_value ){
	global $data;
	
	$dataPages = array();
	
	foreach($data as $dataPage){

		if ( $dataPage[$criteria] == $criteria_value ){
			$dataPages[]=$dataPage;
		}
	}
	return $dataPages;
}

function cloneJS($objectJS) {
    if ( gettype( $objectJS ) == "string"){
		$newString = $objectJS;
		return $newString;
	} else if ( gettype( $objectJS ) == "array"){
		$values = array();
		foreach( $objectJS as $e){
			$values[] = cloneJS($e);
		}
		return $values;
	}
	else{
		$newObjectJS = new stdClass;
		foreach( $objectJS as $key => $value){
			$newObjectJS->{$key} = cloneJS($value);
		}
		return $newObjectJS;
	}
}
?>