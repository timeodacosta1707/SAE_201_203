<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $objet = htmlspecialchars(trim($_POST['objet']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($nom) && !empty($prenom) && !empty($objet) && !empty($message)) {

        $to = "tatusae2013@gmail.com";

        $subject = "Nouveau message de contact : $objet";

        $body = "Vous avez reçu un nouveau message de contact :\n\n";
        $body .= "Nom : $nom\n";
        $body .= "Prénom : $prenom\n";
        $body .= "Objet : $objet\n\n";
        $body .= "Message :\n$message\n";

        $headers = "From: no-reply@votre-site.com\r\n";
        $headers .= "Reply-To: no-reply@votre-site.com\r\n";

        if (mail($to, $subject, $body, $headers)) {
            header("Location: ../HTML/success.html");
            exit();
        } else {
            header("Location: ../HTML/error.html");
            exit();
        }
    } else {
        header("Location: ../HTML/error.html");
        exit();
    }
} else {
    header("Location: ../HTML/error.html");
    exit();
}
?>