<?php
include_once('./connection.php');
include_once('./function.php');

// Get the available opening days and barbers
$allowedDates = getOpeningDays();

// Check if a date was submitted and sanitize the input
$selectedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
$barbers = getBarberAvailability($selectedDate);

// Handle the POST request to save the appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form inputs
    $date = htmlspecialchars($_POST['date']);
    $slot = isset($_POST['timeSlot']) ? htmlspecialchars($_POST['timeSlot']) : '';
    $name = htmlspecialchars($_POST['customer_name']);
    $email = htmlspecialchars($_POST['customer_email']);
    $phone = htmlspecialchars($_POST['customer_phone']);
    $barber = htmlspecialchars($_POST['barber_select']);

    // Extract start and end times from the selected slot
    if (strpos($slot, '-') !== false) {
        list($startTime, $endTime) = explode('-', $slot);
    } else {
        $startTime = $endTime = ''; // Handle missing slot
    }

    if (!empty($date) && !empty($name) && !empty($startTime) && !empty($endTime)) {

        $barberID = getBarberIdByBarberName($barber);
        $message = "";
        $messageType = "";
        if ($barberID) {
            if (saveAppointment($date, $startTime, $endTime, $name, $email, $phone, $barberID)) {
                $message = "Termin erfolgreich gespeichert!";
                $messageType = "success";
                logMessage("Termin erfolgreich gespeichert. [date: '$date', startTime: '$startTime', endTime: '$endTime' , name: '$name', email: '$email', phone: '$phone', barberID: '$barberID']", "INFO");
                sendConfirmationEmail($email, $name, $date, $startTime, $endTime, $barber);
                //relocate Startseite
            } else {
                $message = "Fehler beim Speichern des Termins.";
                $messageType = "error";
                logMessage($message, "ERROR");    
            }
        } else {
            logMessage("Barber nicht gefunden in getBarberIdByName", "ERROR");          
        }

        echo "<script>
        window.onload = function() {
            showToast('$message', '$messageType');
        };
      </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termin</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="assets/images/shababs-logo.ico">
    <link href="assets/css/Termin.css" rel="stylesheet">
    <style>
        #combobox,
        #nameAndEmail {
            display: none;
        }
    </style>
</head>

<header>
    <?php include('./Header.php'); ?>
</header>

<body>
    <div id="termin-div">
        <!-- Date selection form (GET) -->
        <form action="" method="GET" id="dateForm">
            <!-- The datepicker input field -->
            <input type="text" id="datepicker" name="date" placeholder="Wählen Sie ein Datum" class="placeholder" value="<?php echo $selectedDate; ?>" required>
        </form>

        <!-- Appointment form (POST) - Only visible when date is selected -->
        <form action="" method="POST" id="appointmentForm">

            <input type="hidden" name="date" value="<?php echo $selectedDate; ?>">
            <!-- Time slot combobox -->
            <div id="combobox">
            <label for="barberSelect">Friseur auswählen: *</label>
                <select id="barberSelect" name="barber_select" required>
                    <?php foreach ($barbers as $barber) : ?>
                        <option value="<?php echo htmlspecialchars($barber['barber_name']); ?>">
                            <?php echo htmlspecialchars($barber['barber_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="timeSlotSelect">Uhrzeit auswählen: *</label>

                <select id="timeSlotSelect" name="timeSlot" required>
                    <?php
                    if ($selectedDate) {
                        if (strtotime($selectedDate)) {
                            $times = getOpeningClosingTime($selectedDate);

                            if (!empty($times)) {
                                // Get the selected barber name from the form submission
                                $barberName = isset($_POST['barber_select']) ? $_POST['barber_select'] : null;
                                $barberName ? null : $barberName = "mahmood";
                                // Generate time slots using the selected barber name
                                $timeSlots = generateTimeSlotsWithAvailability($times['openTime'], $times['closeTime'], $selectedDate, $barberName);
                                foreach ($timeSlots as $slot) {
                                    echo $slot;
                                }
                            } else {
                                echo "<option value='no-time'>Keine Öffnungszeiten verfügbar</option>";
                                logMessage("Keine Öffnungszeiten verfügbar", "ERROR");
                            }
                        } else {
                            echo "<option value='invalid-date'>Ungültiges Datumsformat</option>";
                            logMessage("Ungültiges Datumsformat", "ERROR");
                        }
                    } else {
                        echo "<option value=''>Bitte wählen Sie ein Datum</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Name and email fields -->
            <div id="nameAndEmail">
                <div class="form-group">
                    <label for="customerNameForm">Name *</label>
                    <input type="text" class="form-control" id="customerNameForm" name="customer_name" placeholder="Name *" required>
                </div>
                <div class="form-group">
                    <label for="customerMailForm">E-Mail-Adresse</label>
                    <input type="email" class="form-control" id="customerMailForm" name="customer_email" placeholder="E-Mail *" required>
                </div>
                <div class="form-group">
                    <label for="customerPhoneNrForm">Telefonnummer</label>
                    <input type="text" class="form-control" id="customerPhoneNrForm" name="customer_phone" placeholder="Telefonnummer">
                </div>
            </div>

            <input type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" value="Termin buchen">
        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            const allowedDates = <?php echo json_encode($allowedDates); ?>;
            const selectedDate = "<?php echo $selectedDate; ?>"; // Get the selected date from PHP

            // Function to enable only specific dates
            function enableSpecificDates(date) {
                const formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
                if (allowedDates.includes(formattedDate)) {
                    return [true, "", "Verfügbar"];
                }
                return [false, "", "Nicht verfügbar"];
            }

            // Initialize the datepicker
            $("#datepicker").datepicker({
                beforeShowDay: enableSpecificDates,
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                onSelect: function(dateText, inst) {
                    // Submit the GET form with selected date
                    $('#dateForm').submit();
                }
            });

            // If a date is already selected, show the appointment form
            if (selectedDate) {
                $('#combobox').show();
                $('#nameAndEmail').show();
            }
        });

        function showToast(message, type) {
            if (!message) return; // If no message

            const toastContainer = document.getElementById("toast-container");
            const toast = document.createElement("div");
            toast.className = "toast " + type;
            toast.innerHTML = message;

            toastContainer.appendChild(toast);

            setTimeout(() => toast.classList.add("show"), 100); // Show the toast

            setTimeout(() => {
                toast.classList.add("hide");
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>

    <div id="toast-container"></div>

</body>

</html>
