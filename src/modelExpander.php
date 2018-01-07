<?php

include "recettes.php";

$models = ["models/2 pages", "models/1 page version 2"]; //"models/1 page version 1",


$data = $recettes;


function getExpandedModel($modelNumber)
{
	global $models;
	//$opts = array('http'=>array('method'=>"GET",'header'=>"Content-Type: text/xml; charset=utf-8");
	//$context = stream_context_create($opts);
	$modelJS = file_get_contents($models[$modelNumber]);


	$model = json_decode($modelJS);
	if (json_last_error() != 0) {
		echo "error read model";
		exit;
	}

	$idx = 0;
	foreach ($model->{"pages"} as $page) {

		if (isset($page->{"section"})) {
			$section = $page->{"section"};
			$criteria = $section->{"criteria"};
			$criteria_value = $section->{"criteria_value"};

			$dataPages = getDataPages($criteria, $criteria_value);
			$pageExpanded = array();

			foreach ($dataPages as $dataPage) {
				foreach ($section->{"pages"} as $pageSection) {
					$newPage = cloneJS($pageSection);

					replaceVars($model, $newPage, $section, $dataPage);
					$pageExpanded[] = $newPage;
				}

			}
			array_splice($model->{"pages"}, $idx, 1, $pageExpanded);
			$idx += count($pageExpanded);
		} else {
			$pageExpanded = array();
			$newPage = cloneJS($page);
			replaceVars($model, $newPage, [], []);
			$pageExpanded[] = $newPage;
			array_splice($model->{"pages"}, $idx, 1, $pageExpanded);
			$idx++;
		}


	}

	// blank page insertion
	$idx = 0;
	while ( $idx<count($model->{"pages"})) {
		if (isset($model->{"pages"}[$idx]->{"side"})) {
			$side = $model->{"pages"}[$idx]->{"side"};
			if ( ( $side=="right" && $idx % 2 == 1) || ( $side=="left" && $idx % 2 == 0) ){
				array_splice( $model->{"pages"},$idx,0, array(array()));
				$idx++;
			}
		}
		$idx++;
	}

	// page number replacement
	$idx = 0;
	foreach ($model->{"pages"} as $page) {
		$pageExpanded = array();
		$newPage = cloneJS($page);
		replaceNumPage($model, $newPage, $idx);
		$pageExpanded[] = $newPage;
		array_splice($model->{"pages"}, $idx, 1, $pageExpanded);
		$idx++;
	}

	//file_put_contents ( "models/expanded",json_encode($model) );

	return $model;
}

function replaceNumPage($model, $newPage, $idx)
{
	foreach ($newPage as $key => $value) {
		$newPage->{$key} = replaceElementNumPage($model, $value, $idx);
	}
	return $newPage;
}
function replaceElementNumPage($model, $value, $idx)
{
	if (gettype($value) == "string") {
		if (preg_match('/@(\w+)@(\w+)@(.*)/', $value, $vars) == 1 && count($vars) == 4) {
			if ($vars[1] == "numero_page") {
				if ($vars[2] == "current") {
					return $idx;
				}
				return getNumPage($model, $vars[2], $vars[3]);
			}
			return $value;
		}
	} else if (gettype($value) == "array") {
		$values = array();
		foreach ($value as $e) {
			$values[] = replaceElementNumPage($model, $e, $idx);
		}
		return $values;
	} else {

		foreach ($value as $key => $subValue) {
			$value->{$key} = replaceElementNumPage($model, $subValue, $idx);
		}
		return $value;
	}
	return $value;
}

function getNumPage($model, $property, $value)
{
	//echo "GetnumPage";
	for ($number = 0; $number < count($model->{"pages"}); $number++) {
		if (isset($model->{"pages"}[$number]->{'elements'})) {
			foreach ($model->{"pages"}[$number]->{"elements"} as $element) {
				//echo $element->{'type'} ." / " . $element->{'name'} ." / " . $element->{'value'} ." =? " . $property ." / " . $value ;
				if ($element->{'type'} == "property" && $element->{'name'} == $property && $element->{'value'} == $value) {
					return $number;
				}
			}
		}
	}
}


function replaceVars($model, $newPage, $section, $dataPage)
{

	foreach ($newPage as $key => $value) {
		$newPage->{$key} = replaceElementVars($model, $value, $section, $dataPage);
	}
	return $newPage;
}

function replaceElementVars($model, $value, $section, $dataPage)
{
	if (isset($value->{"type"}) && $value->{"type"} == "list") {
		replaceListVars($model, $value);
	}

	if (gettype($value) == "string") {
		if (preg_match('/@(\w+)@(.+)/', $value, $vars) == 1 && count($vars) == 3) {
			if ($vars[1] == "document") {
				return $model->{$vars[2]};
			}
			if ($vars[1] == "section") {
				return $section->{$vars[2]};
			}
			if ($vars[1] == "page") {
				return $dataPage[$vars[2]];
			}
			if ($vars[1] == "url") {
				$content = file_get_contents($vars[2]);
				//var_dump($content);
				return $content;
			}
			return $value;
		}
	} else if (gettype($value) == "array") {
		$values = array();
		foreach ($value as $e) {
			$values[] = replaceElementVars($model, $e, $section, $dataPage);
		}
		return $values;
	} else {

		foreach ($value as $key => $subValue) {
			$value->{$key} = replaceElementVars($model, $subValue, $section, $dataPage);
		}
		return $value;
	}
	return $value;
}

function replaceListVars($model, $value)
{
	global $data;


	if (isset($value->{"criteria"})) {
		$criteria = $value->{"criteria"};
	}
	if (isset($value->{"criteria_value"})) {
		$criteria_value = $value->{"criteria_value"};
	}
	if (isset($value->{"fields"})) {
		$fields = $value->{"fields"};
	}
	$dataList = [];
	foreach ($data as $dataPage) {
		if ($dataPage[$criteria] == $criteria_value) {
			$dataField = new stdClass;
			foreach ($fields as $field) {
				if (preg_match('/@(\w+)@(.+)/', $field, $vars) == 1 && count($vars) == 3 && $vars[1] == "page") {
					$dataField->{"page"} = "@numero_page@recette_id@" . $dataField->{$vars[2]};
				} else {
					$v = $dataPage[$field];
					$dataField->{$field} = $v;
				}
			}

			$dataList[] = $dataField;
		}
	}
	$value->{"fields"} = $dataList;
	return $value;
}

function getDataPages($criteria, $criteria_value)
{
	global $data;

	$dataPages = array();

	foreach ($data as $dataPage) {

		if ($dataPage[$criteria] == $criteria_value) {
			$dataPages[] = $dataPage;
		}
	}
	return $dataPages;
}

function cloneJS($objectJS)
{
	if (gettype($objectJS) == "string") {
		$newString = $objectJS;
		return $newString;
	} else if (gettype($objectJS) == "array") {
		$values = array();
		foreach ($objectJS as $e) {
			$values[] = cloneJS($e);
		}
		return $values;
	} else {
		$newObjectJS = new stdClass;
		foreach ($objectJS as $key => $value) {
			$newObjectJS->{$key} = cloneJS($value);
		}
		return $newObjectJS;
	}
}
?>