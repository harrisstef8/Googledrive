<?php
require __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secret.json');
$client->addScope(Google_Service_Drive::DRIVE_READONLY);
$client->setAccessType('offline');

$tokenPath = __DIR__ . '/token.json';
if (!file_exists($tokenPath)) {
    die("⚠️ Please run oauth2callback.php first to authorize.");
}

$token = json_decode(file_get_contents($tokenPath), true);
$client->setAccessToken($token);

if ($client->isAccessTokenExpired()) {
    if (isset($token['refresh_token'])) {
        $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    } else {
        die("⚠️ Token expired and no refresh token available.");
    }
}

$service = new Google_Service_Drive($client);

// Get folders in root
$folders = [];
$pageToken = null;
do {
    $response = $service->files->listFiles([
        'q' => "mimeType='application/vnd.google-apps.folder' and 'root' in parents and trashed=false",
        'fields' => 'nextPageToken, files(id, name, webViewLink)',
        'orderBy' => 'name', // 👉 Ταξινόμηση αλφαβητικά
        'pageToken' => $pageToken
    ]);
    foreach ($response->getFiles() as $f) {
        $folders[] = $f;
    }
    $pageToken = $response->getNextPageToken();
} while ($pageToken);

// Filter example (optional)
$allowedFolders = ['Test_Access1', 'Test_Access2', 'Test_Access3'];
$filtered = array_filter($folders, fn($f) => in_array($f->getName(), $allowedFolders));

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Drive Folders</title>
<style>
body {
  font-family: Arial, sans-serif;
  padding: 20px;
}

.folder-list {
  list-style: none;
  padding: 0;
  margin: 0;
  max-width: 500px; /* 👉 μικραίνει το πλάτος των κουτιών */
}

.folder-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 14px;
  margin-bottom: 8px;
  background: #f7f7f7;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.12s ease;
  text-decoration: none;
  color: inherit;
  border: 1px solid #e2e2e2;
}

.folder-item:hover {
  background: #eef6ff;
}

.folder-name {
  font-weight: 600;
}

.open-icon {
  font-size: 0.9em;
  color: #0366d6;
}
</style>
</head>
<body>
<h2>My Drive Folders</h2>

<ul class="folder-list">
<?php foreach ($filtered as $folder):
    $link = $folder->getWebViewLink() ?: "https://drive.google.com/drive/folders/" . $folder->getId();
?>
    <li class="folder-item" onclick="window.open('<?php echo htmlspecialchars($link); ?>','_blank')">
        <div>
            <span class="folder-name"><?php echo htmlspecialchars($folder->getName()); ?></span>
        </div>
        <div class="open-icon">Άνοιγμα ↗</div>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>
