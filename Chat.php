<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
 use Ratchet\Server\IoServer;
 use Ratchet\Http\HttpServer;
 use Ratchet\WebSocket\WsServer;
 require 'vendor/autoload.php';


class Chat implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            $data = ['msg' => $msg];
            if ($from === $client) {
                $data['position'] = 'right';
            } else {
                $data['position'] = 'left';
            }
            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
            )
        ),
    8282
    );

$server->run();
