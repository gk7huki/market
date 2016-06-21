<?php include 'protect.php'; ?>
<?php include 'header.php'; ?>

<div id="box_text">

<h2>Find Customer</h2>
<hr>

<?php
  $sql = "CREATE TABLE IF NOT EXISTS customers (".
      "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ".
      "name VARCHAR(30) NOT NULL, ".
      "phone VARCHAR(20) NOT NULL, ".
      "date DATE NOT NULL)";
  $retval = mysqli_query($conn, $sql);
  if (!$retval) {
    die('Could not create table: ' . mysqli_error($conn));
  }

  if (isset($_GET['search_name'], $_GET['name']) && !empty($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "SELECT id, name, phone, date FROM customers WHERE name LIKE '$name%'";
    $result = mysqli_query($conn, $sql);
  }

  if (isset($_GET['search_phone'], $_GET['phone'])) {
    $phone = $_GET['phone'];
    $sql = "SELECT id, name, phone, date FROM customers ".
        "WHERE REPLACE(phone, ' ', '') = REPLACE('$phone', ' ', '')";
    $result = mysqli_query($conn, $sql);
  }
?>

<form action="" method="get">
<p>By Name: <input type="text" name="name" autofocus></p>
<p><input class="button_insert" type="submit" value="Search" name="search_name"></p>
</form>
<form action="" method="get">
<p>By Phone: <input type="text" name="phone"></p>
<p><input class="button_insert" type="submit" value="Search" name="search_phone"></p>
</form>
<br>

<?php
  $i = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $i++;
    $id = $row['id'];
    $name = $row['name'];
    $phone = $row['phone'];
    $date = $row['date'];
?>

<?php if ($i == 1) : ?>
<hr>
<?php endif; ?>

<p><b>Result #<?php echo "$i"; ?></b></p>

<form action="" method="post">
<table align="center" border="0" cellspacing="2" cellpadding="2">
<tr>
<th>ID: </th>
<td><?php echo "$id"; ?></td>
</tr>
<tr>
<th>Name: </th>
<td><?php echo "$name"; ?></td>
</tr>
<tr>
<th>Phone: </th>
<td><?php echo "$phone"; ?></td>
</tr>
<tr>
<th>Date: </th>
<td><?php echo "$date"; ?></td>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td><button class="button_print" type="submit" value="<?php echo "$id"; ?>" name="select">Select</button></td>
<td><button class="button_remove" type="submit" value="<?php echo "$id"; ?>" name="remove">Remove</button></td>
</tr>
</table>
</form>

<br>

<?php
  }
?>

<hr><br>
<h2>New Customer</h2>

<form action="" method="post">
<p>Name: <input type="text" name="ins_name"></p><br>
<p>Phone: <input type="text" name="ins_phone"></p><br>
<p><input class="button_print" type="submit" value="Select" name="ins_select"></p>
<p><input class="button_insert" type="submit" value="Add Only" name="insert"></p>
</form>

<?php
  if (isset($_POST['insert']) || isset($_POST['ins_select'])) {
    if (isset($_POST['ins_name'], $_POST['ins_phone'])) {
      $name = $_POST['ins_name'];
      $phone = $_POST['ins_phone'];
      $sql = "INSERT INTO customers VALUES (NULL, '$name', '$phone', NOW())";
      $retval = mysqli_query($conn, $sql);
      if (!$retval) {
        echo "Could not insert value: " . mysqli_error($conn) . "<br>\n";
      }
    }

    if (isset($_POST['ins_select'])) {
      $id = mysqli_insert_id($conn);
      $_SESSION['customer_id'] = $id;
      header("Location: bill.php");
    } else {
      header("Location: " . $_SERVER['REQUEST_URI']);
    }
    exit();
  }

  if (isset($_POST['select'])) {
    $id = $_POST['select'];
    $_SESSION['customer_id'] = $id;

    header("Location: bill.php");
    exit();
  }

  if (isset($_POST['remove'])) {
    $id = $_POST['remove'];
    $sql = "DELETE FROM customers WHERE id=$id";
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
