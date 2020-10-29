<?php
include_once 'config.php';
include_once 'include/db.php';
include_once 'include/functions.php';

$host = $server['host']; 
$port = $server['port']; 
$null = NULL; 

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, 0, $port);
socket_listen($socket);
$clients        = array($socket);
$users_token    = array();
$users_client   = array();

while (true) {

	$changed = $clients;
	socket_select($changed, $null, $null, 0, 10);
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket);
		array_push($clients,$socket_new);
		$header = socket_read($socket_new, 1024);
		perform_handshaking($header, $socket_new, $host, $port);
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
    }
    
    foreach($changed as $client){
        while(socket_recv($client, $buf, 1024, 0) >= 1)
		{
			$received_text = json_decode(unmask($buf)); //unmask data
			//backend code
			//print_r($received_text);
            if(isset($received_text->token) and isset($received_text->type) and isset($received_text->message)):
                $token  =$received_text->token;
                $type   =$received_text->type;
				$message=$received_text->message;
				
                switch($type){
                    case 'start-chat':
						if(in_array($token,$users_token)){
							$index=array_search($token,$users_token);
							$users_client[$index]=$client;
						}
						else{
							array_push($users_token,$token);
							array_push($users_client,$client);
						}
					break;
					case 'send-message':
						
					break;
				}

            else:
                echo "\n Sehv data \n";
            endif;
            //
			break 2; //exist this loop
		}
    }
	
}

socket_close($sock);

