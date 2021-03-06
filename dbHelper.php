<?php

    // ini_set('error_reporting', E_ALL);
    // ini_set( 'display_errors', 1 );
    //
    /**
    * provides a dbHelper object with methods we'll need in the app
    */
    class db
    {
        private $dbo = null;

        //private
        /***********************************************/

        public function __construct()
        {
            require_once 'database_connect.php';
            $this->dbo = new PDO("$databaseEngine:host=$databaseHost;dbname=$databaseName",
                $databaseUsername, $databasePassword);
        }

        public function verifyLogin($username, $password)
        {
            /**
            *   Returns false if can't log in else returns the user data
            */
            $queryString = sprintf("SELECT * FROM users WHERE username='%s'", $username);

            $query = $this->dbo->query($queryString);
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['password'] == $password)
                return $row;
            else
                return false;
        }
        public function close()
        {
            $link =null;
        }
        public function userExists($username)
        {
            $queryString ="SELECT * FROM `users` WHERE username = '$username'";
            $query = $this->dbo->query($queryString);

            return ($query->rowCount() > 0) ? true : false;
        }

        public function getAllUsers()
        {
            /**
            *   Returns a query
            */
            $queryString = "SELECT * FROM `users` INNER JOIN userDetail on users.username=userDetail.username";
            $query = $this->dbo->query($queryString);

            return $query;
        }

        public function getUserDetails($username)
        {
            $queryString = "SELECT * FROM users INNER JOIN userDetail on users.username=userDetail.username WHERE users.username LIKE '$username'";
            $query = $this->dbo->query($queryString);

            return $query;
        }

        public function searchUsers($searchTerm, $start, $goFor)
        {
            /**
            *   $start:
            *       the start of the LIMIT. Expects -1 if no LIMIT
            *   $goFor:
            *       the end of the LIMIT. Expects -1 if no LIMIT
            */

            //TODO: better search
            $start = (int)$start;
            $goFor = (int)$goFor;

            //base search:
            $queryString = "SELECT * FROM users INNER JOIN userDetail on users.username=userDetail.username";

            if (str_replace(' ', '', $searchTerm) != '')
                $queryString = $queryString . " WHERE users.username LIKE '$searchTerm'";

            if ($goFor != -1 && $start == -1)
                $queryString = $queryString .  " LIMIT 0, $goFor";

            elseif ($start != -1 && $goFor == -1)
                $queryString = $queryString .  " LIMIT $start, 2372662636281763";

            elseif($start != -1 && $goFor != -1)
                $queryString = $queryString .  " LIMIT $start, $goFor";

            $query = $this->dbo->query($queryString);

            return $query;
        }

        public function getUserPhotos($username)
        {
            $queryString = sprintf("SELECT * FROM photos WHERE owner='%s' ORDER BY id DESC",
                $username);
            $query = $this->dbo->query($queryString);

            return $query;
        }

        public function changePassword($username, $newPassword)
        {
            $queryString = "UPDATE users SET password='$newPassword' WHERE username = '$username'";
            $query = $this->dbo->query($queryString);
        }

        public function deleteUser($username)
        {
            if ($this->userExists($username))
            {
                $queryString = "DELETE FROM users WHERE username = '$username'";
                $query = $this->dbo->query($queryString);
            }
            else
                return false;
        }

        public function updateProfileInfo($username, $newValue, $type)
        {
            /**
            *   $type:
            *       the field to updateProfileInfo
            */

            $detailFields = array('about', 'gender', 'firstName', 'surname', 'location', 'dob');
            $userFields = array('username', 'password');
            if (in_array($type, $detailFields))
                $tableName = "userDetail";
            elseif (in_array($type, $userFields))
                $tableName = "users";
            $queryString = "UPDATE $tableName SET $type='$newValue' WHERE username='$username'";

            $query = $this->dbo->query($queryString);
        }

        public function createUser($username, $password, $email)
        {
            if (! $this->userExists($username))
            {
                $queryString = "INSERT INTO users (username, password, userType) VALUES
                ('$username', '$password', 'user')";
                $query = $this->dbo->query($queryString);

                $queryString = "INSERT INTO userDetail (username, email) VALUES
                ('$username', '$email')";
                $query = $this->dbo->query($queryString);

                return true;
            }
            else
                return false;
        }

        public function newPhoto($username, $album, $url, $name)
        {
            $queryString = sprintf(
            "INSERT INTO photos (owner, album, name, url) VALUES ('%s','%s', '%s','%s')",
            $username, $album, $name, $url);
            $query = $this->dbo->query($queryString);
        }
    }
?>

