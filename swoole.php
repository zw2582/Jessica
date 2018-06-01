<?php
require './vendor/autoload.php';
require './config/bootstrap.php';

error_reporting(E_ALL ^ E_NOTICE);

spl_autoload_register(function($class){
    $class = strtr($class, '\\', '/');
    require_once __DIR__.'/'.$class.'.php';
});
    
    $serv = new swoole_websocket_server("0.0.0.0", "9501");
    
    $serv->on("open", function(swoole_websocket_server $serv, $request) {
        echo "server open";
    });
        
        $serv->on('message', function (swoole_websocket_server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });
            
            $serv->on('close', function ($ser, $fd) {
                echo "client {$fd} closed\n";
            });
                    
                    $serv->start();