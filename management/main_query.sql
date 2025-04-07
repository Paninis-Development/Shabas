use shababs_web;

create database if not exists shababs_web;

use shababs_web;

select * from admin;
ALTER TABLE admin MODIFY COLUMN Password VARCHAR(100);
delete from admin where AdminID = 1;

insert into admin values(null, "shababs.barbershop@gmail.com", "$2y$10$YldxQhTsHATc7HmzBdgfze0q0FJsYczdgtmFt3sU1p/UCqPZrnlMu");
/* ShababsAdmin1.! */

ALTER TABLE admin MODIFY COLUMN Password VARCHAR(255);

delete from admin where AdminID = 1 ;
select * from admin;


UPDATE admin
SET password = '$2y$10$YldxQhTsHATc7HmzBdgfze0q0FJsYczdgtmFt3sU1p/UCqPZrnlMu'
WHERE email = 'shababs.barbershop@gmail.com';

select * from admin;

Select * from openinghours;

select * from barber;
insert into barber (barber_name, barber_mail) values
('mahmood', 'mahmood@gmail.com'),
('panini', 'panini@gmail.com'),
('litsch', 'litsch@gmail.com');

delete from barber where BarberID = 2;

-- Insert opening hours for several days
INSERT INTO openinghours (opening_date, open_time, close_time)
VALUES 
('2025-05-27', '09:00:00', '17:00:00'),
('2025-05-28', '09:00:00', '17:00:00'),
('2025-05-29', '10:00:00', '15:00:00'),
('2025-05-30', '09:00:00', '17:00:00');


select * from appointment;

-- Insert some booked appointments for a specific date
INSERT INTO appointment (appointment_date, start_time, end_time, customer_name, customer_email, customer_phone, barberID)
VALUES 
('2025-05-27', '09:00:00', '09:40:00', 'John Doe', 'johndoe@example.com', '1234567890', 2),
('2025-05-27', '09:40:00', '10:20:00', 'Jane Smith', 'janesmith@example.com', '0987654321', 3),
('2025-05-27', '11:00:00', '11:40:00', 'Bob Johnson', 'bobjohnson@example.com', '5555555555', 1),

('2025-05-28', '09:00:00', '09:40:00', 'Alice Williams', 'alicewilliams@example.com', '2222222222', 1),
('2025-05-28', '10:20:00', '11:00:00', 'David Brown', 'davidbrown@example.com', '3333333333', 2),

('2025-05-29', '10:00:00', '10:40:00', 'Charlie Clark', 'charlieclark@example.com', '4444444444',1),
('2025-05-29', '10:40:00', '11:20:00', 'Eva Green', 'evagreen@example.com', '6666666666', 2);

delete from appointment where AppointmentID = 7;

SELECT open_time, close_time FROM openinghours WHERE opening_date = '2025-05-27';
Select * from openinghours;
select * from appointment;


delete from appointment where AppointmentId = 21;




select * from appointment where appointment_date = "2024-09-27";

SELECT COUNT(*) as count FROM appointment
              WHERE appointment_date = 2024-09-27 
              AND (start_time <= "09:00" AND end_time >= "09:45" );
              
              
              
			SELECT COUNT(*) as count 
FROM appointment
WHERE appointment_date = '2024-09-27' 
AND (
    (start_time < '09:40:00' AND end_time > '09:00:00') -- Appointment wraps around the new slot
    OR (start_time >= '09:00:00' AND start_time < '09:40:00') -- Slot starts during an appointment
    OR (end_time > '09:00:00' AND end_time <= '09:40:00') -- Slot ends during an appointment
);                   

SELECT appointment_date AS 'Datum', start_time AS 'Start', end_time AS 'Ende', customer_email AS 'Kunde Email', customer_phone AS 'Kunde Telefonnummer' 
FROM appointment WHERE appointment_date = ?;

select * from appointment;

SELECT customer_name, start_time, end_time, customer_email, customer_phone, appointment_date
FROM appointment WHERE appointment_date = '2024-09-27';


SELECT customer_name, start_time, end_time, customer_email, customer_phone, appointment_date  FROM appointment WHERE appointment_date = '2024-09-27';

