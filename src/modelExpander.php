<?php



$dataJS = file_get_contents("bookdata");
$data = json_decode ($dataJS);
if ( json_last_error() != 0 ) {
	echo "error read data";
	exit;
}


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
					
					replaceVars($newPage, $section, $dataPage );
					$pageExpanded[] = $newPage;
				}
				
			}  
			array_splice ($model->{"pages"}, $idx, 1, $pageExpanded);
		}
		
		$idx++;
	}
	return $model;
}




function replaceVars($newPage, $section, $dataPage ){
	foreach( $newPage as $key => $value){
		$newPage->{$key}=replaceElementVars($value, $section, $dataPage );
	}
	return $newPage;
}

function replaceElementVars($value, $section, $dataPage ){
	if ( gettype( $value ) == "string"){
		if ( preg_match('/@(\w+)@(\w+)/', $value, $vars) == 1 && count($vars)==3 && ($vars[1]=="section" || $vars[1]=="page") ){
			if ( $vars[1] == "section" ) {
				return $section->{$vars[2]};
			}
			if ( $vars[1] == "page" ) {
				return $dataPage->{$vars[2]};
			}
		}
	} else if ( gettype( $value ) == "array"){
		$values = array();
		foreach( $value as $e){
			$values[] = replaceElementVars($e, $section, $dataPage );
		}
		return $values;
	}
	else{
		
		foreach( $value as $key => $subValue){
			$value->{$key} = replaceElementVars($subValue, $section, $dataPage );
		}
		return $value;
	}
	return $value;
}

function getDataPages( $criteria, $criteria_value ){
	global $data;
	
	$dataPages = array();
	
	foreach($data->{"data"} as $dataPage){
		if ( $dataPage->{$criteria} == $criteria_value ){
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