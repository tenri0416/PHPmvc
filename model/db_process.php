<?php
require '../model/mysql.class.php';
$mysql=new MysqlConnection();
// HTTPから現在のリクエストがPOST確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //json_decode()->JSON形式の文字列を配列またはオブジェクトに変換する
  //file_get_contents=>指定したファイルの内容を文字列として取得
  //php://inputは特殊なラッパーを使用してリクエストボディを直接取得
  $data = json_decode(file_get_contents('php://input'), true);
  $delete_id = $data['delete_id'];
  $mysql->Delete($delete_id);
  $response = array('status' => 'success', 'message' => '削除が成功しました');
  echo json_encode($response);
} else {
  // POSTリクエストでない場合はエラーレスポンスを返す
  $response = array('status' => 'error', 'message' => '無効なリクエスト');
  echo json_encode($response);
}

?>
