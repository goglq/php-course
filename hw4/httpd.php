<?php

echo "\n";

$current_directory = getcwd();

define("DocumentRoot", "$current_directory/html");

define("MIME_TYPE", 
    [
        "html" => "text/html", 
        "jpg" => "image/jpeg", 
        "css" => "text/css",
        "js" => "text/javascript"
    ]
);

$socket_base = socket_Create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket_base, "127.0.0.1", 5500);
socket_listen($socket_base, 20);

while(true) {
    $socket_fd = socket_accept($socket_base);
    $data = socket_read($socket_fd, 100_000);

    $request = parse_request($data);

    $uri = $request["uri"];
    $extension = $request["ext"];
    $query_string = $request["query_string"];
    
    $filename = DocumentRoot . $uri;

    if(file_exists($filename)) {
        $response_body = file_get_contents($filename);
        $response = "HTTP/1.1 200 Ok\n";
        $mime_type = MIME_TYPE[$extension];
        $response = $response . "Content-type: $mime_type\n\n";
        $response = $response . $response_body;
        if($query_string !== '') {
            $calculation_result = calculate($query_string);
            $response = $response . "<h2>$calculation_result</h2>";
        }
    }
    else {
        $response = "HTTP/1.1 404 Not found\n";
    }

    $len = strlen($response);

    $filename = DocumentRoot . $uri;

    socket_write($socket_fd, $response, $len);

    socket_close($socket_fd);
}

socket_close($socket_base);

function calculate($query_string) {
    $inputs = explode('&', $query_string);
    $values = [];
    $operation = '';
    foreach ($inputs as $input) {
        $matches = [];
        preg_match("#(.+)=(.+)#", $input, $matches);
        if($matches[1] === 'operation') {
            $operation = $matches[2];
            continue;
        }
        $value = intval($matches[2]);
        $values[] = $value;
    }
    $calculation_result = 0;
    switch($operation) {
        case '%2B':
            $calculation_result = $values[0] + $values[1];
            break;
        case '-':
            $calculation_result = $values[0] - $values[1];
            break;
        case '*':
            $calculation_result = $values[0] * $values[1];
            break;
        case '%2F':
            if($values[1] > 0){
                $calculation_result = $values[0] / $values[1];
            }
            break;
    }
    return $calculation_result;
}

function parse_request($data)
{
    $matches = [];
    $regexp = "#(\/([A-Za-z]*)\.?([A-Za-z]*)?)\??(.*) #";
    preg_match($regexp, $data, $matches);
    return ["uri" => $matches[1], "action" => $matches[2], "ext" => $matches[3], "query_string" => $matches[4]];
}