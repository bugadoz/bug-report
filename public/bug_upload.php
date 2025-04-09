<?php
require_once __DIR__ . '/../src/BugReport.php';

use Bugadoz\BugReport;

$report = new BugReport('SUA_API_KEY', __DIR__ . '/../uploads');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $saved = $report->saveFile($_FILES['file']);
    echo json_encode(['status' => $saved ? 'success' : 'error', 'file' => $saved]);
} else {
    echo json_encode(['status' => 'no_file']);
}