SELECT open_time, close_time FROM openinghours WHERE opening_date = "2024-09-29";

select * from appointment;

select * from openinghours;
INSERT INTO openinghours (opening_date, open_time, close_time) VALUES (2024-10-31, '05:01', '15:54');

delete from openinghours where opening_date = '2024-10-31';


select * from barber;
insert into barber (barber_name) values ('mahmood');
insert into barber (barber_name) values ('kaman');
SELECT barber_name FROM barber;


SELECT COUNT(*) AS count
FROM appointment
INNER JOIN barber ON appointment.barberID = barber.BarberID
WHERE appointment_date = ? 
  AND barber.barber_name = ? 
  AND (
      (start_time < ? AND end_time > ?)
      OR (start_time >= ? AND start_time < ?)
      OR (end_time > ? AND end_time <= ?)
  );
  
  SELECT COUNT(*) AS count
FROM appointment
INNER JOIN barber ON appointment.barberID = barber.BarberID
WHERE appointment_date = '2024-12-27'  -- Replace with an actual date
  AND barber.barber_name = 'mahmood'  -- Replace with an actual barber name
  AND (
      (start_time < '11:40:00' AND end_time > '11:00:00')  -- Replace with actual times
      OR (start_time >= '11:00:00' AND start_time < '11:40:00')
      OR (end_time > '11:00:00' AND end_time <= '11:40:00')
  );

SELECT barber_name FROM barber;
select * from appointment;
select * from barber;

select * from appointment;

SELECT customer_name, start_time, end_time, customer_email, customer_phone, appointment_date, barber_name FROM appointment WHERE appointment_date = '2024-12-27';

SELECT BarberID FROM barber WHERE barber_name = "mahmood";


INSERT INTO appointment (appointment_date, start_time, end_time, customer_name, customer_email, customer_phone, barberID) VALUES ("2025-05-27", "09:00", "10:00", "qkje", "kajdf", "oadfjh", 1);


-- testing a urlaub/krankenstand solution

CREATE TABLE IF NOT EXISTS `shababs_web`.`barber_availability` (
  `AvailabilityID` INT(11) NOT NULL AUTO_INCREMENT,
  `BarberID` INT(11) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `reason` ENUM('vacation', 'sick') NOT NULL,
  PRIMARY KEY (`AvailabilityID`),
  FOREIGN KEY (`BarberID`) REFERENCES `shababs_web`.`barber`(`BarberID`) ON DELETE CASCADE
) ENGINE = InnoDB;


SELECT BarberID, barber_name 
FROM shababs_web.barber 
WHERE BarberID NOT IN (
    SELECT BarberID FROM shababs_web.barber_availability
    WHERE ? BETWEEN start_date AND end_date
);

INSERT INTO barber_availability (`BarberID`, `start_date`, `end_date`, `reason`) 
VALUES (1, '2025-05-29', '2025-05-30', 'vacation');

SELECT * FROM barber_availability
WHERE '2024-04-10' NOT BETWEEN start_date AND end_date;


select * from barber_availability;

SET SQL_SAFE_UPDATES = 1;

DELETE FROM appointment;

-- Alle Termine löschen
DELETE FROM appointment;

-- Neue Termine mit einer Stunde Pause
-- Beispiel für die Woche vom 07. April 2025 bis 13. April 2025

-- Montag, 07. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Max Mustermann', 'max@example.com', '+491234567890', '2025-04-07', 1, '09:00:00', '10:00:00'),
('Max Mustermann2', 'max@example.com', '+491234567890', '2025-04-07', 2, '09:00:00', '10:00:00'),
('Lisa Müller', 'lisa@example.com', '+491234567891', '2025-04-07', 1, '11:00:00', '12:00:00'),
('John Doe', 'john@example.com', '+491234567892', '2025-04-07', 2, '13:00:00', '14:00:00'),
('Anna Schmidt', 'anna@example.com', '+491234567893', '2025-04-07', 2, '15:00:00', '16:00:00'),
('Peter Meier', 'peter@example.com', '+491234567894', '2025-04-07', 3, '17:00:00', '18:00:00');

