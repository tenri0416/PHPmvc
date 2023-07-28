<?php
//webソケットライブラリーを読み込み
require 'vendor/autoload.php';
require 'model/mysqlconnection.php';


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// WebSocketサーバーの実装
class MyWebSocket implements MessageComponentInterface
{
    protected $clients;
    protected $mysql;
    protected $sample;
   
    public function __construct()
    {
        //オブジェクトをキーとして使用することができるデータストレージ
        $this->clients = new \SplObjectStorage;
         $this->mysql = new MysqlConnection(); 
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // 新しいクライアントが接続した際の処理
        $this->clients->attach($conn);
        echo "新しいクライアント:({$conn->resourceId})接続しました\n";
    }

    //クライアントからメッセージを受信した際の処理
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg_Arr=explode(',',$msg);
         $this->mysql->Insert($msg_Arr[0],$msg_Arr[1]);
         $difference=$this->mysql->Select();
        //ログに出力
        echo "クライアント相手は {$from->resourceId}です.: $msg\n";
        $json_difference=json_encode($difference);
        
        // 全クライアントにメッセージを送信
        foreach ($this->clients as $client) {
                 $client->send($json_difference);
        }
    }

     // クライアントが接続を切断した際の処理
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo " {$conn->resourceId}クライアントが接続を終了しました\n";
    }
     //エラー発生した場合の処理
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "エラー発生！！内容=> {$e->getMessage()}\n";
        $conn->close();
    }
}

//WebSocketサーバーを8080ポートで起動
$server = IoServer::factory(new HttpServer(new WsServer(new MyWebSocket())), 8090);
$server->run();

