<?php
trait database_trait
{
    public $conn,$all_sku, $columns, $values, $sql, $sql1, $current_user_id, $login_user_password, $row,$row1,$all_books,$all_users,$all_admins,$id,$all_orders,$all_returns,$hashedPassword,$login_user_data;
    public function to_insert($data)
    {
        $this->columns = implode(", ", array_keys($data));
        $this->values = "'" . implode("', '", array_values($data)) . "'";
    }
    public function run_query()
    {
        if ($this->conn->query($this->sql) !== TRUE) {
            echo "Error: " . $this->sql . "<br>" . $this->conn->error;
        }
    }

}
class database{
    use database_trait,user_insertion,user_updation,user_view,user_deletion,books_insertion,book_updation,book_deletion,book_view,admin_insertion,admin_updation,admin_view,cart_insertion,cart_deletion,cart_view,order_insertion,order_deletion,order_view;
    public function __construct($host, $username, $password, $database)
    {
        $this->conn = new mysqli($host, $username, $password, $database);
        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }

    public function books_orders($book_sku)
    {
        $this->sql = "SELECT * FROM book LEFT JOIN orders ON book.book_sku=orders.book_sku LEFT JOIN return_order ON book.book_sku=return_order.book_sku WHERE return_order.book_sku='$book_sku'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
        }
    }

    public function login_user($email)
    {
        $email = $this->conn->real_escape_string($email);
        $result = $this->conn->query("SELECT user_password FROM user_reg WHERE user_email='$email'");        
        $this->login_user_password = $result->fetch_assoc();

        if ($this->login_user_password) {
            $this->hashedPassword= $this->login_user_password['user_password'];
            // echo $this->login_user_password['user_password'];
        }        
        
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}








trait user_insertion{
    public function insert_to_user_reg($tableName, $data)
    {
        $this->to_insert($data);
        $this->sql = "INSERT INTO user_reg($this->columns) VALUES($this->values)";
        $this->run_query();
    }

    public function insert_to_user_file($tableName, $data)
    {
        $this->to_insert($data);
        $this->sql = "INSERT INTO user_file($this->columns) VALUES($this->values)";
        $this->run_query();
    }
}

trait user_updation{
    public function update_to_user_reg($name, $phone, $gender, $dob, $address, $card_number, $name_on_card, $cvv,$user_modified)
    {        
        $this->sql = "UPDATE user_reg SET user_name='$name',user_phone='$phone',user_gender='$gender', user_dob='$dob',user_address='$address', card_number='$card_number', name_on_card='$name_on_card', cvv='$cvv', user_modified='$user_modified' where user_email='$_SESSION[login]'";
        $this->run_query();         
       
        
        
    }

    public function update_password($password)
    {
        $this->sql1 = "UPDATE user_reg SET user_password='$password' where user_email='$_SESSION[login]'";
        if ($this->conn->query($this->sql1) !== TRUE) {
            echo "Error: " . $this->sql1 . "<br>" . $this->conn->error;
        }
    }

    public function update_user_status($status,$user_id)
    {
        $this->sql = "UPDATE user_reg SET user_status='$status' where user_email='$user_id'";
        echo $this->sql;
        $this->run_query();
    }

    public function update_to_user_file($image_name,$user_image_path,$user_image_unique_id){
        $this->sql="UPDATE user_file SET user_image='$image_name', user_image_path='$user_image_path', user_image_uniqe_id='$user_image_unique_id' where user_email='$_SESSION[login]'";
        $this->run_query();
    }
}

trait user_deletion{
    public function delete_user($id,$email){
        $this->sql = "DELETE FROM user_file where user_email='$email'";
        $this->run_query();
        $this->sql = "DELETE FROM user_reg where user_id='$id'";
        $this->run_query();
    }
    public function delete_from_user($email){
        $this->sql = "DELETE FROM user_file where user_email='$email'";
        $this->run_query();
        $this->sql = "DELETE FROM user_reg where user_email='$email'";
        $this->run_query();
    }
}

trait user_view{
   

