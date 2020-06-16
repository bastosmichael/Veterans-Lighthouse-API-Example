<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $apiKey = "HOWEVER_YOU_STORE_AND_GRAB_IT"; //Please store it securely, don't store it plain text in the script

        $headers = array("apiKey: " . $apiKey,
                         "Accept: application/json");

        $handleGrabPUTLocation = curl_init("https://sandbox-api.va.gov/services/vba_documents/v1/uploads");

        curl_setopt($handleGrabPUTLocation, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handleGrabPUTLocation, CURLOPT_POST, true);
        curl_setopt($handleGrabPUTLocation, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($handleGrabPUTLocation, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($handleGrabPUTLocation, CURLOPT_RETURNTRANSFER, true);

        $putLocationCURLRequest = curl_exec($handleGrabPUTLocation);

        $statusCode = curl_getinfo($handleGrabPUTLocation, CURLINFO_HTTP_CODE);

        curl_close($handleGrabPUTLocation);

        if ($statusCode == 202) {

            $filesForUpload = array();

            $uploadInformation = json_decode($putLocationCURLRequest, true);

            $veteranGUID = $uploadInformation['data']['attributes']['guid'];
            $veteranPutLocation = $uploadInformation['data']['attributes']['location'];

            $veteranFormInformation = $_POST["veteranFormInformation"];
            $veteranFormInformationDecoded = json_decode($veteranFormInformation, true);

            $veteranFolderName = strtoupper($veteranFormInformationDecoded['veteranFirstName']) . '_' . strtoupper($veteranFormInformationDecoded['veteranLastName']) . '_' . $veteranGUID;
            $veteranFullFilePath = "../VETERANS_IN_CIRCULATION/" . $veteranFolderName;

            mkdir($veteranFullFilePath);

            foreach ($_FILES as $key => $value) {
                $fileNameWithoutExtension = strtolower($_FILES[$key]["name"]);

                $fileNameWithoutExtension = str_replace(".pdf", "", $fileNameWithoutExtension);
                $fileNameWithoutExtension = str_replace(".json", "", $fileNameWithoutExtension);

                $filesForUpload[$fileNameWithoutExtension] = curl_file_create($_FILES[$key]["tmp_name"], $_FILES[$key]["type"], $_FILES[$key]["name"]);

            }

            $handleFileUploadToVA = curl_init($veteranPutLocation);

            curl_setopt($handleFileUploadToVA, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($handleFileUploadToVA, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($handleFileUploadToVA, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($handleFileUploadToVA, CURLOPT_POSTFIELDS, $filesForUpload);
            curl_setopt($handleFileUploadToVA, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($handleFileUploadToVA);

            $statusCode = curl_getinfo($handleFileUploadToVA, CURLINFO_HTTP_CODE);

            curl_close($handleFileUploadToVA);

            if ($statusCode == 200) {

                //FILES CANNOT BE MOVED UNTIL AFTER TRANSMIT IS SUCCESSFUL, OTHERWISE CURL WILL BOMB

                foreach ($_FILES as $key => $value) {
                    move_uploaded_file($_FILES[$key]["tmp_name"], $veteranFullFilePath . "/" . $_FILES[$key]["name"]);
                }

                touch($veteranFullFilePath . "/" . $veteranGUID . "_UPLOADED.txt");

                echo "UPLOAD SUCCESSFUL";

            } else {
                echo "FILE FAILED DURING TRANSIT TO THE VA.";
                rmdir($veteranFullFilePath);
            }

        } else {
            echo "UPLOAD FAILED, STATUS 202 NOT RECIEVED FROM VA API ON INITIAL POST";
        }

    } else {
        echo "WRONG REQUEST SENT";
    }

?>
