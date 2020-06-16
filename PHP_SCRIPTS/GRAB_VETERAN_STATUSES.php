<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        function glob_recursive($pattern, $flags = 0) {

          $files = glob($pattern, $flags);

          foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir){
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
          }

          return $files;
        }

        $statusData = array();

        $veteransInCirculation = (glob_recursive("../VETERANS_IN_CIRCULATION/*.txt"));

        foreach($veteransInCirculation as $veteranInCirculation) {

            $veteranFilePathInfo = pathinfo($veteranInCirculation);
            $veteranFullName = $veteranFilePathInfo['dirname'];

            $veteranFullName = explode("/", $veteranFullName);
            $veteranFullName = end($veteranFullName);
            $veteranFullName = explode("_", $veteranFullName);
            $veteranFullName = $veteranFullName[0]. " ".$veteranFullName[1];

            $veteranInCirculation = $veteranFilePathInfo['filename'];
            $veteranInCirculationSplit = explode("_", $veteranInCirculation);

            $veteranGUID = $veteranInCirculationSplit[0];
            $veteranStatus = $veteranInCirculationSplit[1];
            $veteranStatus = str_replace(".txt","",$veteranStatus);

            $veteran_data_array = array("veteranGUID" => $veteranGUID,
                                        "veteranStatus" => $veteranStatus,
                                        "veteranFullName" => $veteranFullName);

            array_push($statusData, $veteran_data_array);

        }

        echo json_encode($statusData);

    } else {
        echo "WRONG REQUEST SENT";
    }

?>
