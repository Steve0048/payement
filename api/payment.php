<?php

echo "Script bien déployé"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Paramètres de la requête
    $parameters = [
        'identifier' => $data['id'],
        'currency' => $data['currency'],
        'amount' => $data['amount'],
        'details' => $data['details'],
        'ipn_url' => 'https://payement.vercel.app/api/ipn_url.php',
        'cancel_url' => 'https://payement.vercel.app/api/cancel_url.php',
        'success_url' => 'https://payement.vercel.app/api/success_url.php',
        'public_key' => 'test_p6qr4pv8i8vaagp10a52cmju2mryphmlk1acdjt5bbr9ldihcg189',
        'site_name' => 'No Trade Investment',
        'checkout_theme' => 'dark',
        'customer' => $data['customer']
    ];

    $parameters = http_build_query($parameters);

    // Endpoint de test
    $url = 'https://pay.umva.us/test/payment/initiate';

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    // Retourner le résultat en JSON
    header('Content-Type: application/json');
    echo $result;
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
