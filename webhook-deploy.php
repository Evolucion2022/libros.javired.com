<?php
/**
 * =====================================================
 * AUTO-DEPLOY: libros.javired.com
 * =====================================================
 * 
 * Este archivo recibe webhooks de GitHub y despliega
 * automáticamente. NO requiere Git ni SSH en el servidor.
 * 
 * Descarga el ZIP del repo, extrae themes/plugins/.htaccess
 * y los copia al directorio del sitio.
 * 
 * CONFIGURACIÓN EN GITHUB:
 * - Webhook URL: https://libros.javired.com/webhook-deploy.php
 * - Content type: application/json
 * - Secret: libros-deploy-2026-secret
 * - Events: Just the push event
 * =====================================================
 */

// ============ CONFIGURACIÓN ============
$WEBHOOK_SECRET = 'libros-deploy-2026-secret';
$GITHUB_REPO = 'Evolucion2022/libros.javired.com';
$BRANCH = 'main';
$DEPLOY_DIR = '/home/yzibhssy/libros.javired.com';
$TEMP_DIR = '/home/yzibhssy/tmp/deploy-' . time();
$LOG_FILE = '/home/yzibhssy/libros.javired.com/deploy.log';

// Archivos/carpetas a desplegar (origen => destino relativo al DEPLOY_DIR)
$DEPLOY_PATHS = [
    'wp-content/themes' => 'wp-content/themes',
    'wp-content/plugins' => 'wp-content/plugins',
    '.htaccess' => '.htaccess',
];

// DB config (solo si hay cambios en database/)
$DB_NAME = 'yzibhssy_libros1';
$DB_USER = 'yzibhssy_libros1';
$DB_PASS = 'SPep0[81@9';
$DB_HOST = 'localhost';
// =======================================

// --- Helper: Log ---
function deployLog($msg)
{
    global $LOG_FILE;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($LOG_FILE, "[$timestamp] $msg\n", FILE_APPEND);
}

// --- Helper: Delete directory recursively ---
function deleteDir($dir)
{
    if (!is_dir($dir))
        return;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..')
            continue;
        $path = $dir . '/' . $item;
        is_dir($path) ? deleteDir($path) : unlink($path);
    }
    rmdir($dir);
}

// --- Helper: Copy directory recursively ---
function copyDir($src, $dst)
{
    if (!is_dir($src))
        return false;
    if (!is_dir($dst))
        mkdir($dst, 0755, true);
    $dir = opendir($src);
    while (($file = readdir($dir)) !== false) {
        if ($file === '.' || $file === '..')
            continue;
        $srcPath = $src . '/' . $file;
        $dstPath = $dst . '/' . $file;
        if (is_dir($srcPath)) {
            copyDir($srcPath, $dstPath);
        } else {
            copy($srcPath, $dstPath);
        }
    }
    closedir($dir);
    return true;
}

// ============ MAIN LOGIC ============

// Allow manual trigger via GET with a key
$isManual = ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['key']) && $_GET['key'] === $WEBHOOK_SECRET);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Webhook mode ---
    $payload = file_get_contents('php://input');

    // Verify GitHub signature
    if (!empty($WEBHOOK_SECRET)) {
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
        $expected = 'sha256=' . hash_hmac('sha256', $payload, $WEBHOOK_SECRET);
        if (!hash_equals($expected, $signature)) {
            http_response_code(403);
            deployLog("ERROR: Invalid webhook signature");
            die('Invalid signature');
        }
    }

    // Only process push events to main
    $event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? 'unknown';
    $data = json_decode($payload, true);

    if ($event !== 'push') {
        http_response_code(200);
        echo json_encode(['status' => 'ignored', 'event' => $event]);
        exit;
    }

    $branch = $data['ref'] ?? '';
    if ($branch !== 'refs/heads/' . $BRANCH) {
        http_response_code(200);
        echo json_encode(['status' => 'ignored', 'branch' => $branch]);
        exit;
    }

    $pusher = $data['pusher']['name'] ?? 'unknown';
    deployLog("=== Deploy triggered by webhook (push by $pusher) ===");

} elseif ($isManual) {
    // --- Manual mode ---
    deployLog("=== Deploy triggered manually ===");
    header('Content-Type: text/plain');

} else {
    http_response_code(405);
    die('Method not allowed. Use POST (webhook) or GET with ?key=SECRET');
}

