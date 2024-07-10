<?php
    include "header.php";
    include "connection.php";
    $t = 0;

    if (isset($_POST['submit'])) {
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];

        // Validate that both start and end times are provided
        if (!empty($starttime) && !empty($endtime)) {
            $sql = "SELECT * FROM purchase WHERE created_at >= '$starttime' AND created_at <= '$endtime'";
            $res = $conn->query($sql);
        } else {
            echo "Please provide both start and end times.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Report</title>
</head>
<body>
<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="starttime">Start (date and time):</label>
        <input type="datetime-local" id="starttime" name="starttime" required>

        <label for="endtime">End (date and time):</label>
        <input type="datetime-local" id="endtime" name="endtime" required>

        <input type="submit" name="submit" value="Generate Report">
        <button type="button" onclick="window.print();return false;">PDF Report</button>
    </form>

    <h5>Purchase Report</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Product Name</th>
                <th scope="col">Unit</th>
                <th scope="col">Total Unit Price</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_POST['submit']) && isset($res)) {
            if (mysqli_num_rows($res) > 0) {
                // Output data for each row
                while ($row = mysqli_fetch_assoc($res)) {
                    $t += ($row['unit'] * $row['unitprice']);
                    ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['unit'] * $row['unitprice']; ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='3'>No results found for the specified time period.</td></tr>";
            }
        }
        ?>
        </tbody>
    </table>
    <?php if ($t > 0) echo "Total = " . $t . " Taka"; ?>
</div>
</body>
</html>
