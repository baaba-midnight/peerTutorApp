<?php
require_once('Tutor.php');

header("Content-Type: application/json");


$rawData = file_get_contents("php://input");


$data = json_decode($rawData, true);


if (isset($data['user_id']) && isset($data['profile'])) {
    $user_id = $data['user_id'];
    $profileData = $data['profile'];

    $tutor = new Tutor($db);
    $result = $tutor->updateTutorProfile($user_id, $profileData);

    if ($result){
        echo json_encode(['status'=>'success']);
    }
    else{
        echo json_encode(['status'=>'failure']);
    }
}


?>
