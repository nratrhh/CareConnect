<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=careconnect', 'root', '');
    echo "Connected";
} catch (PDOException $e) {
    echo $e->getMessage();
}
