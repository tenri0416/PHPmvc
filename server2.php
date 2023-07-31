<?php
//webソケットライブラリーを読み込み
require 'vendor/autoload.php';
require 'model/mysql.class.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
 use Ratchet\Server\IoServer;
 use Ratchet\Http\HttpServer;
 use Ratchet\WebSocket\WsServer;

class DBWebSocket implements MessageComponentInterface
{
    protected $clients;
    protected $mysql;
  
   
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
        echo "新しいDBクライアント:({$conn->resourceId})接続しました\n";
    }

    //クライアントからメッセージを受信した際の処理
    public function onMessage(ConnectionInterface $from, $msg)
    {
        //DB削除処理
         $this->mysql->Delete($msg);
         //DBすべて取得
         $difference=$this->mysql->AllSelect();
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


$server2 = IoServer::factory(new HttpServer(new WsServer(new DBWebSocket())), 8091);
$server2->run();