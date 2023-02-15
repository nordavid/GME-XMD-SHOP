<?php 
    function errorMsg($message) {
        return json_encode([
            "status" => "error",
            "error" => true,
            "message" => $message
        ]);
    }

    function successMsg($message) {
        return json_encode([
            "status" => "success",
            "error" => false,
            "message" => $message
        ]);
    }
