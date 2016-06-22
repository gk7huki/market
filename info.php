<?php include 'protect.php'; ?>
<?php include 'header.php'; ?>

<div id="login_text">

<h2><?php echo $_SESSION['store_name']; ?></h2>
<div>
<p><?php echo $_SESSION['store_addr']; ?></p><br>
<p>Phone: <?php echo $_SESSION['store_phone']; ?></p>
</div>

</div>

<?php include 'footer.php'; ?>
