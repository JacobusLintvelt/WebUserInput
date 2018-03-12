<!DOCTYPE html>
<?php
require_once 'DBConnection.php';
$name = "";
$surname = "";
$idNumber = "";
$DOB = "";
$errorMessage = "";
$successMessage;


if (isset($_POST['name'])) {
    $name = $_POST['name'];
}
if (isset($_POST['surname'])) {
    $surname = $_POST['surname'];
}
if (isset($_POST['ID'])) {
    $idNumber = $_POST['ID'];
}
if (isset($_POST['DOBInput'])) {
    $DOB = $_POST['DOBInput'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $check2 = FALSE;
    $check3 = FALSE;
    $check4 = FALSE;
// 1 if name and surname is a string
//2 check if ID number is unique and 13 characters 
    $sql2 = "SELECT `IDNo` FROM `informationtbl` WHERE `IDNo` = '{$idNumber}'";
    $IDV = mysqli_query($Connection, $sql2)or die(mysql_error());
    if (mysqli_affected_rows($Connection) == 0) {
        $check2 = TRUE;
    } else {
        $idNumber = "";
        $errorMessage = 'The ID Number Existas in the database! </br>';
        $check2 = FALSE;
    }
//3 check if Date of birth is DD/MM/YYYY and writes into database YYYY/MM/DD
    $day = substr($DOB, 0, 2);
    $month = substr($DOB, 3, 2);
    $year = substr($DOB, 6, 4);

    $DOB2 = $year . "-" . $month . "-" . $day;
    if (checkdate($month, $day, $year) !== FALSE) {

        $check3 = TRUE;
    } else {
        $DOB = "";
        $errorMessage = 'The date Of birth is not a valid date! </br>';
        $check3 = FALSE;
    }

//4 check if Date of birth is == start of ID number

    $check = substr($year, 2, 2) . $month . $day;
    $compare = substr($idNumber, 0, 6);
    if ($check == $compare) {
        $check4 = TRUE;
    } else {
         $errorMessage = 'The Date and the id number does not match!</br>';
        $check4 = FALSE;
    }

//4 write into database if all checks is passed 

    if ($check2 == TRUE && $check3 == TRUE && $check4 == TRUE) {
        $sql = "INSERT INTO `informationtbl`(`Name`, `Surname`, `IDNo`, `DateOfBirth`) VALUES ('{$name}','{$surname}','{$idNumber}','{$DOB2}')";
        mysqli_query($Connection, $sql) or die(mysqli_error($Connection));
        if (mysqli_affected_rows($Connection) > 0) {
            $successMesage = "Data added to the database";
            $_POST = array();
            $name = $surname = $idNumber = $DOB2 = $DOB = "";
        }
    } else {
        //$errorMessage = 'Data Error: The data in the fields are ';
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <title>Input Form form</title>
    </head>
    <body>
        <div class="jumbotron text-center bg-info">
            <h1>User input</h1>

        </div>
        <div class="container">
            <form class=" form-control panel panel-default" name="Info" method="POST" action="index.php">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input  
                        class="form-control" 
                        type="text" 
                        name="name" 
                        id="name"  
                        pattern="[A-Za-zÀ-ž\s]+" 
                        required 
                        autocomplete="off" 
                        value="<?php echo htmlentities($name) ?>" 
                        placeholder="Name"
                        title="The name field can only take letters uppercase, lowercase, spaces or diacritics">
                </div>

                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input 
                        class="form-control" 
                        type="text" name="surname" 
                        id="surname" 
                        pattern="[A-Za-zÀ-ž\s]+" 
                        required 
                        autocomplete="off" 
                        value="<?php echo htmlentities($surname) ?>" 
                        placeholder="Surname"
                        title="The Surname field can only take letters uppercase, lowercase, spaces or diacritics">
                </div>

                <div class="form-group">
                    <label for="ID" >ID Number</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="ID" 
                        id="ID" 
                        pattern="[0-9]{13}" 
                        required autocomplete="off" 
                        value="<?php echo htmlentities($idNumber) ?>" 
                        placeholder="Must be 13 characters"
                        title="The Id numeber must be 13 Characters long">
                </div>

                <div class="form-group">
                    <label for="DOB">Date Of birth</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="DOBInput" 
                        id="DOB" 
                        pattern="[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}" 
                        required 
                        autocomplete="off" 
                        value="<?php echo htmlentities($DOB) ?>" 
                        placeholder="DD/MM/YYYY"
                        title="Make sure you enter a valid date in the DD/MM/YYYY format">
                </div>

                </br>
                <button class="btn btn-success" type="submit" name="PostButton">Post</button>
                <button class="btn btn-danger"  type="reset" name="CancelButton">Cancel</button>
            </form>
        </div>
        <div class="text-center">
            <p class="">
                <?php
                if (isset($successMesage)) {
                    echo $successMesage;
                    
                }
                if (isset($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
            </p>
        </div>
    </body>
</html>

