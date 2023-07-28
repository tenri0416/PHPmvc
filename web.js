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
         messageDiv.innerHTML += `<p class='list-item'>${difference[0].name}:${difference[0].comment}<button onclick="deleteBtn(${difference[0].id,true})">削除</button></p>`;
         count++;
         count_div.innerHTML=`${count}件表示しています。`;

         function deleteBtn(delete_id,){
        
          fetch('model/db_process.php',{
            method:'POST',
            headers:{
              'Content-Type':'application/json'
            },
            body:JSON.stringify({delete_id:delete_id}),
          })
          .then(response=>{
            return response.json();
          })
          
          .then(data=>{
            console.log(`データを取得:${data.message}`);
          })
          .catch(error=>{
            console.log(`エラー:${error}`);
          })
   

   
        }
     
  };


  // 通信が切断された時に実行される処理
  connect.onclose = function (e) {
      console.log('WebSocket通信が終了しました');
  };

  // 通信中にエラーが発生した時に実行される処理
  connect.onerror = function (e) {
      console.log('WebSocketエラーが発生しました');
  };
  
