<?php
require_once '../models/Backup.php';

class BackupController {
    public function index() {
        $backupModel = new Backup();
        $backups = $backupModel->getAllWithCreator();
        include '../views/admin/backup-restore.php';
    }

    public function createBackup() {
        // Logic to create a backup
        $backupModel = new Backup();
        $result = $backupModel->createBackup();
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Backup created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create backup.']);
        }
    }

    public function restoreBackup($id) {
        // Logic to restore a backup
        $backupModel = new Backup();
        $result = $backupModel->restoreBackup($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Backup restored successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to restore backup.']);
        }
    }

    public function deleteBackup($id) {
        // Logic to delete a backup
        $backupModel = new Backup();
        $result = $backupModel->deleteBackup($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Backup deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete backup.']);
        }
    }
}
?>