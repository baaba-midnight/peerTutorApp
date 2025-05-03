<?php
require_once 'Model.php';

class SystemLog extends Model {
    protected $table = 'system_logs';

    public function getFilteredLogs($start, $length, $severity, $module, $dateFrom, $dateTo) {
        
        // Base query
        $baseQuery = "FROM {$this->table}";
        $where = [];
        $params = [];
    
        // Build WHERE conditions (same as before)
        if ($severity) {
            $where[] = "severity = ?";
            $params[] = $severity;
        }
        
        if ($module) {
            $where[] = "module = ?";
            $params[] = $module;
        }

        if ($dateFrom) {
            $where[] = "timestamp >= ?";
            $params[] = $dateFrom . " 00:00:00";
        }

        if ($dateTo) {
            $where[] = "timestamp <= ?";
            $params[] = $dateTo . " 23:59:59";
        }
    
        // Add WHERE clause if we have conditions
        if (!empty($where)) {
            $baseQuery .= " WHERE " . implode(" AND ", $where);
        }
    
        // Get total count
        $countQuery = "SELECT COUNT(*) as total {$baseQuery}";
        $totalResult = $this->query($countQuery, $params);

        $total = $totalResult[0]['total'];
    
        // Get paginated data
        $dataQuery = "SELECT * {$baseQuery} ORDER BY timestamp DESC LIMIT "  . (int)$start . ", " . (int)$length;
        $data = $this->query($dataQuery, $params);
            
        foreach ($data as &$log) {
            // Fetch user email for each log entry
            $userQuery = "SELECT email FROM users WHERE user_id = ?";
            $userResult = $this->query($userQuery, [$log['user_id']]);
            $log['user_email'] = $userResult[0]['email'] ?? 'Unknown';
        }

        return [
            'data' => $data,
            'total' => $total,
            'filtered' => $total // Same as total unless you have different filtered count
        ];
    }
}


?>