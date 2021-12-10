<?php
    session_start();
    if ((!isset($_POST['Username'])) || (!isset($_POST['Password']))) {
        header('Location: index.php');
        exit();
    }
    
    class Login{

        function verfication($username){
            require_once "connect.php";
            $this->conn = new mysqli($host, $db_user, $db_password, $db_name);
            if ($this->conn->connect_errno!=0) {
                echo "Error: ".$this->conn->connect_errno;
            } else {
                $this->conn->query('SET NAMES utf8');
                $this->conn->query('SET CHARACTER_SET utf8_unicode_ci');
                $verfi = $this->conn->query(sprintf("SELECT `Verification` FROM users WHERE `Login`='%s'", mysqli_real_escape_string($this->conn, $username)));
                $check_verfi = mysqli_num_rows($verfi);
                if ($check_verfi>0) {
                    $row = $verfi->fetch_assoc();
                    $verification = $row['Verification'];
                    if ($verification == "close") {
                        $_SESSION['e_login'] = '<span style="color:#ff0000;"><p>Konto zostało zablokowane!</p></span>';
                        header('Location: index.php');
                        $this->conn->close();
                        exit();
                    } else {
                        login($username, $password, $ipaddress, $checkbox);
                        $this->conn->close();
                    }
                } else {
                    $_SESSION['e_login'] = '<span style="color:#ff0000;"><p>Nieprawidłowy login lub hasło!</p></span>';
                    header('Location: index.php');
                    $this->conn->close();
                    exit();
                }
            }
        
            return 0;
        }

        function login($username, $password, $ipaddress, $checkbox){
            require_once "connect.php";
            $this->conn = new mysqli($host, $db_user, $db_password, $db_name);
            if ($this->conn->connect_errno!=0) {
                echo "Error: ".$this->conn->connect_errno;
            } else {
                $this->conn->query('SET NAMES utf8');
                $this->conn->query('SET CHARACTER_SET utf8_unicode_ci');
                $login = $this->conn->query(sprintf("SELECT * FROM users WHERE `Login`='%s'", mysqli_real_escape_string($this->conn, $username)));
                $check_login = mysqli_num_rows($login);;
                if ($check_login>0) {
                    $row = $login->fetch_assoc();
                    if(password_verify($password, $row['Password'])){
                        $_SESSION['Login-status'] = true;
                        $_SESSION['Id'] = $row['Id'];
                        $_SESSION['Login'] = $row['Login'];
                        $_SESSION['Email'] = $row['Email'];
                        $_SESSION['Register-DATE'] = $row['Register-DATE'];
                        $_SESSION['LastLogin-DATE'] = $row['LastLogin-DATE'];
                        $_SESSION['Fname'] = $row['Fname'];
                        $_SESSION['Lname'] = $row['Lname'];

                        $updatetimelogin = $this->conn->query('UPDATE users SET `LastLogin-DATE`=NOW() WHERE `Login`="'.$_SESSION['Login'].'"');
                        //$updatelogs = $this->conn->query('INSERT INTO logs SET `Date`=NOW(), `Color`="009933", `Bg-color`="f2f2f2", `User`="'.$_SESSION['Login'].'", `Description`="User logged-in: '.$ipaddress.'"');

                        // if 'remember me' is on -> function SaveToAutoLogin
                        if(isset($checkbox) && $checkbox == "on"){
                            $password = $row['Password'];
                            $this->SaveToAutoLogin($username, $password, $ipaddress);
                        }

                        $login->free_result();
                        if(isset($_SESSION['Last_Page'])){
                            $Last_Page = $_SESSION['Last_Page'];
                            header('Location: '.$Last_Page.'');
                            $this->conn->close();
                            exit();
                        } else {
                            header('Location: chatpanel.php');
                            $this->conn->close();
                            exit();
                        }
                    } else {
                        $_SESSION['e_login'] = '<span style="color:#ff0000;"><p>Nieprawidłowy login lub hasło!</p></span>';
                        header('Location: index.php');
                        $this->conn->close();
                        exit();
                    }
                } else {
                    $_SESSION['e_login'] = '<span style="color:#ff0000;"><p>Nieprawidłowy login lub hasło!</p></span>';
                    header('Location: index.php');
                    $this->conn->close();
                    exit();
                }
            }
            return 0;
        }

        function SaveToAutoLogin($username, $password, $ipaddress){
            $_ga = "_ga";  // account
            $_gav = $username;
            setcookie($_ga, $_gav, time() + (86400 * 30), "/"); // 86400 = 1 day COOKIE LIFE TIME: 30 DAYS

            $_gp = "_gp";  // password
            $_gpv = $password;
            setcookie($_gp, $_gpv, time() + (86400 * 30), "/"); // 86400 = 1 day COOKIE LIFE TIME: 30 DAYS

            $_gc = "_gc";  // connection adress
            $_gcv = $ipaddress;
            setcookie($_gc, $_gcv, time() + (86400 * 30), "/"); // 86400 = 1 day COOKIE LIFE TIME: 30 DAYS

            return 0;
        }

        function newLogin($username, $password, $ipaddress, $checkbox){
            $this->verfication($username);
            return 0;
        }
    }


    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    if(isset($_POST['Checkbox'])){
        $checkbox = $_POST['Checkbox'];
    } else {
        $checkbox = "off";
    }
    $username = htmlentities($username, ENT_QUOTES, "UTF-8");
    $login = new Login($username, $password, $ipaddress, $checkbox);
    $login->newLogin($username, $password, $ipaddress, $checkbox);
