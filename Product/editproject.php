<?php
// Include config file
require_once "config.php";

//Finding number of rows in the table (to be used later)
$sql = "SELECT * FROM tasks";
$result = mysqli_query($link, $sql);
$rows=mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
<!-- page header-->
<div class="page-header" style="background-color:lightgray;"><br><br>
    <h1 class="font-italic"><kbd>Edit Project</kbd></h1><br>
</div>
<!-- main menu options and buttons-->
<div style="background-color:lightgray;">
    <div class="container">
        <a href="welcome.php" class="btn btn-dark btn-lg">Main Menu</a>
        <a href="viewproject.php" class="btn btn-dark btn-lg">View Project</a>
        <a href="editproject.php" class="btn btn-dark btn-lg">Edit Project</a>
        <a href="summarysheet.php" class="btn btn-dark btn-lg">Customise Summary Sheets</a>
    </div><br>
<br></div><br><br>
<!-- EDIT THE PROJECT -->
<div class="container">
    <h2><i>I'd like to...</i></h2><br><br>
  <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#edittask"><h3 class="lead"><i>Edit a Task</i></h3></button>
  <div id="edittask" class="collapse" style="background-color:paleturquoise;">
    <br><h2>Edit Project</h2>
    <p class="lead">Please select the column and row that you would like to edit.</p><br>
    <form action="editproject.php" method="post">
        <p>Select Column:</p>
        <select name="column" class="custom-select">
            <!-- select a column -->
            <option selected>Please select</option>
            <option value="umb_task">Umbrella Task</option>
            <option value="sub_task">Sub Task</option>
            <option value="employee">Employee</option>
            <option value="duration_days">Duration (days)</option>
            <option value="status">Status (1 or 0)</option>
        </select><br><br>
        <p>Select Row/Task ID:</p>
        <select name="row" class="custom-select">
            <!-- select a row -->
            <option selected>Please select</option>
            <?php //create row option for each row of the MySQL tasks table
            for ($x=1; $x<=$rows; $x++ ){
                echo '<option value='.$x.'>'.$x.'</option>';
            }
            ?>
        </select><br><br>
        <!-- new input -->
        <p>What would you like to change it to?:</p> <input type="text" name="insert" class="form-control form-control"><br>
        <input type="submit" name="submit" class="btn btn-success"><br><br>
    </form>
    <p>
        <?php
        //If submit button is clicked... form processing
        if (isset($_POST["submit"])){
            if ($_POST["column"]=="duration_days" || $_POST["column"]=="status"){
                // Cast string to int
                $column=$_POST["column"];
                $int_cast = (int)$column;
            }
            $row=$_POST["row"];
            $column=$_POST["column"];
            $insert = $_POST["insert"];

            //Prepare SQL query
            $sql = "UPDATE tasks SET $column = '$insert' WHERE task_id='$row'";

            if (mysqli_query($link, $sql)) {
                //Success message
                echo '<div class="alert alert-success"><strong>Record updated successfully!</strong> Refresh the page to see changes. Your updated record is: '.$_POST["insert"].'</div><br>';
            } else {
                //Error message
                echo "Error updating record: " . mysqli_error($link);
            }
            mysqli_close($link);
        }
        ?>
    </p>
</div>
</div><br>
<!-- ADD A TASK-->
<div class="container">
  <button type="button" class="btn btn-success btn-block" data-toggle="collapse" data-target="#addtask"><h3 class="lead"><i>Add a Task</i></h3></button>
  <div id="addtask" class="collapse" style="background-color:honeydew;">
    <br><h2>Add Task</h2>
    <p class="lead">Fill in the following fields to add a task.</p><br>
    <!-- ADD A TASK FORM INPUT -->
    <form action="editproject.php" method="post">
        <p>Umbrella Task</p>
        <input type="text" name="umb_task" class="form-control"><br>
        <p>Sub Task</p>
        <input type="text" name="sub_task" class="form-control"><br>
        <p>Employee</p> 
        <input type="text" name="employee" class="form-control"><br>
        <p>Duration (days)</p> 
        <input type="number" name="duration_days" class="form-control"><br>
        <input type="submit" name="addtask" value="Add Task" class="btn btn-success"><br>
    </form><br>
    <p>
        <?php
        //Form processing
        if (isset($_POST["addtask"])){
            $umb_task=$_POST["umb_task"];
            $sub_task=$_POST["sub_task"];
            $employee = $_POST["employee"];
            $duration_days=$_POST["duration_days"];
            $int_cast = (int)$duration_days;
            
            //Prepare SQL statement
            $sql = "INSERT INTO tasks (task_id, umb_task, sub_task, employee, duration_days, date_started)VALUES (NULL, '$umb_task', '$sub_task', '$employee', '$duration_days', '2024-01-01')";

            if (mysqli_query($link, $sql)) {
                //Success message
                echo '<div class="alert alert-success"><strong>Record added successfully!</strong> Refresh the page to see changes. </div><br>';
            } else {
                //Error message
                echo "Error updating record: " . mysqli_error($link);
            }
            //Close connection
            mysqli_close($link);
        }
        ?>
    </p>
