<?php
namespace model;

require_once 'animal.class.php'; // Animal クラスを読み込む

class Dog extends Animal { // Animal クラスを継承する
    public function ote() {
        return 'おて';
    }
}
