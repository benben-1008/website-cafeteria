<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$dataFile = '../data/reservation-times.json';

// デフォルトの予約時間設定
$defaultTimes = [
    'startTime' => '11:30',
    'endTime' => '12:45',
    'enabled' => true,
    'message' => '予約時間: 11:30-12:45'
];

// データファイルが存在しない場合はデフォルト設定を作成
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode($defaultTimes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 予約時間設定を取得
    $data = json_decode(file_get_contents($dataFile), true);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 予約時間設定を更新
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input) {
        $data = [
            'startTime' => $input['startTime'] ?? $defaultTimes['startTime'],
            'endTime' => $input['endTime'] ?? $defaultTimes['endTime'],
            'enabled' => $input['enabled'] ?? true,
            'message' => $input['message'] ?? $defaultTimes['message']
        ];
        
        file_put_contents($dataFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data'], JSON_UNESCAPED_UNICODE);
    }
    
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
}
?>
