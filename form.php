<?php
 require 'model/mysql.class.php';
$mysql=new MysqlConnection();
//全件取得
$allComments=$mysql->AllSelect();
//json化してJSに渡す
$json_allComments=json_encode($allComments);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tyatApp</title>
    <link rel="stylesheet" href="css/form.css"></link>
    <!-- WEBソケット読み込み -->
    <script src="web.js"></script>
    <script src="web2.js"></script>
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
<button type="button" id="start_btn" onclick="autoStartMessage()">スタート</button>
<button type="button" id="stop_btn" onclick="autoStopMessage()">ストップ</button>

<div id="view_count" class="count-item"></div>

  <div id="socket">
    <script>
//スタートボタン、ストップボタンのフラグ
const comments_Arr = <?php echo $json_allComments; ?>;
const messageDiv = document.getElementById('socket');
const count_div=document.getElementById('view_count');
//件数
let count=comments_Arr.length;
count_div.innerHTML=`${count}件表示しています。`;

for(i=0;i<comments_Arr.length;i++){
  messageDiv.innerHTML += `<p id="edit" class="list-item"contenteditable>${comments_Arr[i].name}:${comments_Arr[i].comment}
  <button onclick="deleteBtn(${comments_Arr[i].id})">削除</button></p>`;

}

</script>
</div>
  
    <script>

       //送信処理(8090)
        function sendMessage() {
            let name=document.getElementById('name').value;
            let comment=document.getElementById('comment').value;
            const message=[name,comment];
             connect.send(message);
            document.getElementById('name').value='';
            document.getElementById('comment').value='';
        }
        //削除処理(8091)
        function deleteBtn(deleteId){
           connect2.send(deleteId);
        }
        
        //処理をストップトリガー
        let stop_trigger;
        function autoStartMessage(){
          const startBtn=document.getElementById('start_btn');
          const stopBtn=document.getElementById('stop_btn');
          startBtn.disabled=true;
          stopBtn.disabled=false;
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
          const startBtn=document.getElementById('start_btn');
          const stopBtn=document.getElementById('stop_btn');
          startBtn.disabled=false;;
          stopBtn.disabled=true;
          clearInterval(stop_trigger);
        }

        

        
 </script>
 
</body>
</html>

