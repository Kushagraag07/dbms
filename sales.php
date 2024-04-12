<?php
    include "header.php";
    include "connection.php";

    $sql = "SELECT * FROM product";
    $result = mysqli_query($conn, $sql);

    if (isset($_POST['submit'])) 
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $des = $_POST['des'];
        $unit = $_POST['unit'];
        $unitprice = $_POST['unitprice'];
        $unitsale = $_POST['unitsale'];

        // Input validation: ensure the sold units are greater than 0
        if($unitsale <= 0) {
            echo "Sell units must be greater than zero.";
        } else {
            $totalprice = $unitprice * $unitsale;
            $u_unit = $unit - $unitsale;

            // Check if enough stock is available
            if($unit >= $unitsale) {
                // Prepared statement for inserting into the sales table
                $stmt = $conn->prepare("INSERT INTO sales(name, sellunit, totalprice, productid) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sidi", $name, $unitsale, $totalprice, $id);

                if ($stmt->execute()) {
                    echo "Sell successfully";
                } else {
                    echo "Error: " . $stmt->error;
                }

                // Prepared statement for updating product quantity
                $stmt_update = $conn->prepare("UPDATE `product` SET unit = ? WHERE id = ?");
                $stmt_update->bind_param("ii", $u_unit, $id);

                if ($stmt_update->execute()) {
                    echo "Update successfully";
                } else {
                    echo "Error: " . $stmt_update->error;
                }

                // Redirect after successful sale
                header('location:sales.php');
            } else {
                echo "Out Of Stock";
            }
        }
    }
?>
<html>
<head>
    <title>Sales</title>
</head>
<body>
    <div class="container">
        <h5>Sales</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Sell Unit</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="des" value="<?php echo $row['des']; ?>">
                        <input type="hidden" name="unit" value="<?php echo $row['unit']; ?>">
                        <input type="hidden" name="unitprice" value="<?php echo $row['unitprice']; ?>">
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['des']; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['unitprice']; ?></td>
                        <td>
                            <div class="mb-3">
                                <input type="number" name="unitsale" class="form-control" id="exampleInputUnit" min="1" required>
                            </div>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" name="submit">Sell Now</button>
                        </td>
                    </form>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No products available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
