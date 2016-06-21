<?php include 'protect.php'; ?>
<?php include 'header.php'; ?>

<script type="text/javascript">
  function PrintDiv() {
    var divToPrint = document.getElementById('print_div');
    var popupWin = window.open('', '_blank', 'width=300,height=300');
    popupWin.document.open();
    popupWin.document.write('<html><head>' +
        '<link rel="stylesheet" href="style.css" type="text/css"></head>' +
        '<body><div id="box_text" style="padding: 10px;">' + divToPrint.innerHTML +
        '</div></body></html>');
    popupWin.document.close();
    popupWin.focus();
    popupWin.print();
    popupWin.close();
  }
</script>

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

  if (isset($_SESSION['customer_id'])) {
    $id = $_SESSION['customer_id'];
    $sql = "SELECT name, phone FROM customers WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $customer_name = $row['name'];
    $customer_phone = $row['phone'];
  }

  $sql = "SELECT b.id, b.prodid, ".
      "i.name, i.unit, b.qty, b.qty * i.unit as 'total'".
      "FROM bill b LEFT JOIN inventory i ON b.prodid = i.id";
  $result = mysqli_query($conn, $sql);
?>

<div id="print_div">
<div id="print_title">Receipt</div><br>
<div id="print_text">
Date: <span style="float: right;"><?php echo date('d M Y H:i:s'); ?></span><br>
Customer: <span style="float: right;"><?php echo $customer_name; ?></span>
<hr>
<table align="center" border="0" style="width: 100%;">
<tr>
<th>Item</th>
<th>Qty</th>
<th>Rate</th>
<th>Amt</th>
</tr>
<?php
  $bill_total = 0;
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
<?php $bill_total += $total; ?>
<td><?php echo "$name"; ?></td>
<td><?php echo "$qty"; ?></td>
<td><?php echo number_format($unit, 2); ?></td>
<td><?php echo number_format($total, 2); ?></td>
<?php endif; ?>
</tr>
<?php
  }
?>
</table>
<hr>
<b>Total: <span style="float: right;">Rs. <?php echo number_format($bill_total, 2);; ?></span></b>
<hr>
</div>
<br>
<div id="print_footer">Thank You!</div>
</div>

<div>
<form action="customers.php" method="post">
<p><b>Customer: </b></p>
<?php if ($customer_name) : ?>
<p><?php echo $customer_name; ?></p>
<p><input class="button_insert" type="submit" value="Change" name="customer"></p>
<?php else : ?>
<p><input class="button_insert" type="submit" value="Select" name="customer"></p>
<?php endif; ?>
</form>
</div>

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
  mysqli_data_seek($result, 0);
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
<td><input class="button_print" type="button" value="Print" onclick="PrintDiv();"></td>
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

    $_SESSION['customer_id'] = '';

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  mysqli_close($conn);
?>

<hr>

</div>

<?php include 'footer.php'; ?>
