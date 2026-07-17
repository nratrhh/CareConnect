<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=careconnect', 'root', '');
    echo "Connected";
} catch (PDOException $e) {
    echo $e->getMessage();
}
