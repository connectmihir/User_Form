<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Using frameword of Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!--Linked to the css file-->
    <link rel="stylesheet" href="style.css">

    <title>Registration Form</title>
</head>
<body>

<div class="container">
    <?php

    if(isset($_POST["submit"])){
        $fullname =$_POST["fullname"];
        $Email =$_POST["Email"];
        $password =$_POST["password"];

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $errors=array();

        // For error message

        if(empty($fullname) OR empty($Email) OR empty($password) ){
            array_push($errors, "Please fill the all details below");
        }
        if(!filter_var($Email, FILTER_VALIDATE_EMAIL) ){
            array_push($errors, "Please enter the valid email!");
        }
        if(strlen($password)<8 ){
            array_push($errors, "Too short password, use more than 8 charcter");
        }

//For avoiding the same user email id

          require_once "database.php";

$sql ="SELECT *FROM user WHERE  email='$Email'";
$result =mysqli_query($conn, $sql);
$rowcount =mysqli_num_rows($result);
if ($rowcount>0){
    array_push($errors, "Email Already exist");
}




        if(count($errors)>0){
            foreach ($errors as $errors){
                echo "<div class='alert alert-danger'>$errors</div>";
            }

        }
        else{
       

$sql = "INSERT INTO user (fullname, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    
    mysqli_stmt_bind_param($stmt, "sss", $fullname, $Email, $passwordHash);
    mysqli_stmt_execute($stmt);

    echo "<div class='alert alert-success'>You have registered successfully.</div>";
} 
else {
    die("SQL prepare failed");
}
      
    }
}

    ?>
    <form action="register.php" method="post">
        
    <div class="FormGroup">
        <input type="text" class="form-control" name="fullname" placeholder="Full Name">
    </div>

    <div class="FormGroup">
        <input type="email" name="Email"  class="form-control" placeholder="Email id">
    </div>

    <div class="FormGroup">    
        <input type="password"  class="form-control" name="password" placeholder="Password">
    </div>

    <div class="FormGroup">    
        <input type="Submit"  class="btn btn-primary"  value="Register" name="submit">
    </div>
    </form>
</div>
    
</body>
</html>