<?php
$host = 'mysql-gestiondesmateriel.alwaysdata.net';
$dbname = 'gestiondesmateriel_bdd';
$username = '407602';
$password = 'Tatu123456789';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}
?>