<?php
require_once"config.php";
//MAKING CSV FILE
if(isset($_POST["export"])){
    //Set header, file type, file name, and download type
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");
    
    //Initialise the session
    session_start();
    //INHERITING the user choices using superglobal variables
        $string=$_SESSION["choicestring"];
    fputcsv($output, $_SESSION["allchoices"]);
    
    //Prepare SQL query 
    $query="SELECT $string FROM tasks ORDER BY task_id ASC";
    $result=mysqli_query($link, $query);
    
    //Writing the table line by line into the open CSV file
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    }
    //Close
    fclose($output);
}
?>