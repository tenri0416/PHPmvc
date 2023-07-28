<?php
 require 'model/mysqlconnection.php';
$mysql=new MysqlConnection();

$allComments=$mysql->AllSelect();
//json化してJSに渡す
$json_allComments=json_encode($allComments);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form class="Form" action="" method="post">
  <div class="Form-Item">
    <p class="Form-Item-Label">ユーザー名</p>
    <input type="text" id="name" class="Form-Item-Input" placeholder="ユーザー名">
  </div>

  <div class="Form-Item">
    <p class="Form-Item-Label isMsg">メッセージ</p>
    <textarea id="comment" class="Form-Item-Textarea"></textarea>
  </div>
  <button type="button"onclick="sendMessage()"class="Form-Btn">送信</button>
</form>
<button type="button" onclick="autoStartMessage()">スタート</button>
<button type="button" onclick="autoStopMessage()">ストップ</button>

<div id="view_count" class="count-item"></div>
<div id="socket">
<script>
const comments_Arr = <?php echo $json_allComments; ?>;
const messageDiv = document.getElementById('socket');
const count_div=document.getElementById('view_count');
//件数
let count=comments_Arr.length;
count_div.innerHTML=`${count}件表示しています。`;

for(i=0;i<comments_Arr.length;i++){
  messageDiv.innerHTML += `<p class='list-item'>${comments_Arr[i].name}:${comments_Arr[i].comment}</p>`;
}

</script>
</div>
  
   <style>
  .count-item{
    text-align: center;
  }
   .Form {
  margin-top: 2px;
  margin-left: auto;
  margin-right: auto;
  max-width: 720px;
}

.Form-Item {

  padding-top: 2px;
  padding-bottom: 10px;
  width: 100%;
  display: flex;
  align-items: center;
}

.Form-Item-Label {
  width: 100%;
  max-width: 248px;
  letter-spacing: 0.05em;
  font-weight: bold;
  font-size: 18px;
}


.Form-Item-Label.isMsg {
  margin-top: 8px;
  margin-bottom: auto;
}

.Form-Item-Input {
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-left: 40px;
  padding-left: 1em;
  padding-right: 1em;
  height: 28px;
  flex: 1;
  width: 100%;
  max-width: 410px;
  background: #eaedf2;
  font-size: 18px;
}

.Form-Item-Textarea {
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-left: 40px;
  padding-left: 1em;
  padding-right: 1em;
  height: 40px;
  flex: 1;
  width: 100%;
  max-width: 410px;
  background: #eaedf2;
  font-size: 18px;
}

.Form-Btn {
  border-radius: 6px;
  margin-top: 3px;
  margin-left: auto;
  margin-right: auto;
  padding-top: 10px;
  padding-bottom: 10px;
  width: 100px;
  display: block;
  letter-spacing: 0.05em;
  background: #545454;
  color: #fff;
  font-weight: bold;
  font-size: 20px;
}
.list-item{
    font-size:14px;
    text-align:center;
    background: #eaedf2;
}

</style>
 
    <script>
        //Webソケットのインスタンス
        const connect = new WebSocket('ws://localhost:8090');
      
        // 通信が開始された時に実行される処理
        connect.onopen = function () {
            console.log("ここ通信が開始されました");
        };

        // Webサーバーからデータを受信したときに実行される処理
        connect.onmessage = function (event) {
          
          const json_difference = event.data;
          const difference= JSON.parse(json_difference);

            // 受信したデータを表示する
            const messageDiv = document.getElementById('socket');
               messageDiv.innerHTML += `<p class='list-item'>${difference[0].name}:${difference[0].comment}</p>`;
               count++;
               count_div.innerHTML=`${count}件表示しています。`;
           
        };

        // 通信が切断された時に実行される処理
        connect.onclose = function (e) {
            console.log('WebSocket通信が終了しました');
        };

        // 通信中にエラーが発生した時に実行される処理
        connect.onerror = function (e) {
            console.log('WebSocketエラーが発生しました');
        };

        // メッセージを送信する処理
        function sendMessage() {
            let name=document.getElementById('name').value;
            let comment=document.getElementById('comment').value;
            const message=[name,comment];
            connect.send(message);
            document.getElementById('name').value='';
            document.getElementById('comment').value='';

        }
        //処理をストップトリガー
        let stop_trigger;
        function autoStartMessage(){
          //押すとランダムでメッセージを送る
          const names=['佐藤','鈴木','真屋順子','柴田賢志','瓶鮫一','真屋順子','巽秀太郎','柴田賢志','小林夕岐子','中根徹','YATCH',
          '小林隆','川村真樹','小田冴斗','富田仲次郎','相葉雅紀','ささの堅太','松田侑子','藤田宗久',
          '団巌阪脩','草笛美子','田村健太郎','なかもとみゆき','高木りな','柄本明平','野早香','西村優子建蔵'];
          const comments=['すし','ラーメン','うどん','パスタ','そば','タンタンメン','焼肉','唐揚げ','餃子',
                         'ハンバーグ','フライドポテト','フライドチキン','カレーライス','アイス',
                         'ステーキ','ピザ','たこ焼き','牛タン','お好み焼き','タバコ','おにぎり','オムライス','とんかつ','ハンバーガー'];

          stop_trigger=setInterval(function () {
            const name=names[Math.floor(Math.random()*names.length)];
            const comment=comments[Math.floor(Math.random()*comments.length)];
              const message=[name,comment];
            connect.send(message);
	       }, 1000);
        }

        function autoStopMessage(){
          clearInterval(stop_trigger);
        }
  
        
 </script>
</body>
</html>

