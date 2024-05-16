<?php
class DatabaseConnection
{
    private $con;

    function __construct()
    {
        $server = 'localhost:3306';
        $user = 'root';
        $pwd = '';
        $schema = 'shababs_web';

        try {
            $this->con = new PDO('mysql:host=' . $server . ';dbname=' . $schema . ';charset=utf8', $user, $pwd);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo 'Fehler bei der Verbindung zur Datenbank: ' . $e->getMessage();
        }
    }
    
    function hash_password($pw){
        $hashedPassword = hash('sha256', trim($pw));

        return $hashedPassword;

    }


    function getAppointment()
    {
        $stmt = $this->con->prepare("SELECT * from Appointment");

        $stmt->execute();

        // holen aller gerichte
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($appointments != null) {
            return $appointments;
        } else {
            return false;
        }
    }

    function checkUser($email, $password)
    {
        $hashedPassword = $this->hash_password($password);
        try
        {
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM Benutzer WHERE mail = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $checkEmail = $stmt->fetchColumn();

            $stmt=null;

            $stmt = $this->con->prepare("SELECT COUNT(*) FROM Benutzer WHERE passwort = :password");
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            $checkPassword = $stmt->fetchColumn();

            return ($checkEmail > 0) && ($checkPassword > 0);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    function checkIfUserAlreadyExists($email){
        try
        {
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM Benutzer WHERE mail = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $checkEmail = $stmt->fetchColumn();

            return ($checkEmail > 0);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    function createNewUser($firstName, $lastName, $class, $email, $password){
        $randomBytes = random_bytes(64);
        $token = bin2hex($randomBytes);
        $sha256Token = hash('sha256', $token);

        $hashedPassword = $this->hash_password($password);
        
        try
        {
            if($this->checkIfUserAlreadyExists($email)){
                if($this->isAccepted($email,$hashedPassword)){
                    return false;
                }
                return true;
            }
            else{

                $stmt = $this->con->prepare("Insert into Benutzer(rolle_idrolle, mail, passwort, vname, nname, class, pin) values (3, :email , :password, :firstName, :lastName, :class, :pin)");
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
                $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
                $stmt->bindParam(':class', $class, PDO::PARAM_STR);
                $stmt->bindParam(':pin', $sha256Token, PDO::PARAM_STR);
                $stmt->execute();
            }
            return true;
        }
        catch(Exception $e)
        {
        return false;
        }
    }

    function isAccepted($email, $password){
        try
        {
            $stmt = $this->con->prepare("select Count(*) from Benutzer where mail=:email and Rolle_idRolle=3;");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $isAccepted=$stmt->fetchColumn();
            return ($isAccepted <= 0);
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    function makeStatement($query, $array = null)
    {
        try
        {
            $stmt = $this->con->prepare($query);
            $stmt->execute($array);
            return $stmt;
        } catch(Exception $e)
        {
            return $e;
        }
    }  
}
