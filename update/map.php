<?php
    $files = array_values(array_diff(scandir('files'), array('.', '..')));
        $response = array(
            'done' => 1,
            'map' => $files
        );
        echo json_encode($response);
?>