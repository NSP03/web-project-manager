<?php
//Include config file
require_once "config.php";
 
//Define variables with empty values
$username = $password = $confirm_password = "";
$username_error = $password_error = $confirm_password_error = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Username validation
    if(empty(trim($_POST["username"]))){
        $username_error = "Please enter a username.";
    } else{
        //Prepare SQL statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            //Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            //Establish parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute SQL statement
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_error = "Username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Something went wrong. Please try again.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    //Error checking main password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_error = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //Error checking confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_error = "Please confirm your password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_error) && ($password != $confirm_password)){
            $confirm_password_error = "Password did not match.";
        }
    }
    
    //Check input errors before inserting in database
    if(empty($username_error) && empty($password_error) && empty($confirm_password_error)){
        
        //Prepare SQL insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            //Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            //Establish parameters
            $param_username = $username;
            //Creates a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            //Attempt to execute SQL insert statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again.";
            }

            //Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="font-italic"><kbd>Sign Up</kbd></h1><br>
        <p>Fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <!-- 
            EXTENSIBILITY 
            input user EMAIL -- send account registration confirmation email
            <div class="form-group">
                <label>USER EMAIL</label>
                <input type="text" name="email" class="form-control" value="xxxxx@gmail.com">
                <span class="help-block"></span>
            </div>
            -- php code to send automated emails --
            -->
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already got an account? <a href="login.php">Login here</a>!</p>
        </form>
    </div>    
</body>
</html>