-- Dienstag, 08. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Sophie Klein', 'sophie@example.com', '+491234567895', '2025-04-08', 1, '09:00:00', '10:00:00'),
('Michael Braun', 'michael@example.com', '+491234567896', '2025-04-08', 1, '11:00:00', '12:00:00'),
('Sarah Weber', 'sarah@example.com', '+491234567897', '2025-04-08', 2, '13:00:00', '14:00:00'),
('Tim Fischer', 'tim@example.com', '+491234567898', '2025-04-08', 2, '15:00:00', '16:00:00'),
('Klara Vogel', 'klara@example.com', '+491234567899', '2025-04-08', 3, '17:00:00', '18:00:00');

-- Mittwoch, 09. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Tom Schneider', 'tom@example.com', '+491234567900', '2025-04-09', 1, '09:00:00', '10:00:00'),
('Jasmin Hoffmann', 'jasmin@example.com', '+491234567901', '2025-04-09', 1, '11:00:00', '12:00:00'),
('David Lang', 'david@example.com', '+491234567902', '2025-04-09', 2, '13:00:00', '14:00:00'),
('Eva König', 'eva@example.com', '+491234567903', '2025-04-09', 2, '15:00:00', '16:00:00'),
('Markus Müller', 'markus@example.com', '+491234567904', '2025-04-09', 3, '17:00:00', '18:00:00');

-- Donnerstag, 10. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Katrin Schuster', 'katrin@example.com', '+491234567905', '2025-04-10', 1, '09:00:00', '10:00:00'),
('Niklas Weber', 'niklas@example.com', '+491234567906', '2025-04-10', 1, '11:00:00', '12:00:00'),
('Monika Fischer', 'monika@example.com', '+491234567907', '2025-04-10', 2, '13:00:00', '14:00:00'),
('Tobias Richter', 'tobias@example.com', '+491234567908', '2025-04-10', 2, '15:00:00', '16:00:00'),
('Beatrix Jäger', 'beatrix@example.com', '+491234567909', '2025-04-10', 3, '17:00:00', '18:00:00');

-- Freitag, 11. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Lena Schulz', 'lena@example.com', '+491234567910', '2025-04-11', 1, '09:00:00', '10:00:00'),
('Oliver Braun', 'oliver@example.com', '+491234567911', '2025-04-11', 1, '11:00:00', '12:00:00'),
('Mia Fischer', 'mia@example.com', '+491234567912', '2025-04-11', 2, '13:00:00', '14:00:00'),
('Felix Schneider', 'felix@example.com', '+491234567913', '2025-04-11', 2, '15:00:00', '16:00:00'),
('Julia König', 'julia@example.com', '+491234567914', '2025-04-11', 3, '17:00:00', '18:00:00');

-- Samstag, 12. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Paul Weber', 'paul@example.com', '+491234567915', '2025-04-12', 1, '09:00:00', '10:00:00'),
('Nina Jäger', 'nina@example.com', '+491234567916', '2025-04-12', 1, '11:00:00', '12:00:00'),
('Johanna Braun', 'johanna@example.com', '+491234567917', '2025-04-12', 2, '13:00:00', '14:00:00'),
('Matthias Fischer', 'matthias@example.com', '+491234567918', '2025-04-12', 2, '15:00:00', '16:00:00'),
('Victoria König', 'victoria@example.com', '+491234567919', '2025-04-12', 3, '17:00:00', '18:00:00');

-- Sonntag, 13. April 2025
INSERT INTO appointment (customer_name, customer_email, customer_phone, appointment_date, barberID, start_time, end_time) VALUES
('Karla Schulz', 'karla@example.com', '+491234567920', '2025-04-13', 1, '09:00:00', '10:00:00'),
('Stefan Hoffmann', 'stefan@example.com', '+491234567921', '2025-04-13', 1, '11:00:00', '12:00:00'),
('Elke Fischer', 'elke@example.com', '+491234567922', '2025-04-13', 2, '13:00:00', '14:00:00'),
('Hans Richter', 'hans@example.com', '+491234567923', '2025-04-13', 2, '15:00:00', '16:00:00'),
('Jörg Weber', 'joerg@example.com', '+491234567924', '2025-04-13', 3, '17:00:00', '18:00:00');



