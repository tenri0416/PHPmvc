//Webソケットのインスタンス
  const connect = new WebSocket('ws://localhost:8090');
      // WebSocketサーバーに接続（ポート 8091 のサーバー）

  // 通信が開始された時の処理
  connect.onopen = function () {
      console.log("ここポート8090通信が開始されました");
  };

  // Webサーバーからデータを受信したときに実行される処理
  connect.onmessage = function (event) {
    const comments= JSON.parse(event.data);
      // 受信したデータを表示する
      const messageDiv = document.getElementById('socket');
      while(messageDiv.firstChild){
        messageDiv.removeChild(messageDiv.firstChild);
    }
      let count=0;
      for(i=0;i<comments.length;i++){
        messageDiv.innerHTML += `<p class='list-item'>${comments[i].name}:${comments[0].comment}<button onclick="deleteBtn(${comments[0].id})">削除</button></p>`;
        count++;
        count_div.innerHTML=`${count}件表示しています。`;
      }
     
  };

  // 通信が切断された時に実行される処理
  connect.onclose = function (e) {
      console.log('ポート8090通信が終了しました');
  };

  // 通信中にエラーが発生した時に実行される処理
  connect.onerror = function (e) {
      console.log('ポート8090エラーが発生しました');
  };
  
