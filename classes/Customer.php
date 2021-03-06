<?php
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath.'/../lib/Database.php');
    include_once ($filepath.'/../helpers/Format.php');
?>


<?php

/**
 * Customer class
 */
class Customer{

    private $db;
    private $fm;


    public function __construct()
    {

        $this->db = new Database();
        $this->fm = new Format();

    }

    public function customerRegistration($data)
    {
        $name       = mysqli_real_escape_string($this->db->link, $data['name']);
        $address    = mysqli_real_escape_string($this->db->link, $data['address']);
        $city       = mysqli_real_escape_string($this->db->link, $data['city']);
        $country    = mysqli_real_escape_string($this->db->link, $data['country']);
        $zip        = mysqli_real_escape_string($this->db->link, $data['zip']);
        $phone      = mysqli_real_escape_string($this->db->link, $data['phone']);
        $email      = mysqli_real_escape_string($this->db->link, $data['email']);
        $pass       = mysqli_real_escape_string($this->db->link, md5($data['pass']));

        if ($name=="" || $address=="" || $city=="" || $country=="" || $zip=="" || $phone=="" || $email=="" || $email=="" || $pass==""){
            $msg = "<span class='error'>Fields must not be empty!</span>";
            return $msg;
        }
        $emailquery = "SELECT * FROM customer WHERE email ='$email' LIMIT 1";
        $emailchk = $this->db->select($emailquery);

        if ($emailchk != false){
            $msg = "<span class='error'> Email already exist! </span>";
            return $msg;
        }else{
            $query = "INSERT INTO customer(name, address, city, country, zip, phone, email, pass) " . "VALUES('$name', '$address','$city','$country','$zip', '$phone', '$email', '$pass')";
            $inserted_row = $this->db->insert($query) ;
            if ($inserted_row){
                $msg = "<span class='success'>Customer data created successfully!</span>";
                return $msg;
            } else{
                $msg = "<span class='error'>Customer data not inserted! </span>";
                return $msg;
            }

        }
    }

    public function customerLogin($data)
    {
        $email  = mysqli_real_escape_string($this->db->link, $data['email']);
        $pass   = mysqli_real_escape_string($this->db->link, md5($data['pass']));

        if (empty($email) || empty($pass)){
            $msg = "<span class='error'> Email and password must not be empty! </span>";
            return $msg;
        }

        $query = "SELECT * FROM customer WHERE email ='$email' AND pass ='$pass'";
        $result = $this->db->select($query);

        if ($result != false){
            while($value = $result->fetch_assoc()){
                Session::set("cuslogin", true);
                Session::set("cmrId", $value['id']);
                Session::set("cmrName", $value['name']);
                header("Location:cart.php");
            }
        }else{
            $msg = "<span class='error'> Email or password not match! </span>";
            return $msg;
        }
    }

    public function getCustomerData($id)
    {
        $query = "SELECT * FROM customer  WHERE id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function customerUpdate($data, $cmrId)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $city = mysqli_real_escape_string($this->db->link, $data['city']);
        $country = mysqli_real_escape_string($this->db->link, $data['country']);
        $zip = mysqli_real_escape_string($this->db->link, $data['zip']);
        $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
        $email = mysqli_real_escape_string($this->db->link, $data['email']);

        if ($name == "" || $address == "" || $city == "" || $country == "" || $zip == "" || $phone == "" || $email == "" || $email == "") {
            $msg = "<span class='error'>Fields must not be empty!</span>";
            return $msg;
        } else {

            $query = "UPDATE customer
                  SET 
                    name     = '$name',
                    address  = '$address',
                    city     = '$city',
                    country  = '$country',
                    zip      = '$zip',
                    phone    = '$phone',
                    email    = '$email'
                  WHERE id   = '$cmrId'";

            $updated_row = $this->db->update($query);
            if ($updated_row) {
                $msg = "<span class='success'>Customer data updated successfully!</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Customer data not updated!</span>";
                return $msg;
            }
        }
    }


}