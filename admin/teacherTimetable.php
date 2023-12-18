<?php
//this page cannot be traditionally secured, to secure this page all get and post request will contain a user id and the corresponding token and must be checked against the database
require_once '../utils.php';
//check if the user is logged in
if($_SERVER['REQUEST_TYPE']==='POST' && isset($_POST['userID']) && isset($_POST['token'])){
    //get the user id and token from the post request
    $userID = $_POST['userID'];
    $token = $_POST['token'];
    //check if the token is valid
    if(verify_login_($userID, $token)){
        //get the user details from the database
        $sql = "SELECT Admin FROM User WHERE ID=? LIMIT 1";
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($admin);
        $stmt->fetch();
        $stmt->close();
        //store the users details in the session
        $_SESSION['admin'] = $admin;
        //return 200 code
        header("HTTP/1.1 200 OK");
        die();
    }
    else{
        //return 401 code
        header("HTTP/1.1 401 Unauthorized");
        die();
    }
    die();
}
else if ($_SERVER['REQUEST_TYPE']==='GET' && isset($_GET['userID']) && isset($_GET['token'])){
    //get the user id and token from the get request
    $userID = $_GET['userID'];
    $token = $_GET['token'];
    //check if the token is valid
    if(verify_login_($userID, $token)){
        //get the user details from the database
        $sql = "SELECT Admin FROM User WHERE ID=? LIMIT 1";
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($admin);
        $stmt->fetch();
        $stmt->close();
        //store the users details in the session
        $_SESSION['admin'] = $admin;
        //return 200 code
        header("HTTP/1.1 200 OK");
        die();
    }
    else{
        //return 401 code
        header("HTTP/1.1 401 Unauthorized");
        die();
    }
    die();
}
else{
    //return 400 code
    header("HTTP/1.1 400 Bad Request");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Classes</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Classes</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Class ID</th>
                            <th scope="col">Class Name</th>
                            <th scope="col">Teacher(s) Name(s)</th>
                            <th scope="col">Students</th>
                            <th scope="col">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['teacher'])) {
                            $classes = get_all_classes_of_teacher($_GET['teacher']);
                        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher'])) {
                            $classes = get_all_classes_of_teacher($_POST['teacher']);
                        } else {
                            die();
                        }
                        foreach ($classes as $Class) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $Class->getID(); ?></th>
                                <td><?php echo $Class->getName(); ?></td>
                                <?php
                                $teachers = $Class->getTeachers();
                                $teacher_names = array();
                                foreach ($teachers as $teacher) {
                                    $sql = "SELECT Name FROM `Teacher` WHERE `id` = ?";
                                    $stmt = $GLOBALS['db']->prepare($sql);
                                    $stmt->bind_param("i", $teacher);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    array_push($teacher_names, $row['Name']);
                                    $stmt->close();
                                }
                                $teacher_string = implode("<br>", $teacher_names);
                                ?>
                                <td><?php echo $teacher_string; ?></td>
                                <td><?php echo count($Class->getStudents()); ?></td>
                                <td><a href="class.php?id=<?php echo $Class->getID(); ?>">View</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>