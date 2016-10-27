<?php

  include 'connect.php';

  $db_selected = mysqli_select_db($conn, $logindb);
  if (!$db_selected) {
    echo 'Login database not found!';
    exit();
  }

  function login($username, $pass) {
    global $conn;
    $sql = "SELECT id, username, password, name, address, phone ".
        "FROM members WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row || $row['password'] != $pass) {
      session_destroy();
      return false;
    }

    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['login_string'] = $row['password'];

    $_SESSION['store_name'] = $row['name'];
    $_SESSION['store_addr'] = $row['address'];
    $_SESSION['store_phone'] = $row['phone'];
    return true;
  }

  function login_check() {
    global $conn;
    if (!isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
      return false;
    }

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $password = $_SESSION['login_string'];

    $sql = "SELECT password, userdb FROM members WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row || $row['password'] != $password) {
      return false;
    }

    global $userdb;
    $userdb = $row['userdb'];
    return true;
  }

  session_start();
  ob_start();

  if (isset($_POST['login'], $_POST['username'], $_POST['password'])) {
    $name = $_POST['username'];
    $pass = $_POST['password'];
    if (login($name, $pass) == false) {
      header("Location: login.php");
    } else {
      header("Location: index.php");
    }
    exit();
  }

  if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }

  if (!login_check()) {
    header("Location: login.php");
    exit();
  }

  $db_selected = mysqli_select_db($conn, $userdb);
  if (!$db_selected) {
    echo 'User database not found!';
    exit();
  }

?>
