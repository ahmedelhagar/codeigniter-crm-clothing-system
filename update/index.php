<?php
    $host = 'https://kokykidswear.com/koky/';
    $map = json_decode(file_get_contents($host.'update/map.php'));
    $files = $map->map;
    foreach($files as $file){
        $fileData = explode('}',$file);
        $fileDir = str_replace('{','',str_replace('.','/',$fileData[0]));
        $fileName = str_replace(array('}.kk','{'),array('','.'),$fileData[1]);
        $data_to_write = file_get_contents($host.'update/files/'.$file);
        $file_path = __DIR__.'../../'.$fileDir.'/'.$fileName;
        $file_handle = fopen($file_path, 'w'); 
        fwrite($file_handle, $data_to_write);
        fclose($file_handle);
        $resMsg = 'Updated.';
    }
    $response = array(
        'done' => 1,
        'response' => $resMsg
    );
    echo json_encode($response);
?>