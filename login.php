<?php 
session_start();

if (isset($_SESSION['auth'])) {
    if ($_SESSION['auth'] == 1) {
        header("location:index.php");
        exit();
    }
}

if (isset($_POST['submit'])) {
    $id = strtolower(trim($_POST['id'])); // Make username case-insensitive
    $pass = $_POST['password'];

    // Password should ideally be hashed: password_hash($pass, PASSWORD_DEFAULT)
    if ($id == 'admin' && $pass == 'admin') {
        $_SESSION['auth'] = 1;
        header("location:index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center">
        <div class="card mt-5">
            <div class="card-header">
                <h3>Sign In</h3>
            </div>
            <div class="card-body">

                <!-- Display error message if login fails -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="input-group form-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="id" required>
                    </div>
                    <div class="input-group form-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn btn-primary" name="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

</body>
</html>