// --- Step 1: Download the ZIP from GitHub ---
$zipUrl = "https://github.com/$GITHUB_REPO/archive/refs/heads/$BRANCH.zip";
$zipFile = sys_get_temp_dir() . '/deploy-' . time() . '.zip';

deployLog("Downloading: $zipUrl");

$ch = curl_init($zipUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_USERAGENT, 'LibrosDeploy/1.0');
$zipData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode !== 200 || empty($zipData)) {
    $msg = "ERROR: Failed to download ZIP (HTTP $httpCode). $error";
    deployLog($msg);
    if ($isManual)
        echo $msg;
    http_response_code(500);
    exit;
}

file_put_contents($zipFile, $zipData);
$zipSize = round(strlen($zipData) / 1024 / 1024, 2);
deployLog("Downloaded ZIP: {$zipSize}MB");

// --- Step 2: Extract the ZIP ---
$zip = new ZipArchive();
if ($zip->open($zipFile) !== true) {
    $msg = "ERROR: Failed to open ZIP file";
    deployLog($msg);
    if ($isManual)
        echo $msg;
    unlink($zipFile);
    http_response_code(500);
    exit;
}

// Create temp directory
if (!is_dir($TEMP_DIR))
    mkdir($TEMP_DIR, 0755, true);
$zip->extractTo($TEMP_DIR);
$zip->close();
unlink($zipFile);

// GitHub ZIP has a root folder like "libros.javired.com-main/"
$extractedDirs = glob($TEMP_DIR . '/*', GLOB_ONLYDIR);
$repoRoot = $extractedDirs[0] ?? $TEMP_DIR;

deployLog("Extracted to: $repoRoot");

// --- Step 3: Deploy files ---
$deployedCount = 0;

foreach ($DEPLOY_PATHS as $source => $destination) {
    $srcPath = $repoRoot . '/' . $source;
    $dstPath = $DEPLOY_DIR . '/' . $destination;

    if (is_dir($srcPath)) {
        deployLog("Syncing directory: $source -> $destination");
        copyDir($srcPath, $dstPath);
        $deployedCount++;
    } elseif (is_file($srcPath)) {
        deployLog("Syncing file: $source -> $destination");
        $dstDir = dirname($dstPath);
        if (!is_dir($dstDir))
            mkdir($dstDir, 0755, true);
        copy($srcPath, $dstPath);
        $deployedCount++;
    } else {
        deployLog("SKIP: $source not found in repo");
    }
}

// --- Step 4: Check for DB migration ---
$sqlDir = $repoRoot . '/database';
if (is_dir($sqlDir)) {
    $sqlFiles = glob($sqlDir . '/*.sql');
    if (!empty($sqlFiles)) {
        rsort($sqlFiles);
        $latestSql = $sqlFiles[0];
        $currentHash = md5_file($latestSql);
        $hashFile = $DEPLOY_DIR . '/.last_sql_hash';
        $storedHash = file_exists($hashFile) ? trim(file_get_contents($hashFile)) : '';

        if ($currentHash !== $storedHash) {
            deployLog("Database changed, importing: " . basename($latestSql));
            $cmd = "mysql -h $DB_HOST -u $DB_USER -p'$DB_PASS' $DB_NAME < " . escapeshellarg($latestSql) . " 2>&1";
            $output = shell_exec($cmd);
            if ($output)
                deployLog("MySQL output: $output");
            file_put_contents($hashFile, $currentHash);
            deployLog("Database import complete");
        } else {
            deployLog("Database unchanged, skipping");
        }
    }
}

// --- Step 5: Cleanup ---
deleteDir($TEMP_DIR);
deployLog("Deployed $deployedCount items successfully");
deployLog("=== Deploy complete ===");

// --- Response ---
$response = [
    'status' => 'ok',
    'deployed' => $deployedCount,
    'message' => "Deployed $deployedCount items successfully"
];

if ($isManual) {
    echo "Deploy complete!\n";
    echo "Items deployed: $deployedCount\n";
    echo "Check deploy.log for details.\n";
} else {
    http_response_code(200);
    echo json_encode($response);
}
