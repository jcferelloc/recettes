<?php


include 'connect.php';
$connection = connect();
$returnData = new StdClass;

function getResults($result)
{
    $returnData = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (count($row) == 0) {
                continue;
            }
            $rowEncode = array();
            foreach ($row as $key => $value) {
                $rowEncode[$key] = utf8_encode($value);
            }
            if ($result->num_rows == 1) {
                return $rowEncode;
            }
            $returnData[] = $rowEncode;
        }
    }
    return $returnData;
}

//nombre de recettes
$query = "SELECT COUNT( * ) as NB FROM `" . getTable("recettes") . "`;";
$returnData->{"nbRecettes"} = getResults($connection->query($query));

//nombre de recettes par catégories
$query = "SELECT categorie ,COUNT( * ) as NB FROM `" . getTable("recettes") . "` GROUP BY categorie;";
$returnData->{"nbRecettesPerCateg"} = getResults($connection->query($query));

//nombre de recettes sans photos
$query = "SELECT COUNT( * ) as NB FROM `" . getTable("recettes") . "` WHERE ! (url_plat > '') or ! (url_chef > '') ;";
$returnData->{"nbRecettesMissingPhoto"} = getResults($connection->query($query));

//Nombre famille loggées total
$query = "SELECT COUNT( DISTINCT login ) as NB FROM `" . getTable("logs") . "` WHERE ! (login > '')  ;";
$returnData->{"familiesTotal"} = getResults($connection->query($query));


//Nombre visiteurs total
$query = "SELECT COUNT( DISTINCT ip ) as NB FROM `" . getTable("logs") . "`  ;";
$returnData->{"visitorsTotal"} = getResults($connection->query($query));

//Nombre visiteurs les dernières 24h
$query = "SELECT COUNT( DISTINCT ip ) as NB FROM `" . getTable("logs") . "` WHERE date >= CURDATE() - INTERVAL 7 DAY ;";
$returnData->{"visitorsTotalAweek"} = getResults($connection->query($query));

//Nombre visiteurs aujourd'hui
$query = "SELECT COUNT( DISTINCT ip ) as NB FROM `" . getTable("logs") . "` WHERE date >= CURDATE();";
$returnData->{"visitorsTotalToday"} = getResults($connection->query($query));


//volume des photos
$size = new stdClass;
$size->{"size"} = format_size(foldersize("upload/"));
$returnData->{"photoFolderSize"} = $size;


function foldersize($dir)
{
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
    foreach ($dir_array as $key => $filename) {
        if ($filename != ".." && $filename != ".") {
            if (is_dir($dir . "/" . $filename)) {
                $new_foldersize = foldersize($dir . "/" . $filename);
                $count_size = $count_size + $new_foldersize;
            } else if (is_file($dir . "/" . $filename)) {
                $count_size = $count_size + filesize($dir . "/" . $filename);
                $count++;
            }
        }

    }

    return $count_size;
}

function format_size($size) {
    $mod = 1024;
    $units = explode(' ','B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
      $size /= $mod;
    }
  
    return round($size, 2) . ' ' . $units[$i];
  }
  










if (isset($_GET["js"])) {
    echo json_encode($returnData);
}


?>