<?php include 'protect.php'; ?>
<?php include 'header.php'; ?>

<div id="box_text">

<h2>Create Bill</h2>
<hr>

<?php
  $sql = "CREATE TABLE IF NOT EXISTS bill (".
      "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ".
      "prodid INT NOT NULL, ".
      "qty INT NOT NULL)";
  $retval = mysqli_query($conn, $sql);
  if (!$retval) {
    die('Could not create table: ' . mysqli_error($conn));
  }

  if (isset($_POST['insert'], $_POST['ins_id'], $_POST['ins_qty'])) {
    $prodid = $_POST['ins_id'];
    $qty = $_POST['ins_qty'];
    $sql = "INSERT INTO bill VALUES (NULL, '$prodid', '$qty')";
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
      echo "Could not insert value: " . mysqli_error($conn) . "<br>\n";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  $sql = "SELECT b.id, b.prodid, ".
      "i.name, i.unit, b.qty, b.qty * i.unit as 'total'".
      "FROM bill b LEFT JOIN inventory i ON b.prodid = i.id";
  $result = mysqli_query($conn, $sql);
?>

<form action="" method="post">
<table align="center" border="0" cellspacing="2" cellpadding="2">
<tr>
<th>Prod ID</th>
<th>Item Name</th>
<th>Unit Price</th>
<th>Quantity</th>
<th>Total Price</th>
<th><input style="display:none;" type="submit" value="Add" name="insert"></th>
</tr>

<?php
  while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $prodid = $row['prodid'];
    $name = $row['name'];
    $unit = $row['unit'];
    $qty = $row['qty'];
    $total = $row['total'];
?>

<tr>
<?php if (!empty($name)) : ?>
<td><?php echo "$prodid"; ?></td>
<td><?php echo "$name"; ?></td>
<td><?php echo number_format($unit, 2); ?></td>
<td><?php echo "$qty"; ?></td>
<td><?php echo number_format($total, 2); ?></td>
<?php else : ?>
<td><i><?php echo "$prodid"; ?></i></td>
<td><i>Unavailable</i></td>
<td><i>--</i></td>
<td><i>--</i></td>
<td><i>--</i></td>
<?php endif; ?>
<td><button class="button_remove" type="submit" value="<?php echo "$id"; ?>" name="remove">Remove</button></td>
</tr>

<?php
  }
?>
<tr>

<td><input id="box_input" type="text" name="ins_id" autofocus></td>
<td></td>
<td></td>
<td><input id="box_input" type="text" name="ins_qty"></td>
<td></td>
<td><input class="button_insert" type="submit" value="Add" name="insert"></td>
</tr>

<tr>
<td colspan="6"></td>
</tr>

<tr>
<td></td>
<td colspan="4"></td>
<td><input class="button_clear" type="submit" value="New Bill" name="clear"></td>
</tr>

</table>
</form>

<?php
  if (isset($_POST['remove'])) {
    $id = $_POST['remove'];
    $sql = "DELETE FROM bill WHERE id=$id";
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
      echo "Could not remove value: " . mysqli_error($conn) . "<br>\n";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  if (isset($_POST['clear'])) {
    $sql = "DELETE FROM bill";
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
      echo "Could not clear values: " . mysqli_error($conn) . "<br>\n";
    }
    $sql = "ALTER TABLE bill AUTO_INCREMENT=1";
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
      echo "Could not clear values: " . mysqli_error($conn) . "<br>\n";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  mysqli_close($conn);
?>

<hr>

</div>

<?php include 'footer.php'; ?>
