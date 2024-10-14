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

// function checkUserData()
// {
//     if (isset($_POST['login'])) {
//         $email = $_POST['email'];
//         $password = $_POST['password'];

//         $db = new DatabaseConnection();
//         if ($db->checkUser($email, $password)) {
//             if (!($db->isAccepted($email, $password))) {
//                 echo '<p style="color:red;font-size:12px"><b>Benutzer wurde noch nicht durch einen Admin bestätigt!</b></p>';
//             } else {
//                 global $ben_id;

//                 $query = "select ben_id from benutzer where mail=?";
//                 $array = array($email);
//                 $stmt = $db->makeStatement($query, $array);
//                 $result = $stmt->fetch(PDO::FETCH_ASSOC);
//                 $id = $result['ben_id'];

//                 $ben_id = $id;
//                 if () {
//                     header("Location: /mealmaster_web/Admin/scripts/admin.php");
//                 } else {
//                     header("Location: /mealmaster_web/Student/scripts/student.php"); //schüler seite
//                 }
//                 $_SESSION['ben_id'] = $ben_id;
//                 exit;
//             }
//         } else {
//             echo '<p style="color:red;font-size:12px"><b>Bitte geben Sie gültige Daten ein!</b></p>';
//         }
//     }
// }


function checkUser($email, $password)
{
    $db = new DatabaseConnection();
    $query = 'SELECT Password FROM admin WHERE Username = ?';
    $array = array($email);
    $stmt = $db->makeStatement($query, $array);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists
    if ($result) {
        // Fetch the hashed password from the database
        $hashedPassword = $result['Password'];

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            global $loggedIn;
            return true;
        }
    }
$loggedIn = false;
    // Return false if the user doesn't exist or the password is incorrect
    return false;
}


function getOpeningDays()
{
    $db = new DatabaseConnection();   


    $query = "Select opening_date from openinghours;";

    $stmt = $db->makeStatement($query);

    $dates = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dates[] = $row['opening_date'];
    }

    return $dates;
}
function getOpeningTime($date)
{
    $db = new DatabaseConnection();
    $query = "SELECT open_time FROM openinghours WHERE opening_time =?;";
    $array = array($date);
    $stmt = $db->makeStatement($query, $array);

    $openingtime = "";
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $openingtime = $row['OpenTime'];
    }

    return $openingtime;
}
function getClosingTime($date)
{
    $db = new DatabaseConnection();
    $query = "SELECT close_time FROM openinghours WHERE opening_date =?;";
    $array = array($date);
    $stmt = $db->makeStatement($query, $array);

    $closingtime = "";
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $closingtime = $row['CloseTime'];
    }

    return $closingtime;
}


function getOpeningClosingTime($date)
{
   
    $db = new DatabaseConnection();
    $query = "SELECT open_time, close_time FROM openinghours WHERE opening_date = ?;";
    $array = array($date);
    $stmt = $db->makeStatement($query, $array);

    $openCloseTimes = [];
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $openCloseTimes['openTime'] = $row['OpenTime'];
        $openCloseTimes['closeTime'] = $row['CloseTime'];
    }

    return $openCloseTimes;
}

// function generateTimeSlots($openTime, $closeTime, $interval = 45)
// {
//     $timeSlots = [];
//     $current = strtotime($openTime);
//     $end = strtotime($closeTime);

//     while ($current < $end) {
//         $startTime = date('H:i', $current);
//         $current = strtotime("+$interval minutes", $current);
//         if ($current <= $end) {
//             $endTime = date('H:i', $current);
//             $timeSlots[] = "$startTime - $endTime";
//         }
//     }

//     return $timeSlots;
// }


function generateTimeSlotsWithAvailability($openTime, $closeTime, $date) {
    $slots = [];
    $interval = 45 * 60; // 45 minutes in seconds
    $startTime = strtotime($openTime);
    $endTime = strtotime($closeTime);

    while ($startTime < $endTime) {
        $slotStart = date("H:i", $startTime);
        $slotEnd = date("H:i", $startTime + $interval);

        // Check if the time slot is available
        if (isSlotAvailable($date, $slotStart, $slotEnd)) {
            $slots[] = "<option value='$slotStart-$slotEnd'>$slotStart - $slotEnd</option>";
        } else {
            // Slot is taken, disable the option
            $slots[] = "<option value='$slotStart-$slotEnd' disabled>$slotStart - $slotEnd (Taken)</option>";
        }

        // Move to the next time slot
        $startTime += $interval;
    }

    return $slots;
}



function isSlotAvailable($date, $startTime, $endTime) {
    $db = new DatabaseConnection();
    
    // Query to check if any appointment overlaps with the selected time slot on the specified date
    $query = "SELECT COUNT(*) as count 
    FROM appointments 
    WHERE appointment_date = ? 
    AND (
        (start_time < ? AND end_time > ?)  -- Appointment wraps around the slot
        OR (start_time >= ? AND start_time < ?) -- Slot starts during an appointment
        OR (end_time > ? AND end_time <= ?) -- Slot ends during an appointment
    )";
    
    // The time range you want to check
    $params = array($date, $endTime, $startTime);
    
    $stmt = $db->makeStatement($query, $params);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If count > 0, the slot is taken
    return $result['count'] == 0;
}

function saveAppointment($date, $startTime, $endTime, $name, $email, $phone) {
    $db = new DatabaseConnection();

    // Insert the appointment into the database
    $query = "INSERT INTO appointments (appointment_date, start_time, end_time, customer_name, customer_email, customer_phone) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $params = array($date, $startTime, $endTime, $name, $email, $phone);
    
    $stmt = $db->makeStatement($query, $params);

    return $stmt;
}
