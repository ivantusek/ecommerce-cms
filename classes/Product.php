<?php
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath.'/../lib/Database.php');
    include_once ($filepath.'/../helpers/Format.php');
?>


<?php

/**
 * Product class
 */
class Product
{

    private $db;
    private $fm;


    public function __construct()
    {

        $this->db = new Database();
        $this->fm = new Format();

    }

    public function productInsert($data, $file)
    {

        $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
        $catId = mysqli_real_escape_string($this->db->link, $data['catId']);
        $brandId = mysqli_real_escape_string($this->db->link, $data['brandId']);
        $body = mysqli_real_escape_string($this->db->link, $data['body']);
        $price = mysqli_real_escape_string($this->db->link, $data['price']);
        $type = mysqli_real_escape_string($this->db->link, $data['type']);

        $permited = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $file['image']['name'];
        $file_size = $file['image']['size'];
        $file_temp = $file['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = "uploads/" . $unique_image;

        if ($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $type == "") {

            $msq = "<span class='error'>Fields  must not be empty!</span>";
            return $msq;

        } elseif ($file_size > 1048567) {
            echo "<span class='error'>Image Sitze should be less then 1MB!</span>";
        } elseif (in_array($file_ext, $permited) === false) {
            echo "<span class='error'>Image Sitze should be less then 1MB!</span>";
        } else {
            move_uploaded_file($file_temp, $uploaded_image);
            $qurey = "INSERT INTO product(productName, catId, brandId, body, price, image, type) VALUE('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type') ";
            $inserted_row = $this->db->insert($qurey);
            if ($inserted_row) {

                $msq = "<span class='success'>Product Inserted Successfully!</span>";
                return $msq;
            } else {
                $msq = "<span class='error'>Product Not Inserted!</span>";
                return $msq;
            }
        }
    }

    public function getAllProduct()
    {

        $qurey = "SELECT p.*, c.catName, b.brandName 
                     FROM product as p, category as c, brand as b
                     WHERE p.catId = c.catId AND p.brandId = b.brandId
                     ORDER BY p.productId DESC";
        /*   $qurey = "SELECT product.*, category.catName, brand.brandName
                     FROM product
                     INNER JOIN category ON product.catId = category.catId
                     INNER JOIN brand ON product.brandId = brand.brandId
                     ORDER BY product.productId DESC"; */


        $result = $this->db->select($qurey);
        return $result;


    }

    public function getProById($id)
    {

        $query = "SELECT * FROM product WHERE productId = '$id'";
        $result = $this->db->select($query);
        return $result;

    }

    public function productUpdate($data, $file, $id)
    {

        $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
        $catId = mysqli_real_escape_string($this->db->link, $data['catId']);
        $brandId = mysqli_real_escape_string($this->db->link, $data['brandId']);
        $body = mysqli_real_escape_string($this->db->link, $data['body']);
        $price = mysqli_real_escape_string($this->db->link, $data['price']);
        $type = mysqli_real_escape_string($this->db->link, $data['type']);

        $permited = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $file['image']['name'];
        $file_size = $file['image']['size'];
        $file_temp = $file['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = "uploads/" . $unique_image;

        if ($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $type == "") {

            $msq = "<span class='error'>Fields  must not be empty!</span>";
            return $msq;
        } else {

            if (!empty($file_name)) {

                if ($file_size > 1048567) {
                    echo "<span class='error'>Image Sitze should be less then 1MB!</span>";
                } elseif (in_array($file_ext, $permited) === false) {
                    echo "<span class='error'>You can Upload only:-" . implode(', ', $permited) . "</span>";
                } else {
                    move_uploaded_file($file_temp, $uploaded_image);
                    //$qurey = "INSERT INTO product(productName, catId, brandId, body, price, image, type) VALUE('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type') ";
                    $query = "UPDATE product 
                              SET 
                              productName   = '$productName',
                              catId         = '$catId',
                              brandId       = '$brandId',
                              body          = '$body',
                              price         = '$price',
                              image         = '$uploaded_image',
                              type          = '$type'
                              WHERE
                              productId = '$id'";

                    $updated_row = $this->db->update($query);
                    if ($updated_row) {

                        $msq = "<span class='success'>Product Updated Successfully!</span>";
                        return $msq;
                    } else {
                        $msq = "<span class='error'>Product Not Updated!</span>";
                        return $msq;
                    }
                }

            } else {
                //$qurey = "INSERT INTO product(productName, catId, brandId, body, price, image, type) VALUE('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type') ";
                $query = "UPDATE product 
                              SET 
                              productName   = '$productName',
                              catId         = '$catId',
                              brandId       = '$brandId',
                              body          = '$body',
                              price         = '$price',
                              type          = '$type'
                              WHERE
                              productId = '$id'";

                $updated_row = $this->db->update($query);
                if ($updated_row) {

                    $msq = "<span class='success'>Product Updated Successfully!</span>";
                    return $msq;
                } else {
                    $msq = "<span class='error'>Product Not Updated!</span>";
                    return $msq;
                }

            }
        }

    }

    public function delProById($id){

        $query = "SELECT * FROM product WHERE productId = '$id'";
        $getData = $this->db->select($query);

        if ($getData) {
            while ($delImg = $getData->fetch_assoc()) {
                $dellink = $delImg['image'];
                unlink($dellink);
            }
        }

        $delqurey = "DELETE FROM product  WHERE productId = '$id' ";
        $deldata = $this->db->delete($delqurey);
        if ($deldata) {
            $msq = "<span class='success'>Product Deleted Successfully!</span>";
            return $msq;
        } else {
            $msq = "<span class='error'>Product Not Deleted!</span>";
            return $msq;
        }
    }


    public function getFetauredProduct(){

        $query = "SELECT * FROM product WHERE type = '0' ORDER BY productId DESC LIMIT 4";
        $result = $this->db->select($query);
        return $result;
    }

    public function getNewProduct(){

        $query = "SELECT * FROM product ORDER BY productId DESC LIMIT 4";
        $result = $this->db->select($query);
        return $result;


    }

    public function getSingleProduct($id){

        $qurey = "SELECT p.*, c.catName, b.brandName 
                     FROM product as p, category as c, brand as b
                     WHERE p.catId = c.catId AND p.brandId = b.brandId  AND productId = '$id'
                     ORDER BY p.productId DESC";

        $result = $this->db->select($qurey);
        return $result;
    }

    public function latestFromIphone()
    {
        $query = "SELECT * FROM product  WHERE brandId = '4' ORDER BY productId DESC LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function latestFromSamsung()
    {
        $query = "SELECT * FROM product  WHERE brandId = '2' ORDER BY productId DESC LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }
    public function latestFromAcer()
    {
        $query = "SELECT * FROM product  WHERE brandId = '1' ORDER BY productId DESC LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }
    public function latestFromCanon()
    {
        $query = "SELECT * FROM product  WHERE brandId = '3' ORDER BY productId DESC LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function getProductByCat($id)
    {
        $catId = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM product WHERE catId='$catId'";
        $result = $this->db->select($query);
        return $result;
    }

    public function insertCompareData($cmprid,$cmrId)
    {
        $cmrId 	    = mysqli_real_escape_string($this->db->link, $cmrId);
        $productId 	= mysqli_real_escape_string($this->db->link, $cmprid);

        $cquery = "SELECT * FROM compare WHERE cmrId = '$cmrId' AND productId = '$productId' ";
        $check = $this->db->select($cquery);
        if ($check) {
            $msg = " <span class='error'>Already Compare!</span> ";
            return $msg;
        }

        $query = "SELECT * FROM product WHERE productId = '$productId' ";
        $result = $this->db->select($query)->fetch_assoc();

        if ($result) {
            $productId = $result['productId'];
            $productName = $result['productName'];
            $price = $result['price'];
            $image = $result['image'];

            $query = " INSERT INTO compare(cmrId, productId, productName, price, image) VALUES('$cmrId','$productId','$productName','$price','$image')";
            $inserted_row = $this->db->insert($query);


            if ($inserted_row) {
                $msg = " <span class='success'>Added to Compare!</span> ";
                return $msg;
            }else{
                $msg = " <span class='error'>Not Added! </span> ";
                return $msg;
            }
        }

    }


    public function getCompareData($cmrId)
    {
        $query = "SELECT * FROM compare WHERE cmrId = '$cmrId' ORDER BY id DESC  ";
        $result = $this->db->select($query);
        return $result;
    }

    public function delCompareData($cmrId)
    {
        $query = "DELETE FROM compare WHERE cmrId = '$cmrId' ";
        $deldata = $this->db->delete($query);
    }

    public function	saveWishListData($id, $cmrId)
    {

        $cquery = "SELECT * FROM wlist WHERE cmrId = '$cmrId' AND productId = '$id' ";
        $check = $this->db->select($cquery);
        if ($check) {
            $msg = " <span class='error'>Already Added!</span> ";
            return $msg;
        }


        $pquery = "SELECT * FROM product WHERE productId = '$id' ";
        $result = $this->db->select($pquery)->fetch_assoc();

        if ($result) {

            $productId = $result['productId'];
            $productName = $result['productName'];
            $price = $result['price'];
            $image = $result['image'];

            $query = " INSERT INTO wlist(cmrId, productId, productName, price, image) VALUES('$cmrId','$productId','$productName','$price','$image')";

            $inserted_row = $this->db->insert($query);

            if ($inserted_row) {
                $msg = " <span class='success'>Added! Check WishList Page!</span> ";
                return $msg;
            }else{
                $msg = " <span class='error'>Not Added! </span> ";
                return $msg;
            }
        }
    }

    public function getWlistData($cmrId)
    {
        $query = "SELECT * FROM wlist WHERE cmrId = '$cmrId' ORDER BY id DESC  ";
        $result = $this->db->select($query);
        return $result;
    }

    public function delWlistData($cmrId, $productId)
    {
        $query = "DELETE FROM wlist WHERE cmrId = '$cmrId' AND productId = '$productId' ";
        $deldata = $this->db->delete($query);
    }







}