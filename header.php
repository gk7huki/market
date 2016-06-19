<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>E-Fresh Billing System</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div id="banner">

<div id="welcome">
<?php if (isset($_SESSION['user_id'])) : ?>
<span>Welcome<br><?php echo $_SESSION['username']; ?></span>
<?php else : ?>
<span><i>guest</i></span>
<?php endif; ?>
</div>

<div id="logout">
<?php if (isset($_SESSION['user_id'])) : ?>
<form action="index.php" method="post">
<span><input class="button_logout" type="submit" value="Logout" name="logout"></span>
</form>
<?php endif; ?>
</div>

<?php
  $menu1 = 'menu_inactive';
  $filename = basename($_SERVER['REQUEST_URI'], '.php');
  if ($filename == 'bill') {
    $menu1 = 'menu_active';
  }
?>

<div id="title">E-Fresh Billing System</div>

<div id="menu">
<div id="<?php echo $menu1; ?>"><a href="bill.php">Bill</a></div>
</div>

</div>

<div id="box">
