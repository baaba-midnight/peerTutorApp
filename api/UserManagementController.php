<?php 
require_once '../models/User.php';
require_once '../config/Database.php';

header('Content-Type: application/json');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $userController = new UserController();

    switch ($action) {
        case 'getUsers':
            $userController->getUsers();
            break;
        case "getFilteredUsers":
            $userController->getFilteredUsers();
            break;
        case 'searchUsers':
            $userController->searchUsers();
            break;
        case 'addUser':
            $userController->addUser();
            break;
        case 'updateUser':
            $userController->updateUser();
            break;
        case 'deleteUser':
            $userController->deleteUser();
            break;
        case 'getUserById':
            $userController->getUserById();
            break;
        case 'activateUser':
            $userController->activateUser();
            break;
        case 'deactivateUser':
            $userController->deactivateUser();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
}

// Define the UserController class
class UserController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->userModel = new User($db);
    }

    public function getUsers() {
        $users = $this->userModel->getAllUsers();
        echo json_encode($users);
    }

    public function getFilteredUsers() {
        $params = $_GET;

        $users = $this->userModel->getFilteredUsers(
            isset($params['start']) ? $params['start'] : 0,
            isset($params['length']) ? $params['length'] : 10,
            isset($params['filter']) ? $params['filter'] : '',
            isset($params['search']) ? $params['search'] : '',
        );
        echo json_encode($users);
    }

    public function searchUsers() {
        $searchTerm = json_decode(file_get_contents("php://input"), true);
        $users = $this->userModel->searchUsers($searchTerm);
        echo json_encode($users);
    }

    public function addUser() {
        // $data = json_decode(file_get_contents("php://input"), true);

        $first_name = $_POST['firstName'] ?? null;
        $last_name = $_POST['lastName'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirm_password = $_POST['confirmPassword'] ?? null;
        $role = $_POST['role'] ?? null;
        $phone_number = $_POST['phone'] ?? null; 
        $profile_picture_url = $_POST['profilePicture'] ?? null;
        $subjects = $_POST['subjects'] ?? null;

        // add check for email, password, role, and other fields
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($role)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            return;
        }

        if (strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters']);
            return;
        }

        if ($password !== $confirm_password) {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
            return;
        }

        $result = $this->userModel->register($first_name, $last_name, $email, $password, $role, $phone_number, null, $profile_picture_url, $subjects);
        if ($result['status'] === 'success') {
            $result = [
                'status' => 'success',
                'message' => 'User added successfully'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Failed to add user'
            ];
        }
        echo json_encode($result);
    }

    public function updateUser() {
        $userId = $_POST['user_id'] ?? null;
        $first_name = $_POST['firstName'] ?? null;
        $last_name = $_POST['lastName'] ?? null;
        $email = $_POST['email'] ?? null;
        $role = $_POST['role'] ?? null;
        $phone_number = $_POST['phone'] ?? null; 
        $profile_picture_url = $_POST['profilePicture'] ?? null;
        $subjects = $_POST['subjects'] ?? null;
        $bio = $_POST['bio'] ?? null;

        $data = [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "role" => $role,
            "phone_number" => $phone_number,
            "profile_picture_url" => $profile_picture_url,
            "subjects" => $subjects,
            "bio" => $bio
        ];

        if (empty($userId)) {
            echo json_encode(['status' => 'error', 'message' => 'No user ID provided']);
            return;
        }

        $result = $this->userModel->updateUser($userId, $data);

        echo json_encode($result);
    }

    public function deleteUser() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->userModel->deleteUser($data['user_id']);
        echo json_encode($result);
    }

    public function getUserById() {
        $data = (int)$_GET['user_id'] ?? null;
        $user = $this->userModel->getUserById($data);
        echo json_encode([
            "user" => $user,
            "success" => true,
        ]);
    }

    public function activateUser() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->userModel->activateUser($data['user_id']);
        echo json_encode($result);
    }

    public function deactivateUser() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->userModel->deactivateUser($data['user_id']);
        echo json_encode($result);
    }
}