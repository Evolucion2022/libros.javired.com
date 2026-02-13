<?php
/**
 * GitHub Webhook Receiver for Auto-Deploy
 * 
 * URL: https://libros.javired.com/webhook-deploy.php
 * Configure this URL in GitHub repo Settings > Webhooks
 * Set the secret to match WEBHOOK_SECRET below
 */

// ============ CONFIGURATION ============
$WEBHOOK_SECRET = 'libros-deploy-2026-secret';
$DEPLOY_SCRIPT = '/home/yzibhssy/deploy.sh';
$LOG_FILE = '/home/yzibhssy/libros.javired.com/deploy.log';
// =======================================

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// Verify GitHub signature
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if (!empty($WEBHOOK_SECRET)) {
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $WEBHOOK_SECRET);
    if (!hash_equals($expected, $signature)) {
        http_response_code(403);
        die('Invalid signature');
    }
}

// Parse the event
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? 'unknown';
$data = json_decode($payload, true);

// Only deploy on push to main
if ($event === 'push') {
    $branch = $data['ref'] ?? '';
    if ($branch === 'refs/heads/main') {
        // Log the deploy trigger
        $logEntry = date('Y-m-d H:i:s') . " - Webhook triggered by push to main by " . ($data['pusher']['name'] ?? 'unknown') . "\n";
        file_put_contents($LOG_FILE, $logEntry, FILE_APPEND);

        // Execute deploy script in background
        exec("bash $DEPLOY_SCRIPT > /dev/null 2>&1 &");

        http_response_code(200);
        echo json_encode(['status' => 'ok', 'message' => 'Deploy triggered']);
    } else {
        http_response_code(200);
        echo json_encode(['status' => 'ignored', 'message' => 'Not main branch']);
    }
} else {
    http_response_code(200);
    echo json_encode(['status' => 'ignored', 'message' => "Event: $event"]);
}
