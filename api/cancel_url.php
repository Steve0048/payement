<?php
// cancel_url.php

// Démarrer une session pour récupérer des informations stockées
session_start();

// Vérifiez si des informations sur la commande sont disponibles dans la session
$order_id = $_SESSION['order_id'] ?? null;

if ($order_id) {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // Mettre à jour l'état de la commande pour refléter l'annulation
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'cancelled' WHERE order_id = ?");
    $stmt->bind_param('i', $order_id);

    if ($stmt->execute()) {
        echo "<h1>Paiement Annulé</h1>";
        echo "<p>Votre paiement a été annulé. Si vous avez des questions, veuillez nous contacter.</p>";
    } else {
        echo "<p>Erreur lors de la mise à jour de l'état de votre commande : " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<h1>Paiement Annulé</h1>";
    echo "<p>Nous n'avons pas pu trouver les informations de votre commande. Si vous avez des questions, veuillez nous contacter.</p>";
}
?>
