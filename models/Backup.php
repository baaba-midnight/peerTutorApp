<?php
require_once '../config/Database.php';

class Backup extends Model {
    protected $table = "backup_records";

    // get all backups with creator info
    public function getAllWithCreator() {
        $query = "SELECT b.*, u.first_name, u.last_name
                  FROM {$this->table} b
                  JOIN users u ON b.created_by = u.id
                  ORDER BY b.created_at DESC";
        return $this->query($query);
    }

    // Create a new backup
    public function createBackup() {
        $query = "INSERT INTO {$this->table} (file_name, created_by, created_at) VALUES (?, ?, NOW())";
        $stmt = $this->query($query, [
            'file_name' => $this->generateBackupFileName(),
            'created_by' => $_SESSION['user_id']
        ]);
    }

    // Restore a backup
    public function restoreBackup($id) {
        $query = "SELECT file_name FROM {$this->table} WHERE id = ?";
        $backup = $this->query($query, [$id]);
        if ($backup) {
            $fileName = $backup[0]['file_name'];
            $filePath = "../backups/" . $fileName;

            if (file_exists($filePath)) {
                $command = 
            }
            return true;
        }
        return false;
    }

    // Delete a backup
    public function deleteBackup($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->query($query, ['id' => $id]);
        
        if ($stmt) {
            return [
                'success' => true,
                'message' => 'Backup deleted successfully.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to delete backup.'
            ];
        }
    }

    // Generate a unique backup file name
    private function generateBackupFileName() {
        return 'backup_' . date('Ymd_His') . '.sql';
    }
}
?>