</div>
</div>
<br>
<!-- DELETE A TASK -->
<div class="container">
  <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#deletetask"><h3 class="lead"><i>Delete a Task</i></h3></button>
  <div id="deletetask" class="collapse" style="background-color:pink;"><br>
    <h2>Delete Task</h2>
    <p class="lead">Select the task ID of the entry you would like to delete.</p><br>
    <!-- DELETE A TASK FORM INPUT -->
    <form action="editproject.php" method="post">
        <p>Select Row/Task ID:</p>
        <select name="deleterow" class="custom-select">
            <option selected>Please select</option>
            <?php 
            //create row option for each of the rows in the table
            for ($x=1; $x<=$rows; $x++ ){
                echo '<option value='.$x.'>'.$x.'</option>';
            }
            ?>
        </select><br><br>
        <input type="submit" name="deletetask" value="Delete Task" class="btn btn-success"><br><br>
    </form>
    <p>
        <?php
        //Form processing
        if (isset($_POST["deletetask"])){
            $row=$_POST["deleterow"];
            
            //Prepare SQL query
            $sql = "DELETE FROM tasks WHERE task_id='$row';";

            if (mysqli_query($link, $sql)) {
                //Success message
                echo '<div class="alert alert-success"><strong>Record deleted successfully!</strong> Refresh the page to see changes. </div><br>';
            } else {
                //Error message
                echo "Error updating record: " . mysqli_error($link);
            }
            //Close connection
            mysqli_close($link);
        }
        ?>
    </p>
</div>
</div>
<br><br>
<!-- PRINTING WHOLE PROJECT-->
<div class="container">
    <h2><i>Your Project</i></h2><br><br>
  <p class="lead">Below is the table summary of your whole project.</p>
  <table class="table table-hover">
    <thead class="thead-light">
        <!-- Table header -->
      <tr class="text-left">
        <th>Row/Task ID</th>
        <th>Umbrella Task</th>
        <th>Sub Task</th>
        <th>Employee</th>
        <th>Duration (days)</th>
        <th>Date to start</th>
          <th>Status</th>
      </tr>
    </thead>
      <!-- Table body -->
    <tbody>
        <?php

        if (mysqli_num_rows($result) > 0) {
          // Output data of each row
          while($row = mysqli_fetch_assoc($result)) {
              //Assigning data per row per column
              $task_id=$row["task_id"];
              $umb_task=$row["umb_task"];
              $sub_task=$row["sub_task"];
              $employee=$row["employee"];
              $duration_days=$row["duration_days"];
              $date_started=$row["date_started"];
              //Convert status from binary to string
              if ($row["status"]==1){
                  $status="Completed";
              } else {
                  $status="";
              }

              //Print each row
              echo'
                    <tr>
                        <td><p class="text-justify">'.$task_id.'</p></td>
                        <td><p class="text-justify">'.$umb_task.'</p></td>
                        <td><p class="text-justify">'.$sub_task.'</p></td>
                        <td><p class="text-justify">'.$employee.'</p></td>
                        <td><p class="text-justify">'.$duration_days.'</p></td>
                        <td><p class="text-justify">'.$date_started.'</p></td>
                        <td><p class="text-justify">'.$status.'</p></td>
                    </tr>';
          }
        }
        ?>
    </tbody>
  </table>
</div>
<!-- UPDATE TIMEPLAN -->
<div class="container">
    <!-- 'Update my timeplan' button -->
    <form action="editproject.php" method="post" class="text-right">
        <input type="submit" name="update" value="Update my timeplan" class="btn btn-info">
    </form><br>
    <p>
        <?php
        //if the user has clicked the button
        if (isset($_POST["update"])){
            //for each row of the tasks MySQL table
            for ($oldindex = 1; $oldindex <= $rows; $oldindex++) {
                $newindex=$oldindex+1;
                
                //the start date of the new task = date of old task + duration of old task in days
                
                //select the location of the previous task - A
                    //"SELECT date_started FROM tasks WHERE task_id='$oldindex'"

                //select amount of days by which to change - B
                    //"SELECT duration_days FROM tasks WHERE task_id='$oldindex'";

                //select the location that needs to be updated - C
                    //"SELECT date_started FROM tasks WHERE task_id='$newindex'";

                //add B to A to create the date for C
                    //"SELECT DATE_ADD(date_started,INTERVAL B) FROM A"

                //completed update query
                    // UPDATE C SET C = B + A
                
                $sql="UPDATE tasks SET date_started = (SELECT DATE_ADD(date_started,INTERVAL 
                (SELECT duration_days FROM tasks WHERE task_id='$oldindex') DAY) FROM tasks 
                WHERE task_id='$oldindex') WHERE task_id='$newindex'";
                
                $result = mysqli_query($link, $sql);
            }
                if (mysqli_query($link, $sql)) {
                    //confirmation message
                    echo '<div class="alert alert-success"><strong>Dates updated successfully!</strong> Refresh the page to see your new timeplan.</div>';
                } else {
                    //error message
                  echo "Error updating record: " . mysqli_error($link);
                }
        }
        ?>
    </p>
</div><br><br>
<!-- the back buttons-->
<p>
    <a href="welcome.php" class="btn btn-outline-primary">Back to Main Menu</a>
    <a href="logout.php" class="btn btn-outline-danger">Sign Out of Your Account</a>
</p>
</body>