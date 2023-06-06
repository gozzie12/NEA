<?php
//check login function
function check_login($con)
{
    if(isset($_SESSION['user_id'])&&isset($_SESSION['token']))
    {
        $id = $_SESSION['user_id'];
        $query = "select * from users where user_id = '$id' limit 1";
        $result = mysqli_query($con,$query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
        }
        //check if token matches
        if($user_data['token'] !== $_SESSION['token'])
        {
            header("Location: login.php");
            die;
        }
        return $user_data;
    }
    //redirect to login
    header("Location: login.php");
    die;
}
