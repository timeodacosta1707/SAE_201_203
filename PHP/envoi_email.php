<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
        header("Location:../HTML/error.html");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location:../HTML/error.html");
        exit;
    }

    $to = "tatusae2013@gmail.com";
    $subject = "Nouveau message de $firstName $lastName";
    $emailContent = "Prénom: $firstName\n";
    $emailContent .= "Nom: $lastName\n";
    $emailContent .= "Adresse e-mail: $email\n\n";
    $emailContent .= "Message:\n$message";

    $headers = "From: no-reply@ton-domaine.com\r\n"; 
    $headers .= "Reply-To: $email\r\n"; 
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $emailContent, $headers)) {
        header("Location:../HTML/success.html");
        exit;
    } else {
        header("Location:../HTML/error.html");
        exit;
    }
} else {
    header("Location:../HTML/error.html");
    exit;
}
?>