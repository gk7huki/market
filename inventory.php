<?php include 'protect.php'; ?>
<?php include 'header.php'; ?>

<div id="box_text">

<h2>Find Item</h2>
<hr>

<?php
  $sql = "CREATE TABLE IF NOT EXISTS inventory (".
      "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ".
      "name VARCHAR(30) NOT NULL, ".
      "unit FLOAT NOT NULL)";
  $retval = mysqli_query($conn, $sql);
  if (!$retval) {
    die('Could not create table: ' . mysqli_error($conn));
  }

  if (isset($_GET['search_id'], $_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT id, name, unit FROM inventory WHERE id=$id";
    $result = mysqli_query($conn, $sql);
  }

  if (isset($_GET['search_name'], $_GET['name']) && !empty($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "SELECT id, name, unit FROM inventory WHERE name LIKE '$name%'";
    $result = mysqli_query($conn, $sql);
  }
?>

<form action="" method="get">
<p>By Prod ID: <input type="text" name="id" autofocus></p>
<p><input class="button_insert" type="submit" value="Search" name="search_id"></p>
</form>
<form action="" method="get">
<p>By Name: <input type="text" name="name"></p>
<p><input class="button_insert" type="submit" value="Search" name="search_name"></p>
</form>
<br>

<?php
  $i = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $i++;
    $id = $row['id'];
    $name = $row['name'];
    $unit = $row['unit'];
?>

<?php if ($i == 1) : ?>
<hr>
<?php endif; ?>

<p><b>Result #<?php echo "$i"; ?></b></p>

<form action="" method="post">
<table align="center" border="0" cellspacing="2" cellpadding="2">
<tr>
<th>Prod ID: </th>
<td><?php echo "$id"; ?></td>
</tr>
<tr>
<th>Name: </th>
<td><?php echo "$name"; ?></td>
</tr>
<tr>
<th>Unit Price: </th>
<td><?php echo number_format($unit, 2); ?></td>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td></td>
<td><button class="button_remove" type="submit" value="<?php echo "$id"; ?>" name="remove">Remove</button></td>
</tr>
</table>
</form>

<br>

<?php
  }
?>

<hr><br>
<h2>New Item</h2>

<form action="" method="post">
<p>Name: <input type="text" name="ins_name"></p><br>
<p>Unit Price: <input type="text" name="ins_unit"></p><br>
<p><input class="button_insert" type="submit" value="Add" name="insert"></p>
</form>

<?php
  if (isset($_POST['insert'], $_POST['ins_name'], $_POST['ins_unit'])) {
    $name = $_POST['ins_name'];
    $unit = $_POST['ins_unit'];
    $sql = "INSERT INTO inventory VALUES (NULL, '$name', '$unit')";
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
      echo "Could not insert value: " . mysqli_error($conn) . "<br>\n";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  if (isset($_POST['remove'])) {
    $id = $_POST['remove'];
    $sql = "DELETE FROM inventory WHERE id=$id";
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
      echo "Could not remove value: " . mysqli_error($conn) . "<br>\n";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  mysqli_close($conn);
?>

<hr>

</div>

<?php include 'footer.php'; ?>
