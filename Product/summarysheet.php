<?php
// Include config file
require_once "config.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
<!-- page header -->
<div class="page-header" style="background-color:lightgray;"><br><br>
    <h1><kbd>What would you like to see?</kbd></h1><br>
</div>
<!-- main menu options and buttons-->
<div style="background-color:lightgray;">
    <div class="container">
        <a href="viewproject.php" class="btn btn-dark btn-lg">View Project</a>
        <a href="editproject.php" class="btn btn-dark btn-lg">Edit Project</a>
        <a href="summarysheet.php" class="btn btn-dark btn-lg">Customise Summary Sheets</a>
    </div><br>
<br></div><br><br>
<!-- TAKING INPUT AND MAKING A STRING -->
<div class="new-form">
<form method="post" action="summarysheet.php">
    <div class="form-check">
        <h2>Customisation</h2>
              <p class="lead">Select the variables that you would like to see.</p>
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="choice[]" value="task_id">Task ID
        </label><br><br>
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="choice[]" value="umb_task">Umbrella Task
        </label><br><br>
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="choice[]" value="sub_task">Sub Task
        </label><br><br>
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="choice[]" value="employee">Employee
        </label><br><br>
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="choice[]" value="duration_days">Duration (days)
        </label><br><br>
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="choice[]" value="status">Status
        </label>
    </div><br><br>
    <button type="submit" name="submit" class="btn btn-success">Submit</button>
</form><br><br>
</div>

<!-- FORM PROCESSING-->
<?php
require_once "config.php";
//PROCESSING CHOICES
if (isset($_POST['submit'])){
    //make the select string
    if(!empty($_POST["choice"])){
        echo '<h3> Your Summary Sheet:</h3>';
        $allchoices=$_POST["choice"];
        $string = implode(', ', $_POST["choice"]);
        session_start();
            $_SESSION["allchoices"] = $_POST["choice"];
            $_SESSION["choicestring"] = $string;
    }else{
        //error message
        echo "Please choose at least one variable.";
    }

    //make the table header
    echo'<div class="container">
      <table class="table table-hover">
        <thead class="thead-light">
          <tr class="text-left">';

    //make the titles in the header
    foreach($_POST["choice"] as $choice){
        echo '<th>'.$choice.'</th>';
    }

    echo '</tr></thead><tbody>';
    //table body start - select chosen columns
    $sql = "SELECT $string FROM tasks ORDER BY task_id ASC";
    $result = mysqli_query($link, $sql);
    $allcolumns=array($task_id="", $umb_task="", $sub_task="", $employee=""
                      , $duration_days="", $date_started="", $status="");
    
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          //Assign data per column per row
          for ($x=0; $x<=count($_POST["choice"])-1; $x++){
              $allcolumns[$x]=$row["$allchoices[$x]"];
          }
          //printing each row
          echo'<tr>';
          for ($x=0; $x<=count($_POST["choice"])-1; $x++){
                echo '<td><p class="text-justify">'.$allcolumns[$x].'</p></td>';
            }
          echo '</tr>';
      }
    }
    //table ending
    echo '</tbody></table></div>';
    echo '
    <form method="post" action="export.php">
        <input type="submit" name="export" value="CSV Export" class="btn btn-info">
    </form><br><br>';
}

?><br>
<!-- the back buttons-->
    <p>
        <a href="welcome.php" class="btn btn-outline-primary">Back to Main Menu</a>
        <a href="logout.php" class="btn btn-outline-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>