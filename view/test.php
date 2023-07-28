<?php

require_once '../model/animal.class.php'; // ファイルの相対パスを適切に指定する
require_once '../model/dog.class.php'; // Dog クラスを読み込む
use model\Animal;
use model\Dog;

$animal = new Animal(); // Animal クラスのインスタンスを生成

echo $animal->bark(); // "wan" が出力される
$dog = new Dog(); // Dog クラスのインスタンスを生成

echo $dog->bark(); // "wan" が出力される（Animal クラスのメソッドを呼び出す）
echo $dog->ote();  // "おて" が出力される（Dog クラスのメソッドを呼び出す）
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>