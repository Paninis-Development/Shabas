<?php
require_once('./connection.php');



// function checkUser($email, $password)
// {
//     $db = new DatabaseConnection();
//     $query = 'SELECT Password FROM admin WHERE Username = ?';
//     $array = array($email);
//     $stmt = $db->makeStatement($query, $array);
//     $result = $stmt->fetch(PDO::FETCH_ASSOC);
//     // hash pw for new pw
//     // echo password_hash($password, PASSWORD_DEFAULT);
//     // Check if the user exists
//     if ($result) {
//         // Fetch the hashed password from the database
//         $hashedPassword = $result['Password'];

//         // Verify the password
//         if (password_verify($password, $hashedPassword)) {
//             global $loggedIn ;
//             $loggedIn = true;
//             return true;
//         }
//     }
//     $loggedIn = false;
//     // Return false if the user doesn't exist or the password is incorrect
//     return false;
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
            // Start session and set loggedIn status
            session_start();
            $_SESSION['loggedIn'] = true;
            return true;
        }
    }
    
    // If credentials are wrong, set loggedIn to false
    session_start();
    $_SESSION['loggedIn'] = false;
    return false;
}


function isLoggedIn()
{
    session_start();
    // Check if 'loggedIn' is set and true
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        return true;
    }
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
        // Use the correct column names from the database
        $openCloseTimes['openTime'] = $row['open_time'];
        $openCloseTimes['closeTime'] = $row['close_time'];
    }

    return $openCloseTimes;
}


function generateTimeSlotsWithAvailability($openTime, $closeTime, $date)
{
    $slots = [];
    $interval = 60 * 60; // 45 minutes in seconds
    $startTime = strtotime($openTime);
    $endTime = strtotime($closeTime);

    while ($startTime < $endTime) {
        $slotStart = date("H:i", $startTime);
        $slotEnd = date("H:i", $startTime + $interval);

        // Check if the time slot is available
        if(isSlotAvailable($date, $slotStart, $slotEnd)) {
            $slots[] = "<option value='$slotStart-$slotEnd'>$slotStart - $slotEnd</option>";
        } else {
            // Slot is taken, disable the option
            $slots[] = "<option value='$slotStart-$slotEnd' disabled>$slotStart - $slotEnd (Vergeben)</option>";
        }

        // Move to the next time slot
        $startTime += $interval;
    }

    return $slots;
}



function isSlotAvailable($date, $startTime, $endTime)
{
    $db = new DatabaseConnection();

    // Wrap the database interaction in a try-catch block
    try {
        // Query to check if any appointment overlaps with the selected time slot on the specified date
        $query = "SELECT COUNT(*) as count 
        FROM appointment 
        WHERE appointment_date = ? 
        AND (
            (start_time < ? AND end_time > ?)  
            OR (start_time >= ? AND start_time < ?) 
            OR (end_time > ? AND end_time <= ?) 
        )";

        // Pass the correct number of parameters for each placeholder
        $params = array($date, $endTime, $startTime, $startTime, $endTime, $startTime, $endTime);

        // Execute the query
        $result = $db->executeisSlotAvailable($query, $params);

        // Fetch the result

        // If count > 0, the slot is taken
        return $result['count'] == 0;
        
    } catch (PDOException $e) {
        // Log or handle the error as necessary
        echo "Error checking slot availability: " . $e->getMessage();
        return false; // Return false in case of an error
    }
}

function saveAppointment($date, $startTime, $endTime, $name, $email, $phone)
{
    $db = new DatabaseConnection();

    // Insert the appointment into the database
    $query = "INSERT INTO appointment (appointment_date, start_time, end_time, customer_name, customer_email, customer_phone) 
              VALUES (?, ?, ?, ?, ?, ?)";

    $params = array($date, $startTime, $endTime, $name, $email, $phone);

    $stmt = $db->makeStatement($query, $params);

    return $stmt;
}

function getAppointmentDetails($date)
{
    $db = new DatabaseConnection();

    $query = "SELECT customer_name, start_time, end_time, customer_email, customer_phone, appointment_date FROM appointment WHERE appointment_date = ?";

    if ($date !== null){
        $stmt = $db->makeStatementArray($query, $date);
        return $stmt;
    } else {
        return null;            
    }


}



function saveOpeningHours($opendate, $openingTime, $closingTime) {
    
    $db = new DatabaseConnection();

    try {
        // Insert the opening hours into the openinghours table
        $query = "INSERT INTO openinghours (opening_date, open_time, close_time) VALUES ('$opendate', '$openingTime', '$closingTime')";
    

        $stmt = $db->makeStatementArray($query);
        return $stmt;
    } catch (PDOException $e) {
        // Log or handle errors
        return false;
    }
}


function deleteAppointment($appointmentId) {

    $db = new DatabaseConnection();

    $query = "DELETE FROM appointments WHERE appointment_id = ?";

    
        $stmt = $db->makeStatementArray($query, $appointmentId);
        return $stmt;


}

// function sendSuccessMail($date, $startTime) {
    
//     use PHPMailer\PHPMailer\PHPMailer;
//     use PHPMailer\PHPMailer\Exception;
    
//     require 'vendor/autoload.php';
    
//     $mail = new PHPMailer(true);
    
//     try {
//         // Server settings
//         $mail->SMTPDebug = 0;                       // Enable verbose debug output
//         $mail->isSMTP();                            // Set mailer to use SMTP
//         $mail->Host       = 'smtp.gmail.com';       // Specify main SMTP server
//         $mail->SMTPAuth   = true;                   // Enable SMTP authentication
//         $mail->Username   = 'your_email@gmail.com'; // SMTP username
//         $mail->Password   = 'your_password';        // SMTP password
//         $mail->SMTPSecure = 'tls';                  // Enable TLS encryption
//         $mail->Port       = 587;                    // TCP port to connect to
    
//         // Recipients
//         $mail->setFrom('your_email@gmail.com', 'Shababs Barbershop');
//         $mail->addAddress($email, $name);           // Add a recipient
    
//         // Content
//         $mail->isHTML(false);                       // Set email format to plain text
//         $mail->Subject = 'Terminbestätigung bei Shababs Barbershop';
//         $mail->Body    = "Liebe/r $name,\r\n\r\nvielen Dank für Ihre Terminbuchung bei Shababs Barbershop.\r\n\r\nIhr Termin ist am $date um $startTime.\r\n\r\nBitte erscheinen Sie 5-10 Minuten früher, um den Ablauf reibungslos zu gestalten.\r\n\r\nWir freuen uns auf Ihren Besuch!\r\n\r\nHerzliche Grüße,\r\nIhr Shababs-Team";
    
//         // Send the email
//         $mail->send();
//         echo 'Message has been sent';
//     } catch (Exception $e) {
//         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     }
    
// }
