<?php
// Recevoir les données de la requête IPN
$raw_post_data = file_get_contents('php://input');
$ipn_data = json_decode($raw_post_data, true);

// Effectuer des vérifications de sécurité (signature, identifiant, etc.)
// Exemple simple de vérification (à adapter selon vos besoins)
$expected_signature = strtoupper(hash_hmac('sha256', $ipn_data['identifier'] . $ipn_data['amount'], 'YOUR_SECRET_KEY'));

if ($ipn_data['signature'] == $expected_signature && $ipn_data['status'] == 'success') {
    // Mise à jour de la base de données pour marquer la commande comme payée
    // Exemple de mise à jour (à adapter selon votre structure de base de données)
    $order_id = $ipn_data['order_id'];
    $payment_status = $ipn_data['status'];
    $payment_amount = $ipn_data['amount'];

    // Connexion à la base de données et mise à jour de la commande
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE orders SET payment_status='$payment_status', payment_amount='$payment_amount' WHERE order_id='$order_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Order updated successfully";
    } else {
        echo "Error updating order: " . $conn->error;
    }

    $conn->close();
} else {
    // Enregistrer les tentatives de notification échouées pour analyse
    error_log('Invalid IPN received: ' . $raw_post_data);
}

header("HTTP/1.1 200 OK");
?>
