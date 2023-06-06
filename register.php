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
    $cpassword = $_POST['confirm-password'];
    //check if email and password are not empty and that password and confirm password match
    if(!empty($email) && !empty($password) && !is_numeric($email) && $password === $cpassword){
        //check if email is valid
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            //check if email is already registered
            $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
            $result = mysqli_query($con, $query);
            if($result && mysqli_num_rows($result) > 0){
                $msg = "Email already registered!";
            }else{
                //register user
                $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
                $result = mysqli_query($con, $query);
                if($result['user_id']){
                    $uid = $result['user_id'];
                    // Generate a new token
                    $token = bin2hex(random_bytes(11));
                    // Store the token and user ID in the users session
                    $_SESSION['user_id'] = $uid;
                    $_SESSION['token'] = $token;
                    //store the token in the database
                    $query="UPDATE users SET token = '$token' WHERE user_id = $uid";
                }else{
                    $msg = "Error: " . mysqli_error($con);
                }
            }
        }else{
            $msg = "Invalid email!";
        }

    }
}
?>
<doctype html>
    <html lang="en">

    <head>
        <title>Register</title>
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
                            <form action="register.php" method="post">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm-password">Confirm Password:</label>
                                    <input type="password" name="confirm-password" id="confirm-password" class="form-control" required>
                                </div>
                                <div class="form-group mt-3 d-flex justify-content-between">
                                    <input type="submit" value="Register" class="btn btn-primary">
                                    <a href="login.php" class="btn btn-success">Login</a>
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