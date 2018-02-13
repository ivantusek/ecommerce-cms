<?php
    include '../lib/Session.php';
    Session::checkLogin();
    include_once '../lib/Database.php';
    include_once '../helpers/Format.php';
?>

<?php
/**
 * Adminlogin Class
 */

class AdminLogin{

    private $db;
    private $fm;

    public function __construct (){

        $this->db = new Database();
        $this->fm = new Format();

    }

    public function adminlogin ($adminUser, $adminPass){

        $adminUser = $this->validation($adminUser);
        $adminPass = $this->validation($adminPass);

        $adminUser = mysqi_real_escape_string($this->db->link, $adminUser);
        $adminPass = mysqli_real_escape_string($this->db->link, $adminPass);

        if(empty($adminUser) || empty($adminPass)){

            $loginmsq = "Username or Password must not be empty!";
            return $loginmsq;

        }
        else
        {

            $query = "SELECT * FROM admin WHERE adminUser = '$adminUser' AND adminPass = '$adminPass' ";
            $result = $this->db->select($query);
            if($result != flase){

             $value = $result->fetch_assoc();
             Session::set("adminlogin", true);
             Session::set("adminId", $adminId['adminId']);
             Session::set("adminUser", $adminId['adminUser']);
             Session::set("adminName", $adminId['adminName']);

             header("Location:dashboard.php" );

            }
            else
            {
                $loginmsq = "Username or Password not match!";
                return $loginmsq;
            }

        }


    }

}

