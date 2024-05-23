<?php
//
//function validateLogin($username, $password): bool
//{
//    if (empty($username) || empty($password)) {
//        return false;
//    }
//
//    if (strlen($password) < 8) {
//        return false;
//    }
//
//    if (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
//        return false;
//    }
//
//    return true;
//}
//
//function authenticateUser($username, $password)
//{
//    try {
//        $connection = Database::getConnection();
//    } catch (PDOException $e) {
//        echo "Database connection error: " . $e->getMessage();
//        die();
//    }
//
//    $sql = "SELECT * FROM UserAccount WHERE Username = :username";
//    $stmt = $connection->prepare($sql);
//    $stmt->bindParam(':username', $username);
//    $stmt->execute();
//    $user = $stmt->fetch(PDO::FETCH_ASSOC);
//
//    if ($user) {
//        if (password_verify($password, $user['Password'])) {
//            session_start();
//            $_SESSION['loggedin'] = true;
//            $_SESSION['username'] = $username;
//            return true;
//        } else {
//            return false;
//        }
//    } else {
//        return false;
//    }
//}