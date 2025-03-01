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