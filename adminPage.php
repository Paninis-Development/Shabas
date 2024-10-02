<?php
include_once('./connection.php');
include_once('./function.php');

// Get the available opening days
$allowedDates = getOpeningDays();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Picker</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        #combobox {
            display: none;
        }
        #nameAndEmail { 
            display: none;
        }
    </style>
</head>

<header>
    <?php include('./Header.php'); ?>
</header>

<body>
    <form action="">
        <input type="text" id="datepicker" placeholder="Select a date" class="placeholder">
        <div id="combobox">
            <label for="options">Choose an option:</label>
            <select id="options">
                
                <option value="option1">Option 1</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
            </select>
        </div>
        <div id="nameAndEmail">
            <form>
                <div class="form-group">
                    <label for="customerNameForm">Customer Name</label>
                    <input type="text" class="form-control" id="customerNameForm" placeholder="Name *" required>
                </div>
                <div class="form-group">
                    <label for="customerMailForm">Customer Mail</label>
                    <input type="email" class="form-control" id="customerMailForm" placeholder="Email *" required>
                </div>
                <div class="form-group">
                    <label for="customerPhoneNrForm">Customer phone number</label>
                    <input type="text" class="form-control" id="customerPhoneNrForm" placeholder="Phone Number">
                </div>
        </div>

        <input type="submit" value="Submit Form">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            const allowedDates = <?php echo json_encode($allowedDates); ?>;
            
            if (allowedDates.length === 0) {
                alert("No available appointments.");
                $('#datepicker').prop('disabled', true);
            } else {
                function enableSpecificDates(date) {
                    const formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
                    if (allowedDates.includes(formattedDate)) {
                        return [true, "", "Available"];
                    }
                    return [false, "", "Unavailable"];
                }

                $("#datepicker").datepicker({
                    beforeShowDay: enableSpecificDates,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(dateText, inst) {
                        $('#combobox').show();
                        $('#nameAndEmail').show();
                    }
                });
            }
        });
    </script>
</body>

</html>
