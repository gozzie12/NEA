<?php
session_start();
//login page
include_once("DB.php");
include("funcs.php");
$msg = "";
//if logged in redirect to index page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    die;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (!empty($email) && !empty($password) && !is_numeric($email)) {
        //read from database
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                if ($user_data['password'] === md5($password)) {
                    // Generate a new token
                    $token = bin2hex(random_bytes(11));
                    // Store the token and session ID in the database
                    $user_id = $user_data['user_id'];
                    $query = "UPDATE users SET token = '$token' WHERE user_id = $user_id";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        $_SESSION['user_id'] = $user_data['user_id'];
                        $_SESSION['token'] = $token;
                        header("Location: index.php");
                        die;
                    } else {
                        $msg = "Error: " . mysqli_error($con);
                    }
                } else {
                    $msg = "Wrong email or password!";
                }
            } else {
                $msg = "Wrong email or password!";
            }
        } else {
            $msg = "Error: " . mysqli_error($con);
        }
    } else {
        $msg = "Wrong email or password!";
    }
}
?>
<doctype html>
    <html lang="en">

    <head>
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    </head>

    <body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Login</h2>
                        </div>
                        <div class="card-body">
                            <form action="login.php" method="post">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="form-group mt-3 d-flex justify-content-between">
                                    <input type="submit" value="Login" class="btn btn-primary">
                                    <a href="register.php" class="btn btn-success">Register</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($msg)) : ?>
            <div class=" position-fixed w-100" style="top: 0;">
                <div class="alert alert-danger text-center mb-0" role="alert" style="opacity: 0.8;" onclick="this.parentNode.removeChild(this);">
                    <?php echo $msg; ?>
                </div>
            </div>
        <?php endif; ?>
    </body>

    </html>