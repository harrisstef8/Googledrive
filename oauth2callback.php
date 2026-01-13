<?php
require __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secret.json');
$client->setRedirectUri('http://localhost/Googledrive/oauth2callback.php'); // ή https://yourdomain.com/oauth2callback.php
$client->addScope(Google_Service_Drive::DRIVE_READONLY);
$client->setAccessType('offline');
$client->setPrompt('consent');

$tokenPath = __DIR__ . '/token.json';

// Αν δεν υπάρχει code -> redirect για σύνδεση
if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
} else {
    // Λαμβάνουμε το token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    file_put_contents($tokenPath, json_encode($token));
    echo "✅ Authorization complete. Token saved!";
}
