<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user with profile and tutor profile if applicable
    public function register($first_name, $last_name, $email, $password, $role, $phone_number = null, $bio = null, $profile_picture_url = null, $subjects = null) {
        try {
            // Begin transaction for multiple table operations
            $this->conn->beginTransaction();
            
            // Check if user already exists
            $checkQuery = "SELECT user_id FROM Users WHERE email = :email";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                return array("status" => "error", "message" => "User already exists with this email.");
            }

            // Hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insertQuery = "INSERT INTO Users (email, password_hash, first_name, last_name, role, phone_number) 
                            VALUES (:email, :password_hash, :first_name, :last_name, :role, :phone_number)";
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password_hash', $password_hash);
            $insertStmt->bindParam(':first_name', $first_name);
            $insertStmt->bindParam(':last_name', $last_name);
            $insertStmt->bindParam(':role', $role);
            $insertStmt->bindParam(':phone_number', $phone_number);
            $insertStmt->execute();
            
            // Get the newly inserted user_id
            $user_id = $this->conn->lastInsertId();
            
            // Create profile record
            $profileQuery = "INSERT INTO Profiles (user_id, bio, profile_picture_url) 
                            VALUES (:user_id, :bio, :profile_picture_url)";
            $profileStmt = $this->conn->prepare($profileQuery);
            $profileStmt->bindParam(':user_id', $user_id);
            $profileStmt->bindParam(':bio', $bio);
            $profileStmt->bindParam(':profile_picture_url', $profile_picture_url);
            $profileStmt->execute();
            
            // If role is tutor, create tutor profile
            if ($role === 'tutor') {
                $tutorQuery = "INSERT INTO TutorProfiles (user_id) VALUES (:user_id)";
                $tutorStmt = $this->conn->prepare($tutorQuery);
                $tutorStmt->bindParam(':user_id', $user_id);
                $tutorStmt->execute();
                
                // If subjects were provided and we have a subjects table/relationship
                if ($subjects && is_array($subjects)) {
                    $this->assignTutorSubjects($user_id, $subjects);
                }
            }
            
            // Commit the transaction
            $this->conn->commit();
            
            return array(
                "status" => "success", 
                "message" => "Registration successful.",
                "user_id" => $user_id
            );
            
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            return array("status" => "error", "message" => "Registration failed: " . $e->getMessage());
        }
    }

    // Assign subjects to a tutor
    private function assignTutorSubjects($user_id, $subjects) {
        foreach ($subjects as $subject_id) {
            $query = "INSERT INTO TutorCourses (tutor_id, course_id) VALUES (:tutor_id, :course_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tutor_id', $user_id);
            $stmt->bindParam(':course_id', $subject_id);
            $stmt->execute();
        }
    }

    // Login user
    public function login($email, $password) {
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.password_hash, u.email, u.role,
                         p.profile_picture_url 
                  FROM Users u 
                  LEFT JOIN Profiles p ON u.user_id = p.user_id
                  WHERE u.email = :email AND u.is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // $this->updateLastLogin($row['user_id']);
                
            //     return array(
            //         "status" => "success",
            //         "user" => array(
            //             "user_id" => $row['user_id'],
            //             "full_name" => $row['first_name'] . " " . $row['last_name'],
            //             "email" => $row['email'],
            //             "role" => $row['role'],
            //             "profile_picture" => $row['profile_picture_url']
            //         )
            //     );

            if (password_verify($password, $row['password_hash'])) {
                // Update last login time
                $this->updateLastLogin($row['user_id']);
                
                return array(
                    "status" => "success",
                    "user" => array(
                        "user_id" => $row['user_id'],
                        "full_name" => $row['first_name'] . " " . $row['last_name'],
                        "email" => $row['email'],
                        "role" => $row['role'],
                        "profile_picture" => $row['profile_picture_url']
                    )
                );
            } else {
                return array("status" => "error", "message" => "Incorrect password.");
            }
        } else {
            return array("status" => "error", "message" => "User not found or account is inactive.");
        }
    }

    // Update last login timestamp
    private function updateLastLogin($user_id) {
        $query = "UPDATE Users SET last_login = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    // Get all users
    public function getAllUsers() {
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.email, u.role, u.is_active,
                         p.profile_picture_url, u.last_login, u.created_at
                  FROM Users u
                  LEFT JOIN Profiles p ON u.user_id = p.user_id
                  ORDER BY u.user_id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($users as $user) {
            $data[] = array(
                "user_id" => $user['user_id'],
                "first_name" => $user['first_name'],
                "last_name" => $user['last_name'],
                "email" => $user['email'],
                "role" => $user['role'],
                "is_active" => $user['is_active'] ? 'Active' : 'Inactive',
                "profile_picture_url" => $user['profile_picture_url'],
                "last_login" => $user['last_login'],
                "joined_date" => $user['created_at']
            );
        }

       return [
            "data" => $data,
            "total"=> count($data),
            "filtered" => count($data) // Assuming no filtering for now
       ]; 
    }

    // Get user by ID with profile information
    public function getUserById($user_id) {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, u.phone_number, u.role, u.is_active,
                         p.bio, p.profile_picture_url, p.department, p.year_of_study
                  FROM Users u
                  LEFT JOIN Profiles p ON u.user_id = p.user_id
                  WHERE u.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If user is a tutor, get tutor profile info
        if ($user && $user['role'] === 'tutor') {
            $tutorQuery = "SELECT hourly_rate, overall_rating, is_verified
                          FROM TutorProfiles
                          WHERE user_id = :user_id";
            $tutorStmt = $this->conn->prepare($tutorQuery);
            $tutorStmt->bindParam(':user_id', $user_id);
            $tutorStmt->execute();
            
            $tutorInfo = $tutorStmt->fetch(PDO::FETCH_ASSOC);
            if ($tutorInfo) {
                $user = array_merge($user, $tutorInfo);
            }
        }
        
        return $user;
    }

    public function getFilteredUsers($start, $length, $filter = '', $search = '') {
        $whereClauses = [];
        $params = [
            ':start' => (int)$start,
            ':length' => (int)$length,
        ];
    
        // Filter clause
        if (!empty($filter)) {
            $whereClauses[] = "(u.role LIKE :filter OR u.is_active LIKE :filter)";
            $params[':filter'] = "%$filter%";
        }
    
        // Search clause
        if (!empty($search)) {
            $whereClauses[] = "(u.first_name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search)";
            $params[':search'] = "%$search%";
        }
    
        $where = '';
        if (count($whereClauses)) {
            $where = 'WHERE ' . implode(' AND ', $whereClauses);
        }
    
        // Data query
        $dataQuery = "
            SELECT u.user_id, u.first_name, u.last_name, u.email, u.role, u.is_active,
                   p.profile_picture_url, u.last_login, u.created_at
            FROM Users u
            LEFT JOIN Profiles p ON u.user_id = p.user_id
            $where
            ORDER BY u.user_id DESC
            LIMIT :start, :length
        ";
    
        $stmt = $this->conn->prepare($dataQuery);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Total count
        $totalStmt = $this->conn->prepare("SELECT COUNT(*) FROM Users");
        $totalStmt->execute();
        $total = $totalStmt->fetchColumn();
    
        // Filtered count
        $filteredCountQuery = "
            SELECT COUNT(*) 
            FROM Users u
            $where
        ";
        $filteredStmt = $this->conn->prepare($filteredCountQuery);
        foreach ($params as $key => $value) {
            if ($key !== ':start' && $key !== ':length') {
                $filteredStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        }
        $filteredStmt->execute();
        $filteredTotal = $filteredStmt->fetchColumn();
    
        return [
            'data' => $users,
            'total' => $total,
            'filteredTotal' => $filteredTotal
        ];
    }
      

    // Update user profile including Profiles table
    public function updateUser($user_id, $data) {
        try {
            $this->conn->beginTransaction();
            
            // Update Users table
            if (isset($data['first_name']) || isset($data['last_name']) || isset($data['phone_number'])) {
                $updateFields = [];
                $params = [':user_id' => $user_id];
                
                if (isset($data['first_name'])) {
                    $updateFields[] = "first_name = :first_name";
                    $params[':first_name'] = $data['first_name'];
                }
                
                if (isset($data['last_name'])) {
                    $updateFields[] = "last_name = :last_name";
                    $params[':last_name'] = $data['last_name'];
                }
                
                if (isset($data['phone_number'])) {
                    $updateFields[] = "phone_number = :phone_number";
                    $params[':phone_number'] = $data['phone_number'];
                }
                
                if (!empty($updateFields)) {
                    $query = "UPDATE Users SET " . implode(", ", $updateFields) . " WHERE user_id = :user_id";
                    $stmt = $this->conn->prepare($query);
                    foreach ($params as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    $stmt->execute();
                }
            }
            
            // Update Profiles table
            if (isset($data['bio']) || isset($data['profile_picture_url']) || isset($data['department']) || isset($data['year_of_study'])) {
                // Check if profile exists
                $checkQuery = "SELECT profile_id FROM Profiles WHERE user_id = :user_id";
                $checkStmt = $this->conn->prepare($checkQuery);
                $checkStmt->bindParam(':user_id', $user_id);
                $checkStmt->execute();
                
                $profileExists = $checkStmt->rowCount() > 0;
                $profileFields = [];
                $profileParams = [':user_id' => $user_id];
                
                if (isset($data['bio'])) {
                    $profileFields[] = "bio = :bio";
                    $profileParams[':bio'] = $data['bio'];
                }
                
                if (isset($data['profile_picture_url'])) {
                    $profileFields[] = "profile_picture_url = :profile_picture_url";
                    $profileParams[':profile_picture_url'] = $data['profile_picture_url'];
                }
                
                if (isset($data['department'])) {
                    $profileFields[] = "department = :department";
                    $profileParams[':department'] = $data['department'];
                }
                
                if (isset($data['year_of_study'])) {
                    $profileFields[] = "year_of_study = :year_of_study";
                    $profileParams[':year_of_study'] = $data['year_of_study'];
                }
                
                if (!empty($profileFields)) {
                    if ($profileExists) {
                        $query = "UPDATE Profiles SET " . implode(", ", $profileFields) . " WHERE user_id = :user_id";
                    } else {
                        $fields = array_map(function($field) {
                            return explode(" = ", $field)[0];
                        }, $profileFields);
                        $placeholders = array_map(function($field) {
                            return ":" . explode(" = :", $field)[1];
                        }, $profileFields);
                        
                        $query = "INSERT INTO Profiles (user_id, " . implode(", ", $fields) . ") 
                                 VALUES (:user_id, " . implode(", ", $placeholders) . ")";
                    }
                    
                    $stmt = $this->conn->prepare($query);
                    foreach ($profileParams as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    $stmt->execute();
                }
            }
            
            // Update TutorProfiles if user is a tutor
            if (isset($data['role']) && $data['role'] === 'tutor') {
                if (isset($data['hourly_rate'])) {
                    $this->updateTutorProfile($user_id, $data);
                }
            }
            
            $this->conn->commit();
            return array("status" => "success", "message" => "Profile updated successfully");
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return array("status" => "error", "message" => "Update failed: " . $e->getMessage());
        }
    }

    // search for Users by name, email or course
    public function searchUsers($searchParams) {
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.email, u.role, u.is_active,
                         p.profile_picture_url
                  FROM Users u
                  LEFT JOIN Profiles p ON u.user_id = p.user_id
                  WHERE 1=1";
        
        $params = [];

        if (!empty($searchParams['name'])) {
            $query .= " AND (u.first_name LIKE :name OR u.last_name LIKE :name)";
            $params[':name'] = "%" . $searchParams['name'] . "%";
        }

        if (!empty($searchParams['email'])) {
            $query .= " AND u.email LIKE :email";
            $params[':email'] = "%" . $searchParams['email'] . "%";
        }

        if (!empty($searchParams['course'])) {
            $query .= " AND EXISTS (SELECT 1 FROM TutorCourses tc WHERE tc.tutor_id = u.user_id AND tc.course_id = :course)";
            $params[':course'] = $searchParams['course'];
        }

        // Add sorting
        $query .= " ORDER BY u.user_id DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    // Update tutor profile
    private function updateTutorProfile($user_id, $data) {
        // Check if tutor profile exists
        $checkQuery = "SELECT tutor_profile_id FROM TutorProfiles WHERE user_id = :user_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->execute();
        
        $profileExists = $checkStmt->rowCount() > 0;
        $updateFields = [];
        $params = [':user_id' => $user_id];
        
        if (isset($data['hourly_rate'])) {
            $updateFields[] = "hourly_rate = :hourly_rate";
            $params[':hourly_rate'] = $data['hourly_rate'];
        }
        
        if (isset($data['availability_schedule'])) {
            $updateFields[] = "availability_schedule = :availability_schedule";
            $params[':availability_schedule'] = json_encode($data['availability_schedule']);
        }
        
        if (!empty($updateFields)) {
            if ($profileExists) {
                $query = "UPDATE TutorProfiles SET " . implode(", ", $updateFields) . " WHERE user_id = :user_id";
            } else {
                $fields = array_map(function($field) {
                    return explode(" = ", $field)[0];
                }, $updateFields);
                $placeholders = array_map(function($field) {
                    return ":" . explode(" = :", $field)[1];
                }, $updateFields);
                
                $query = "INSERT INTO TutorProfiles (user_id, " . implode(", ", $fields) . ") 
                         VALUES (:user_id, " . implode(", ", $placeholders) . ")";
            }
            
            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
        }
    }

    public function deleteUser($user_id) {
        $query = "DELETE FROM Users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Activate user
    public function activateUser($user_id) {
        $query = "UPDATE Users SET is_active = 1 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Deactivate (soft delete) user
    public function deactivateUser($user_id) {
        $query = "UPDATE Users SET is_active = 0 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
    
    // Search for tutors
    public function searchTutors($searchParams) {
        $query = "SELECT u.user_id, u.first_name, u.last_name, p.profile_picture_url, 
                         tp.hourly_rate, tp.overall_rating
                  FROM Users u
                  JOIN Profiles p ON u.user_id = p.user_id
                  JOIN TutorProfiles tp ON u.user_id = tp.user_id
                  WHERE u.role = 'tutor' AND u.is_active = TRUE";
        
        $params = [];
        
        if (!empty($searchParams['subject_id'])) {
            $query .= " AND EXISTS (SELECT 1 FROM TutorCourses tc WHERE tc.tutor_id = u.user_id AND tc.course_id = :subject_id)";
            $params[':subject_id'] = $searchParams['subject_id'];
        }
        
        if (!empty($searchParams['min_rating'])) {
            $query .= " AND tp.overall_rating >= :min_rating";
            $params[':min_rating'] = $searchParams['min_rating'];
        }
        
        if (!empty($searchParams['max_rate'])) {
            $query .= " AND tp.hourly_rate <= :max_rate";
            $params[':max_rate'] = $searchParams['max_rate'];
        }
        
        if (!empty($searchParams['verified'])) {
            $query .= " AND tp.is_verified = :verified";
            $params[':verified'] = $searchParams['verified'];
        }
        
        // Add sorting
        $query .= " ORDER BY tp.overall_rating DESC";
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reset password by email
    public function resetPasswordByEmail($email, $newPassword) {
        $query = "SELECT user_id FROM Users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return array('status' => 'error', 'message' => 'Email not found!');
        }
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE Users SET password_hash = :password_hash WHERE email = :email";
        $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bindParam(':password_hash', $password_hash);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();
        return array('status' => 'success');
    }
}
?>