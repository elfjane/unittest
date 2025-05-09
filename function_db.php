<?php
function wLogToSQLite($filename, $logData, $url, $getAt)
{
    date_default_timezone_set(CRON_LOG_TIMEZONE);

    // 準備 log 內容
    if (is_array($logData)) {
        $log = json_encode($logData, JSON_UNESCAPED_UNICODE);
    } else {
        $log = $logData;
    }

    // SQLite 資料庫路徑
    $dbPath = CRON_BASE_PATH . "/logs/logs.sqlite";

    // 建立資料夾（如果不存在）
    $dirPath = dirname($dbPath);
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
    }

    // 連接 SQLite
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 建立 logs 資料表（包含 created_at）
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            filename TEXT,            
            url TEXT,
            get_at TEXT,
            log TEXT,
            created_at TEXT DEFAULT (datetime('now', 'localtime'))
        )
    ");

    // 寫入 log 資料
    $stmt = $pdo->prepare("
        INSERT INTO logs (filename, get_at, log, url) VALUES (:filename, :getAt, :log, :url)
    ");
    $stmt->execute([
        ':url' => $url,        
        ':filename' => $filename,
        ':getAt' => $getAt,
        ':log' => $log
    ]);
}

function getLatestLogs($limit = 3)
{
    $dbPath = CRON_BASE_PATH . "/logs/logs.sqlite";

    if (!file_exists($dbPath)) {
        return []; // 如果資料庫不存在，回傳空陣列
    }

    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT * FROM logs
        ORDER BY datetime(created_at) DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLogsByPage($page = 1, $perPage = 10)
{
    $dbPath = CRON_BASE_PATH . "/logs/logs.sqlite";

    if (!file_exists($dbPath)) {
        return []; // 如果資料庫不存在，回傳空陣列
    }

    // 計算 offset
    $offset = ($page - 1) * $perPage;

    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT * FROM logs
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalLogCount()
{
    $dbPath = CRON_BASE_PATH . "/logs/logs.sqlite";
    if (!file_exists($dbPath)) {
        return 0;
    }

    $pdo = new PDO("sqlite:" . $dbPath);
    $stmt = $pdo->query("SELECT COUNT(*) FROM logs");
    return (int) $stmt->fetchColumn();
}