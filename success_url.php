<?php
// success_url.php

// Démarrer une session pour récupérer des informations stockées
session_start();

// Vérifiez si des informations de paiement ont été transmises
if (isset($_GET['transaction_id']) && isset($_GET['amount']) && isset($_GET['currency'])) {
    $transaction_id = $_GET['transaction_id'];
    $amount = $_GET['amount'];
    $currency = $_GET['currency'];
    
    // Afficher un message de succès
    echo "<h1>Paiement Réussi</h1>";
    echo "<p>Merci pour votre achat !</p>";
    echo "<p>ID de la transaction : " . htmlspecialchars($transaction_id) . "</p>";
    echo "<p>Montant : " . htmlspecialchars($amount) . " " . htmlspecialchars($currency) . "</p>";

    // Mettre à jour l'état de la commande dans la base de données
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // ID de la commande (peut-être stocké dans la session ou transmis en tant que paramètre)
    $order_id = $_SESSION['order_id'] ?? null;

    if ($order_id) {
        // Mettre à jour l'état de la commande
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', transaction_id = ? WHERE order_id = ?");
        $stmt->bind_param('si', $transaction_id, $order_id);

        if ($stmt->execute()) {
            echo "<p>L'état de votre commande a été mis à jour avec succès.</p>";
        } else {
            echo "<p>Erreur lors de la mise à jour de votre commande : " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>ID de commande non trouvé.</p>";
    }

    $conn->close();
} else {
    echo "<h1>Échec du paiement</h1>";
    echo "<p>Des informations nécessaires sont manquantes.</p>";
}
?>
