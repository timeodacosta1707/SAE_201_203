<?php
session_start();
require_once 'connexion.php';
require_once 'check_user.php';

if (!isAdmin($pdo, $_SESSION['data']['user'])) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $telephone = $_POST['telephone'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        $pdo->beginTransaction();

        // Mise à jour des informations de base dans la table Personne
        $updatePersonne = "UPDATE Personne SET 
            nom = :nom,
            prenom = :prenom,
            email = :email,
            telephone = :telephone,
            date_naissance = :date_naissance" .
            ($password ? ", password = :password" : "") .
            " WHERE Id_user = :userId";

        $stmt = $pdo->prepare($updatePersonne);
        $params = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
            'userId' => $userId
        ];
        if ($password) {
            $params['password'] = $password;
        }
        $stmt->execute($params);

        // Supprimer les anciens rôles
        $stmt = $pdo->prepare("DELETE FROM Etudiant WHERE Id = :userId");
        $stmt->execute(['userId' => $userId]);
        $stmt = $pdo->prepare("DELETE FROM Professeur WHERE Id = :userId");
        $stmt->execute(['userId' => $userId]);
        $stmt = $pdo->prepare("DELETE FROM Administration WHERE Id = :userId");
        $stmt->execute(['userId' => $userId]);
        $stmt = $pdo->prepare("DELETE FROM Agent WHERE Id = :userId");
        $stmt->execute(['userId' => $userId]);

        // Ajouter le nouveau rôle
        switch ($role) {
            case 'etudiant':
                $stmt = $pdo->prepare("INSERT INTO Etudiant (Id, promotion, groupe_td, groupe_tp, annee, numero_carte) 
                                     VALUES (:userId, :promotion, :groupe_td, :groupe_tp, :annee, :numero_carte)");
                $stmt->execute([
                    'userId' => $userId,
                    'promotion' => !empty($_POST['formation']) ? $_POST['formation'] : null,
                    'groupe_td' => !empty($_POST['groupe_td']) ? $_POST['groupe_td'] : null,
                    'groupe_tp' => !empty($_POST['groupe_tp']) ? $_POST['groupe_tp'] : null,
                    'annee' => !empty($_POST['annee']) ? $_POST['annee'] : null,
                    'numero_carte' => !empty($_POST['numero_carte']) ? $_POST['numero_carte'] : null
                ]);
                break;

            case 'professeur':
                $stmt = $pdo->prepare("INSERT INTO Professeur (Id, date_embauche) VALUES (:userId, :date_embauche)");
                $stmt->execute([
                    'userId' => $userId,
                    'date_embauche' => $_POST['date_embauche']
                ]);
                break;

            case 'admin':
                $stmt = $pdo->prepare("INSERT INTO Administration (Id) VALUES (:userId)");
                $stmt->execute(['userId' => $userId]);
                break;

            case 'agent':
                $stmt = $pdo->prepare("INSERT INTO Agent (Id) VALUES (:userId)");
                $stmt->execute(['userId' => $userId]);
                break;
        }

        $pdo->commit();
        header('Location: ../pages/admin_users.php?success=edit');
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: ../pages/admin_users.php?error=edit');
        exit();
    }
}

header('Location: ../pages/admin_users.php');
exit(); 