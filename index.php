<?php
    include "header.php";
    include "connection.php";

    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);

    // Update product information
    if (isset($_POST['update_btn'])) {
        $update_id = $_POST['update_id'];
        $name = $_POST['update_name'];
        $des = $_POST['update_des'];
        $unit = $_POST['update_unit'];
        $unitprice = $_POST['update_unitprice'];

        // Use prepared statements to avoid SQL injection
        $stmt = $conn->prepare("UPDATE product SET unitprice = ?, name = ?, des = ?, unit = ? WHERE id = ?");
        $stmt->bind_param("dssii", $unitprice, $name, $des, $unit, $update_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update product.";
        }

        $stmt->close();
        header('location:index.php');
        exit();
    }

    // Remove a product
    if (isset($_GET['remove'])) {
        $remove_id = $_GET['remove'];

        // Prepared statement for deletion
        $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
        $stmt->bind_param("i", $remove_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete product.";
        }

        $stmt->close();
        header('location:index.php');
        exit();
    }
?>

<html>
<head>
    <title>Stock Management</title>
</head>
<body>
    <div class="container">
        <h5>Stock Status</h5>

        <!-- Display success/error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // Fetch each row from result
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <tr>
                        <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
                        <td><input type="text" name="update_name" value="<?php echo $row['name']; ?>"></td>
                        <td><input type="text" name="update_des" value="<?php echo $row['des']; ?>"></td>
                        <td><input type="number" name="update_unit" value="<?php echo $row['unit']; ?>"></td>
                        <td><input type="number" name="update_unitprice" value="<?php echo $row['unitprice']; ?>"></td>
                        <td><button type="submit" class="btn btn-primary" name="update_btn">Update</button></td>
                        <td><a class="btn btn-danger" href="index.php?remove=<?php echo $row['id']; ?>">Delete</a></td>
                    </tr>
                </form>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No products available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
    include "header.php";
    include "connection.php";

$sql = "SELECT * FROM product";
$result = $conn -> query ($sql);


if(isset($_POST['update_btn'])){
  $update_id = $_POST['update_id'];
  $name = $_POST['update_name'];
  $des = $_POST['update_des'];
  $unit = $_POST['update_unit'];
  $unitprice = $_POST['update_unitprice'];
  
  $update_query = mysqli_query($conn, "UPDATE product SET unitprice = '$unitprice' , name='$name' , des='$des' ,unit='$unit'  WHERE id = '$update_id'");
  if($update_query){
     header('location:index.php');
  };
};

if(isset($_GET['remove'])){
  $remove_id = $_GET['remove'];
  mysqli_query($conn, "DELETE FROM product WHERE id = '$remove_id'");
  header('location:index.php');
};


?>

<html>
<head>
    <title></title>
</head>
<body>
    <div class="container">
    <h5>Stock Status</h5>
    <table class="table table-striped">
  <thead>
    <tr>
      <!--<th scope="col">#</th>-->
      <th scope="col">Product Name</th>
      <th scope="col">Description</th>
      <th scope="col">Unit</th>
      <th scope="col">Unit Price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
   
      <?php
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
              ?>
             <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
               <tr>
                <input type="hidden" name="update_id"  value="<?php echo $row['id'];?>">
                <td><input type="text" name="update_name"  value="<?php echo $row['name'];?>"></td>
                <td><input type="text" name="update_des"  value="<?php echo $row['des'];?>"></td>
                <td><input type="number" name="update_unit"  value="<?php echo $row['unit'];?>"></td>
                <td><input type="number" name="update_unitprice"  value="<?php echo $row['unitprice'];?>"></td>
                <td><button type="submit" class="btn btn-primary" name="update_btn">update</button></td>
                <td><a  class="btn btn-primary" href="index.php?remove=<?php echo $row['id']; ?>">delete</a></td>
                </tr>
                </form>
                <?php }
        } else {
            echo "0 results";
        }
        ?>
      

    
  </tbody>
</table>
</div>
</body>
</html>