    // $this->sql = "SELECT user_password FROM user_reg WHERE user_email='$email'";
        // $result = $this->conn->query($this->sql);
        // if ($result->num_rows > 0) {
        //     $this->row = $result->fetch_assoc();
        // var_dump($this->row['user_password']);
        // }
    public function get_user_phone()
    {
        $this->sql = "SELECT user_phone FROM user_reg";
        $result = $this->conn->query($this->sql);
        $all_phone = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_phone[] = $row['user_phone'];
            }
        }
        return $all_phone;
    }

    public function users_data($email)
    {
        $this->sql = "SELECT * FROM user_reg LEFT JOIN user_file ON user_reg.user_email = user_file.user_email WHERE user_reg.user_email='$email'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc(); 
                   
        }
    }

    public function all_users()
    {
        $this->sql = "SELECT * FROM user_reg LEFT JOIN user_file ON user_reg.user_email = user_file.user_email";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_users[] = $row;
                // print_r($this->all_users);
            }
        }
        return $this->all_users;
    }

    public function users_status($id){
        $this->sql = "SELECT * FROM user_reg where user_id='$id'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
        }
    }  
    
    public function check_users_status($email){
        $this->sql = "SELECT user_status FROM user_reg where user_email='$email'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
        }
    }

    public function get_user_emails()
    {
        $this->sql = "SELECT user_email FROM user_reg";
        $result = $this->conn->query($this->sql);
        $all_email = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_email[] = $row['user_email'];
            }
        }
        return $all_email;
    }

    public function count_users(){
        $this->sql="SELECT COUNT(user_email) FROM user_reg";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
            // echo $this->row["COUNT(book_sku)"];
        }
    }
}

trait books_insertion{
    public function insert_to_book($tableName,$data){
        $this->to_insert($data);
        $this->sql = "INSERT INTO book($this->columns) VALUES($this->values)";
        $this->run_query();
    }

    public function insert_to_category($tableName, $data)
    {
        $this->sql = "INSERT INTO book_category(category) VALUES('$data')";
        $this->run_query();
    }

}

trait book_updation{
    public function update_to_book($book_sku,$book_title,$book_author,$book_category,$book_discription,$book_quantity,$book_price,$book_modified)
    {        
        $this->sql = "UPDATE book SET book_title='$book_title',book_author='$book_author', book_category='$book_category',book_discription='$book_discription', book_quantity='$book_quantity', book_price='$book_price' where book_sku='$book_sku'";
        $this->run_query();   
        // var_dump($this->sql);         
    }

    public function update_to_book_image($book_sku,$book_file,$book_path,$book_unique_id){
        $this->sql="UPDATE book SET book_image='$book_file', book_path='$book_path',book_unique_id='$book_file' where book_sku='$book_sku'";
        $this->run_query();
    }

    public function deduct_quantity($book_sku)
    {
        $this->sql = "UPDATE book SET book_quantity= book_quantity-1 where book_sku='$book_sku'";
        $this->run_query();
    }

    public function restoke_quantity($book_sku)
    {
        $this->sql = "UPDATE book SET book_quantity= book_quantity+1 where book_sku='$book_sku'";
        $this->run_query();
    }
}

trait book_deletion{
    public function delete_from_book($sku){
        $this->sql="DELETE FROM book where book_sku='$sku'";
        $this->run_query();
        
    }

    public function delete_book_category($category){
        $this->sql = "UPDATE book SET book_category='NO CATEGORY' where book_category='$category'";
        $this->run_query();
        $this->sql="DELETE FROM book_category where category='$category'";
        $this->run_query();
        
    }
}

trait book_view{
    public function get_all_category()
    {
        $this->sql = "SELECT category FROM book_category";
        $result = $this->conn->query($this->sql);
        $all_category = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_category[] = $row['category'];
            }
        }
        return $all_category;
    }

    public function get_all_sku()
    {
        $this->sql = "SELECT book_sku FROM book";
        $result = $this->conn->query($this->sql);
        $all_sku = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_sku[] = $row['book_sku'];
            }
        }
        return $all_sku;
    }

    

    public function view_book_data($sku)
    {  
        $this->sql = "SELECT * FROM book WHERE book_sku='$sku'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
            // print_r($this->row);
        }
    }

    public function book_data($sku)
    {  
        $this->sql = "SELECT * FROM book WHERE book_sku='$sku'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_sku[] = $row['book_sku'];
            }
        }
        return $this->all_sku;
    }

    public function books_data()
    {
        $this->sql = "SELECT * FROM book";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_books[] = $row;
            }
        }
        return $this->all_books;
    }

    public function books_category_wise($category)
    {
        $this->sql = "SELECT * FROM book where book_category='$category'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_books[] = $row;
            }
        }
        return $this->all_books;
    } 

    public function count_books(){
        $this->sql="SELECT COUNT(book_sku) FROM book";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
            // echo $this->row["COUNT(book_sku)"];
        }
    }



}

trait admin_insertion{
    public function insert_to_admin($tableName,$data){
        $this->to_insert($data);
        $this->sql = "INSERT INTO admin($this->columns) VALUES($this->values)";
        $this->run_query();
    }
}

trait admin_updation{
    public function update_to_admin($admin_id,$admin_phone,$admin_name,$admin_gender,$admin_dob,$admin_address,$admin_modified){
        $this->sql = "UPDATE admin SET admin_phone='$admin_phone',admin_name='$admin_name',admin_gender='$admin_gender', admin_dob= '$admin_dob',admin_address='$admin_address',admin_modified='$admin_modified' where admin_id='$admin_id'";
        $this->run_query(); 
    }

