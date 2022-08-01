<?php
// Initialize the session
session_start();
require_once "config.php";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//for the progress bar later
$sql = "SELECT * FROM tasks";
$result = mysqli_query($link, $sql);
$rows=mysqli_num_rows($result); 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
<!-- page greeting-->
<div class="page-header" style="background-color:lightgray;"><br><br>
    <h1><kbd>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to ProPlan.</kbd></h1><br>
</div>
<!-- main menu options and buttons-->
<div style="background-color:lightgray;">
    <div class="container">
        <a href="viewproject.php" class="btn btn-dark btn-lg">View Project</a>
        <a href="editproject.php" class="btn btn-dark btn-lg">Edit Project</a>
        <a href="summarysheet.php" class="btn btn-dark btn-lg">Customise Summary Sheets</a>
    </div><br>
<br></div><br><br>
<!-- date and time-->
    <div class="date-and-time">
        <p class="lead">
            <?php date_default_timezone_set("Asia/Hong_Kong");
            echo htmlspecialchars(" The date is " . date("Y-m-d") . " // ");
            echo htmlspecialchars("The time is " . date("h:i"));
            ?>
        </p>
    </div><br><br>
<!-- progress bar-->
<?php 
    $numcompleted=0;

    //calculating the number of tasks completed
    if (mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
              if ($row["status"]==1){
                  $numcompleted+=1;
              }
          }
        $progress=round(($numcompleted / $rows)*100);
        echo '
        <div class="row"><div class="col-sm-1"></div>
          <div class="col-sm-3"><h4>Your Project Progress:</h4></div>
          <div class="col-sm-7">
            <div class="progress" style="height:40px">
              <div class="progress-bar bg-success" style="width:'.$progress.'%;height:40px">'.$progress.'% Completed</div>
            </div>
          </div>
        </div><br><br>';
    }
?>
<!-- next upcoming task-->
<div class="container">
  <h3 class="text-justify">Upcoming Tasks</h3>          
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Row/Task ID</th>
        <th>Umbrella Task</th>
        <th>Sub Task</th>
        <th>Employee</th>
        <th> Duration (days)</th>
        <th>Date to start</th>
      </tr>
    </thead>
    <tbody>
<?php
    $sql = "SELECT * FROM tasks";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          if ($row["status"]==0){
              $task_id=$row["task_id"];
              $umb_task=$row["umb_task"];
              $sub_task=$row["sub_task"];
              $employee=$row["employee"];
              $duration_days=$row["duration_days"];
              $date_started=$row["date_started"];
              
              echo '
              <tr>
                <td>'.$task_id.'</td>
                <td>'.$umb_task.'</td>
                <td>'.$sub_task.'</td>
                <td>'.$employee.'</td>
                <td>'.$duration_days.'</td>
                <td>'.$date_started.'</td>
              </tr>';
          }
      }
    }
?>
    </tbody>
  </table>
</div><br><br>
<!-- the main menu buttons-->
<div class="back-buttons">
    <a href="reset-password.php" class="btn btn-outline-info">Reset Your Password</a>
    <a href="logout.php" class="btn btn-outline-warning">Sign Out of Your Account</a>
</div>
</body>
</html>