<?php
require_once('config.php');
require_once('function_db.php');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 10;

$logs = getLogsByPage($page, $perPage);
$totalCount = getTotalLogCount(); // 你需要實作這個函式

$hasMore = ($page * $perPage) < $totalCount;

header('Content-Type: application/json');
echo json_encode([
    'logs' => $logs,
    'has_more' => $hasMore,
]);
exit;
