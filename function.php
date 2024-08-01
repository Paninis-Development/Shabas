<?php
require_once('./connection.php');

// function getSite($site)
// {
//     if(isset($_GET['site'])){
//         include_once('scripts/'.$_GET['site'].'.php');
//     } else{
//         include_once('scripts/'.$site.'.php');
//     }
// }

function checkUserData(){
    if(isset($_POST['login'])){
        $email=$_POST['email'];
        $password=$_POST['password'];

        $db = new DatabaseConnection();
        if ($db->checkUser($email, $password)) {
            if(!($db->isAccepted($email, $password))){
                echo '<p style="color:red;font-size:12px"><b>Benutzer wurde noch nicht durch einen Admin bestätigt!</b></p>';
            }
            else{
                global $ben_id;

                $query="select ben_id from benutzer where mail=?";
                $array=array($email);
                $stmt=$db->makeStatement($query, $array);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $result['ben_id'];

                $ben_id=$id;
                if(isAdmin()){
                    header("Location: /mealmaster_web/Admin/scripts/admin.php");
                }
                else{
                    header("Location: /mealmaster_web/Student/scripts/student.php"); //schüler seite
                }
                $_SESSION['ben_id']=$ben_id;
                exit;
            }
        } else {
            echo '<p style="color:red;font-size:12px"><b>Bitte geben Sie gültige Daten ein!</b></p>';
        }
    }
}

function isAdmin(){
    $db = new DatabaseConnection();
    $query="select rolle_idrolle from Benutzer where ben_id=?";
    global $ben_id;
    $array=array($ben_id);

    $stmt = $db->makeStatement($query, $array);

    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Hier wird das Ergebnis aus dem Statement geholt
    return ($result['rolle_idrolle'] == 1);

}

function checkUser($email, $password) {


$db = new DatabaseConnection();
$query= 'SELECT * FROM admin WHERE Username = ? AND Password = ?';

    $validEmail = "";  
    $validPassword = "password123";

    if ($email === $validEmail && $password === $validPassword) {
        return true;
    } else {
        return false;
    }
}


function getOpeningDays() {
    $db = new DatabaseConnection();
    $query="SELECT OpeningDate FROM openinghours;";
    
    $stmt=$db->makeStatement($query);
    
    $dates = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dates[] = $row['OpeningDate'];
    }
    
    return $dates;
}
function getOpeningTime($date) {
    $db = new DatabaseConnection();
    $query="SELECT OpenTime FROM openinghours WHERE OpeningDate =?;";
    $array=array($date);
    $stmt=$db->makeStatement($query, $array);
    
    $openingtime = "";
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $openingtime = $row['OpenTime'];
    }
    
    return $openingtime;
}
function getClosingTime($date) {
    $db = new DatabaseConnection();
    $query="SELECT CloseTime FROM openinghours WHERE OpeningDate =?;";
    $array=array($date);
    $stmt=$db->makeStatement($query, $array);
    
    $closingtime = "";
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $closingtime = $row['CloseTime'];
    }
    
    return $closingtime;
}




?>