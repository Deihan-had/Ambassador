<?php
class Chat {

    var $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    function sendMessage($senderId, $receiverId, $message) {
        $query = "INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $senderId, $receiverId, $message);
        return mysqli_stmt_execute($stmt);
    }

    function getChatHistory($user1, $user2) {

        $query = "SELECT * FROM chat_messages
                  WHERE (sender_id = ? AND receiver_id = ?)
                     OR (sender_id = ? AND receiver_id = ?)
                  ORDER BY created_at ASC";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $user1, $user2, $user2, $user1);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }
}
?>