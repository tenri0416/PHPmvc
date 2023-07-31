<?php

class MysqlConnection{


private $hostname='127.0.0.1';
private $username='root';
private $password='';
private $database='web_socket';
public $mysql;

public function __construct()
{
    $this->mysql=new mysqli($this->hostname,$this->username,$this->password,$this->database);
}

public function Insert($name,$comment){

    if($this->mysql->connect_error){
      
        die('接続エラー'.$this->mysql->connect_error);
    }
    $sql="INSERT INTO comments(name,comment) VALUES ('$name','$comment')";
      
    if($this->mysql->query($sql)===true){
            echo "データが挿入されました";
     }else{
            echo $this->mysql->error;
    }

}
public function AllSelect(){
    $sql="SELECT * FROM comments";
    $result = $this->mysql->query($sql);
    if ($this->mysql->connect_error) {
        die("クエリの実行に失敗しました: " . $this->mysql->error);
    }
    $data=[];
     while($row=$result->fetch_assoc()){
        array_push($data,$row);
     }
     //メモリーを解放
     $result->free();
    

    return $data;
    }    

public function Select(){
    $sql="SELECT * FROM comments ORDER by id DESC LIMIT 1";
    //SQLを実行
    $result = $this->mysql->query($sql);

    //接続エラー
    
    if ($this->mysql->connect_error) {
        //エラーログ＝＞die
        die("クエリの実行に失敗しました: " . $this->mysql->error);
    }
    //差分取得
    $difference=[];
    //取得した結果を連想配列として取得
     while($row=$result->fetch_assoc()){
        array_push($difference,$row);
     }
     //メモリーを解放
     $result->free();
    

    return $difference;
    }    
    public function Close(){
        $this->mysql->close();
    }

    public function Delete($deleteId){
        $sql="DELETE FROM comments WHERE id='$deleteId'";
        $result=$this->mysql->query($sql);
    }

}