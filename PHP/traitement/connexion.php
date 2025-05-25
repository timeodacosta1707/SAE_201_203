<?php
    $host = 'mysql-gestiondesmateriel.alwaysdata.net';
    $dbname = 'gestiondesmateriel_bdd';
    $user = '407602';
    $password = 'Tatu123456789';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
