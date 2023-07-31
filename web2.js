//Webソケットインスタンス作成
const connect2=new WebSocket('ws://localhost:8091');

//通信が実行された時の処理
connect2.onopen=function(event){
    console.log('ここでポート8091が開始されました')
}

//データが受信された時の処理
connect2.onmessage=function(event){
    const comments= JSON.parse(event.data);
    const messageDiv = document.getElementById('socket');
    let count=0;
    while(messageDiv.firstChild){
        messageDiv.removeChild(messageDiv.firstChild);
    }
    for(i=0;i<comments.length;i++){
        messageDiv.innerHTML += `<p class='list-item'>${comments[i].name}:${comments[i].comment}<button onclick="deleteBtn(${comments[i].id})">削除</button></p>`;
         count++;
    }
     count_div.innerHTML=`${count}件表示しています。`;
}

connect2.onclose=function(event){
    console.log('ポート8091通信終了しました');
}

connect2.onerror=function(e){
    console.log('ポート8091エラーが発生しました')
}