<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_complet = trim($_POST['nom']);
    $nom_complet = htmlspecialchars($nom_complet);

    $nom = $parts[0];
    $prenom = isset($parts[1]) ? $parts[1] : '';
    $email = htmlspecialchars(trim($_POST['email']));
    $sujet = htmlspecialchars(trim($_POST['sujet']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($message)) {

        $to = "tatusae2013@gmail.com";

        $subject = "Nouveau message de contact : $sujet";

        $body = "Vous avez reçu un nouveau message de contact :\n\n";
        $body .= "Nom : $nom\n";
        $body .= "Prénom : $prenom\n";
        $body .= "Objet : $sujet\n\n";
        $body .= "Message :\n$message\n";

        $headers = "From: no-reply@gestiondesmateriels.com\r\n";
        $headers .= "Reply-To: no-reply@gestiondesmateriels.com\r\n";

        if (mail($to, $subject, $body, $headers)) {
            header("Location: ../../HTML/success.html");
            exit();
        } else {
            header("Location: ../../HTML/error.html");
            exit();
        }
    } else {
        header("Location: ../../HTML/error.html");
        exit();
    }
} else {
    header("Location: ../../HTML/error.html");
    exit();
}
?>