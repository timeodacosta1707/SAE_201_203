<?php
function isAdmin($pdo, $email) {
    $stmt = $pdo->prepare("
        SELECT CASE WHEN a.Id IS NOT NULL THEN 1 ELSE 0 END as is_admin
        FROM Personne AS p
        LEFT JOIN Administration AS a ON p.Id_user = a.Id
        WHERE p.email = :email
    ");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['is_admin'] == 1;
}

function isProfesseur($pdo, $email) {
    $stmt = $pdo->prepare("
        SELECT CASE WHEN pr.Id IS NOT NULL THEN 1 ELSE 0 END as is_professeur
        FROM Personne AS p
        LEFT JOIN Professeur AS pr ON p.Id_user = pr.Id
        WHERE p.email = :email
    ");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['is_professeur'] == 1;
}

function isAgent($pdo, $email) {
    $stmt = $pdo->prepare("
        SELECT CASE WHEN ag.Id IS NOT NULL THEN 1 ELSE 0 END as is_agent
        FROM Personne AS p
        LEFT JOIN Agent AS ag ON p.Id_user = ag.Id
        WHERE p.email = :email
    ");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['is_agent'] == 1;
}
?> 