    public function update_admin_file($admin_id,$admin_image){
        $this->sql="UPDATE admin SET admin_image='$admin_image' where admin_id='$admin_id'";
        $this->run_query();
    }

    public function update_to_super_admin($admin_id,$admin_phone,$admin_name,$admin_gender,$admin_dob,$admin_address,$admin_modified){
        $this->sql = "UPDATE super_admin SET s_admin_phone='$admin_phone',s_admin_name='$admin_name',s_admin_gender='$admin_gender', s_admin_dob= '$admin_dob',s_admin_address='$admin_address',s_admin_modified='$admin_modified' where s_admin_id='$admin_id'";
        $this->run_query(); 
    }

    public function update_super_admin_file($admin_id,$admin_image){
        $this->sql="UPDATE super_admin SET s_admin_image='$admin_image' where s_admin_id='$admin_id'";
        $this->run_query();
    }
}

trait admin_view{
    public function login_admin($email)
    {
        $email = $this->conn->real_escape_string($email);
        $result = $this->conn->query("SELECT admin_password FROM admin WHERE admin_email='$email'");
        $this->login_user_password = $result->fetch_assoc();
        if ($this->login_user_password) {
            return $this->hashedPassword=$this->login_user_password['admin_password'];
        } else {
            return null; // Return null if no user is found
        }
    }

    

    public function get_admin_emails()
    {
        $this->sql = "SELECT admin_email FROM admin";
        $result = $this->conn->query($this->sql);
        $all_email = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_email[] = $row['admin_email'];
            }
        }
        return $all_email;
    }
    public function all_admins()
    {
        $this->sql = "SELECT * FROM admin";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_admins[] = $row;
            }
        }
        return $this->all_admins;
    } 
        
    public function admin($id){
        $this->sql = "SELECT * FROM admin where admin_id='$id' or admin_email='$id'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row = $result->fetch_assoc();
        }
    }

    public function get_super_admin_emails()
    {
        $this->sql = "SELECT s_admin_email FROM super_admin";
        $result = $this->conn->query($this->sql);
        $all_email = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_email[] = $row['s_admin_email'];
            }
        }
        return $all_email;
    }

    public function get_super_admin_password($email)
    {
        $this->sql = "SELECT s_admin_password FROM super_admin where s_admin_email='$email'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row1 = $result->fetch_assoc();
        }
    }
    public function super_admin($id){
        $this->sql = "SELECT * FROM super_admin where s_admin_id='$id' or s_admin_email='$id'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row1 = $result->fetch_assoc();
        }
    }

}

trait cart_insertion{
    public function insert_to_cart($tableName, $data)
    {
        $this->to_insert($data);
        $this->sql = "INSERT INTO cart($this->columns) VALUES($this->values)";
        $this->run_query();
    }
}

trait cart_deletion{
    
    public function delete_cart($sku,$email){        
        $this->sql = "DELETE FROM cart where book_sku='$sku' and user_email='$email'";
        $this->run_query();
    }

    public function delete_from_cart($email){        
        $this->sql = "DELETE FROM cart where user_email='$email'";
        $this->run_query();
    }
}

trait cart_view{
    public function user_cart()
    {
        $this->sql = "SELECT book_sku FROM cart where user_email='$_SESSION[login]'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_sku[] = $row;
            }
        }
        return $this->all_sku;
    } 
}

trait order_insertion{
    public function insert_to_orders($tableName,$data){             
        $this->to_insert($data);
        $this->sql = "INSERT INTO orders($this->columns) VALUES($this->values)";
        $this->run_query();
    }

    public function insert_to_return_order($tableName,$data){             
        $this->to_insert($data);
        $this->sql = "INSERT INTO return_order($this->columns) VALUES($this->values)";
        $this->run_query();
    }
}

trait order_deletion{
    public function delete_from_returns($sku,$email){       
        $this->sql = "DELETE FROM return_order where book_sku='$sku' and customer_email='$email'";
        $this->run_query();
    }
}

trait order_view{
    public function orders($id){
        $this->sql = "SELECT * FROM orders where customer_email='$id'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row1 = $result->fetch_assoc();
        }
    } 

    

    public function returns($id){
        $this->sql = "SELECT * FROM return_order where customer_email='$id'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            $this->row1 = $result->fetch_assoc();
        }
    } 

    public function user_returns()
    {
        $this->sql = "SELECT book_sku FROM return_order where customer_email='$_SESSION[login]'";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_sku[] = $row;
            }
        }
        return $this->all_sku;
    } 

    public function all_returns()
    {
        $this->sql = "SELECT * FROM return_order";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_returns[] = $row;
            }
        }
        return $this->all_returns;
    }
    public function all_orders()
    {
        $this->sql = "SELECT * FROM orders";
        $result = $this->conn->query($this->sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->all_orders[] = $row;
            }
        }
        return $this->all_orders;
    }

}






?>