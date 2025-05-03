<?php
require_once '../models/SystemLog.php';

header('Content-Type: application/json');

// Instantiate the controller class
$controller = new SystemLogsController();

// Route the request based on action
$action = $_GET['action'] ?? '';
if ($action === 'getLogs') {
    $controller->getLogs();
} elseif ($action === 'index') {
    $controller->index();
} elseif ($action === 'exportLogs') {
    $controller->exportLogs();
} elseif ($action === 'archiveLogs') {
    $controller->archiveLogs();

}else {
    http_response_code(404);
    echo json_encode(['error' => 'Invalid action']);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

class SystemLogsController {
    public function index() {
        $logModel = new SystemLog();
        $logs = $logModel->all();

        $logUser = $logModel->query("SELECT email FROM users WHERE user_id = " . (int)$logs[0]['user_id']);

        $logs = array_map(function($log) use ($logUser) {
            $log['user_email'] = $logUser[0]['email'] ?? 'Unknown';
            return $log;
        }, $logs);

        echo json_encode($logs);
    }

    public function getLogs() {
        // API endpoint for DataTables
        $params = $_GET;

        $logModel = new SystemLog();
        $result = $logModel->getFilteredLogs(
            isset($params['start']) ? $params['start'] : 0,
            isset($params['length']) ? $params['length'] : 10,
            isset($params['severity']) ? $params['severity'] : null,
            isset($params['module']) ? $params['module'] : null,
            isset($params['dateFrom']) ? $params['dateFrom'] : null,
            isset($params['dateTo']) ? $params['dateTo'] : null
        );

        echo json_encode([
            'draw' => (int)($params['draw'] ?? 1),
            'recordsTotal' => $result['total'],
            'recordsFiltered' => $result['filtered'],
            'data' => $result
        ]);
    }

    public function exportLogs() {
        // Generate CSV/Excel export
        $log = new SystemLog();
        $logs = $log->all();

        // CSV header
        $headers = [
            'Content-Type: text/csv',
            'Content-Disposition' => 'attachment; filename="logs_'.date('Y-m-d').'.csv"',
        ];

        // Create CSV content
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Level', 'Message', 'Created At']);
            
            // Add log rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->log_id,
                    $log->severity,
                    $log->message,
                    $log->timestamp
                ]);
            }
            
            fclose($file);
        };

        echo json_encode([
            'headers' => $headers,
            'callback' => $callback
        ]);
    }

    public function archiveLogs() {
        // Move old logs to archive
    }
}
?>