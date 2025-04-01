<?php
require_once('./connection.php');

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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


function generateTimeSlotsWithAvailability($openTime, $closeTime, $date, $barberName)
{
    $slots = [];
    $interval = 60 * 60; // 45 minutes in seconds
    $startTime = strtotime($openTime);
    $endTime = strtotime($closeTime);
    $slots[] = "<option value='' selected disabled>Bitte Uhrzeit auswählen *</option>";
    while ($startTime < $endTime) {
        $slotStart = date("H:i", $startTime);
        $slotEnd = date("H:i", $startTime + $interval);

        // Check if the time slot is available
        if (isSlotAvailable($date, $slotStart, $slotEnd, $barberName)) {
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



function isSlotAvailable($date, $startTime, $endTime, $barberName)
{
    $db = new DatabaseConnection();

    // Wrap the database interaction in a try-catch block
    try {
        // Query to check if any appointment overlaps with the selected time slot on the specified date
        $query = "SELECT COUNT(*) AS count
FROM appointment
INNER JOIN barber ON appointment.barberID = barber.BarberID
WHERE appointment_date = ? 
  AND barber.barber_name = ? 
  AND (
      (start_time < ? AND end_time > ?)
      OR (start_time >= ? AND start_time < ?)
      OR (end_time > ? AND end_time <= ?)
  );";

        // Pass the correct number of parameters for each placeholder
        $params = array($date, $barberName, $endTime, $startTime, $startTime, $endTime, $startTime, $endTime);

        // Execute the query
        $result = $db->executeisSlotAvailable($query, $params);

        // Fetch the result

        // If count > 0, the slot is taken
        return $result['count'] == 0;
    } catch (PDOException $e) {
        // Log or handle the error as necessary
        logMessage("Error checking slot availability: " . $e->getMessage() . "", "ERROR");
        return false; // Return false in case of an error
    }
}

function saveAppointment($date, $startTime, $endTime, $name, $email, $phone, $barberID)
{
    $db = new DatabaseConnection();

    // Insert the appointment into the database
    $query = "INSERT INTO appointment (appointment_date, start_time, end_time, customer_name, customer_email, customer_phone, barberID) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $params = array($date, $startTime, $endTime, $name, $email, $phone, $barberID);

    $stmt = $db->makeStatement($query, $params);

    return $stmt;
}

function getAppointmentDetails($date)
{
    $db = new DatabaseConnection();

    $query = "SELECT 
    a.AppointmentID, 
    a.customer_name, 
    a.start_time, 
    a.end_time, 
    a.customer_email, 
    a.customer_phone, 
    a.appointment_date, 
    a.barberID,
    b.barber_name
FROM appointment a
JOIN barber b ON a.barberID = b.BarberID
WHERE a.appointment_date = ?;
";

    if ($date !== null) {
        $stmt = $db->makeStatementArray($query, $date);
        return $stmt;
    } else {
        return null;
    }
}
function getBarberIdByBarberName($barberName)
{
    $db = new DatabaseConnection();

    $query = "SELECT BarberID FROM barber WHERE barber_name = ?";
    
    // Übergib den Parameter als Array
    $stmt = $db->makeStatement($query, [$barberName]);

    // Hole das Ergebnis
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result ? $result['BarberID'] : null;
}

function getBarber()
{
    $db = new DatabaseConnection();

    $query = "SELECT barber_name FROM barber";

    $stmt = $db->makeStatement($query, null);

    return $stmt;
}

function getBarberDetails()
{
    $db = new DatabaseConnection();

    $query = "SELECT BarberID, barber_name, barber_mail FROM barber";

    $stmt = $db->makeStatement($query, null);

    return $stmt;
}

function addBarber($name, $email)
{

    $db = new DatabaseConnection();

    try {
        // Insert the opening hours into the openinghours table
        $query = "INSERT INTO barber (barber_name, barber_mail) VALUES ('$name', '$email')";


        $stmt = $db->makeStatementArray($query);
        return $stmt;
    } catch (PDOException $e) {
        // Log or handle errors
        return false;
    }
}

function deleteBarber($barberId)
{
    $db = new DatabaseConnection();

    $query = "DELETE FROM barber WHERE BarberID = ?";

    $stmt = $db->makeStatement($query, [$barberId]);

    return $stmt;
}




function saveOpeningHours($opendate, $openingTime, $closingTime)
{

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


function deleteAppointment($appointmentId)
{

    $db = new DatabaseConnection();

    $query = "DELETE FROM appointment WHERE AppointmentID = ?";


    $stmt = $db->makeStatement($query, [$appointmentId]);
    return $stmt;
}
function closeDay($date)
{
    $db = new DatabaseConnection();

    // Deaktiviere temporär den Safe Update Mode
    $db->makeStatement("SET SQL_SAFE_UPDATES = 0");

    // Führe die DELETE-Abfrage aus
    $query = "DELETE FROM openinghours WHERE opening_date = ?";
    $stmt = $db->makeStatementArray($query, $date);

    // (Optional) Aktiviere den Safe Update Mode wieder, wenn benötigt
    $db->makeStatement("SET SQL_SAFE_UPDATES = 1");

    return $stmt;
}

function logMessage($message, $type = "INFO") {
    date_default_timezone_set('Europe/Berlin');
    $logFile = "log/log.txt"; // Speicherort des Log-Files

    // ANSI Farbcodes für die Konsole (werden im Editor evtl. nicht farbig angezeigt)
    $colors = [
        "ERROR" => "\033[31m",   // Rot
        "WARNING" => "\033[33m", // Gelb
        "INFO" => "\033[32m",    // Grün
        "RESET" => "\033[0m"     // Reset auf Standardfarbe
    ];

    // Log-Nachricht formatieren mit Zeitstempel
    $timestamp = date("Y-m-d H:i:s", time());
    $logEntry = "{$colors[$type]}[$timestamp] [$type] $message{$colors['RESET']}\n";

    // Nachricht in Datei speichern
    file_put_contents($logFile, strip_tags($logEntry), FILE_APPEND);
}

function sendConfirmationEmail($to, $name, $date, $startTime, $endTime, $barber) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Server Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shababs.barbershop.linz@gmail.com';    // Change this
        $mail->Password = 'ieqfehsrtthzdwjt';            // Change this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        

        // Email Setup
        $mail->setFrom('shababs.barbershop.linz@gmail.com', 'Shababs Barbershop'); // Change this
        $mail->addAddress($to, $name); // Recipient

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "Terminbestaetigung - Shababs Barbershop";
        $mail->Body = "
            <p>Hallo <strong>$name</strong>,</p>
            <p>Ihr Termin wurde erfolgreich gebucht:</p>
            <ul>
                <li><strong>Datum:</strong> $date</li>
                <li><strong>Uhrzeit:</strong> $startTime - $endTime</li>
                <li><strong>Barber:</strong> $barber</li>
            </ul>
            <p>Vielen Dank fuer Ihre Buchung!</p>
            <p>Mit freundlichen Gruessen,<br>Shababs Barbershop</p>
        ";
        
        $mail->send();
        logMessage("Confirmation email sent to $to", "INFO");
    } catch (Exception $e) {
        logMessage("Error sending email: " . $mail->ErrorInfo, "ERROR");
    }
}

function sendDeleteEmail($to,  $name, $date, $startTime) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Server Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shababs.barbershop.linz@gmail.com';    // Change this
        $mail->Password = 'ieqfehsrtthzdwjt';            // Change this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        

        // Email Setup
        $mail->setFrom('shababs.barbershop.linz@gmail.com', 'Shababs Barbershop'); // Change this
        $mail->addAddress($to, $to); // Recipient

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "Terminaenderung - Shababs Barbershop";
        $mail->Body = "
            <p>Hallo <strong>$name</strong>,</p>
            <p>Ihr Termin am <strong>$date um $startTime</strong> wurde leider storniert.</p>
            <p>Bitte vereinbaren Sie einen neuen Termin. Nutzen Sie dazu folgenden Link:</p>
            <p><a href='https://www.orf.at' style='color: #007bff; text-decoration: none;'>Neuen Termin buchen</a></p>
            <p>Vielen Dank fuer Ihr Verstaendnis!</p>
            <p>Mit freundlichen Gruessen,<br>Shababs Barbershop</p>
        ";
        
        
        $mail->send();
        logMessage("Delete email sent to $to", "INFO");
    } catch (Exception $e) {
        logMessage("Error sending email: " . $mail->ErrorInfo, "ERROR");
    }
}
