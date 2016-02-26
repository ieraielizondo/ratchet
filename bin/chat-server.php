<?php
require __DIR__ . '/../vendor/autoload.php';


use Chat\Chat;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
//use src\MyApp\Chat;

    

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8081
    );

    $server->run();