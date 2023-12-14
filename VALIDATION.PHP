<?php
session_start();
include_once("DATABASE.PHP");
// trait for validation checking
trait validation_trait
{
    public $name, $email, $address, $phone, $gender, $dob, $security_question, $password, $card_number, $name_on_card, $cvv,$month,$year, $file, $extra, $file_name, $uploaded_file, $date, $category, $duration, $payment, $start_date, $max_duration, $return_date, $sale_price,$daysDifference;

    public $error_name, $error_email, $error_address, $error_phone, $error_gender, $error_dob, $error_security_question, $error_password, $error_card_number, $error_name_on_card, $error_cvv,$error_month,$error_year, $error_file, $error_category, $error_duration, $error_payment;

    public $book_sku, $book_title, $book_author, $book_category, $book_discription, $book_quantity, $book_price, $book_sale_price, $book_image;

    public $error_book_sku, $error_book_title, $error_book_author, $error_book_category, $error_book_discription, $error_book_quantity, $error_book_price, $error_book_sale_price, $error_book_image;

    public $admins;    

    /**
     * Check whether a value is empty or not
     * 
     * @param mixed $data The value to check is whether it is empty or not.
     * @return bool return yes if not empty, false otherwise
     */
    function is_not_empty($data)
    {
        return !empty($data);
    }

    /**
     * Check whether pattren of a value is correct or not
     * 
     * @param mixed $data It validate that only capital letter, small letters and space allowd.
     * @return bool return yes if $data dosen't belongs to the pattren, false otherwise
     */
    function is_name_pattren_valid($data)
    {
        return !preg_match("/^[a-zA-z' ]*$/", $data);
    }

    /**
     * Check whether the email entered by user is in correct format or not
     * 
     * @param mixed $data The oprend to check the format
     * @return bool return yes if $data format is wrong, false otherwise
     */
    function is_email_pattren_valid($data)
    {
        return !filter_var($data, FILTER_VALIDATE_EMAIL);
    }

    function is_phone_length_valid($data)
    {
        return strlen((string) $data) != 10;
    }
    // strlen($this->password) <= 8
    function is_password_length_valid($data)
    {
        return strlen($data) <= 8;
    }
    function is_length_valid($data, $lenth)
    {
        return strlen($data) <= $lenth;
    }

    function is_date_format_valid($data)
    {
        $dateTime = new DateTime($data);
        return $dateTime->format('Y-m-d');
    }

    function is_number($data)
    {
        return !preg_match("#[0-9]+#", $data);
    }
    function is_capital($data)
    {
        return !preg_match("#[A-Z]+#", $data);
    }

    function is_small($data)
    {
        return !preg_match("#[a-z]+#", $data);
    }

    function is_contail_special_char($data)
    {
        return !preg_match("#[^a-zA-Z0-9]+#", $data);
    }
    function is_contain_numbers($data)
    {
        return !preg_match("#[0-9]+#", $data);
    }

    function is_array_empty($array)
    {
        $filteredArray = array_filter($array, function ($value) {
            return !empty($value);
        });

        return empty($filteredArray);
    }

}

class form_validation extends database
{
    use validation_trait;
    public function is_name_valid()
    {
        $this->name = $_POST['name'];
        if (empty(trim($this->name))) {
            $this->error_name= "Please enter valid name!";
        }
        if ($this->is_name_pattren_valid($this->name)) {
            $this->error_name = "Only alphabets and whitespace are allowed!";
        }        
    }
    public function is_dob_valid()
    {
        $this->dob = $_POST['dob'];
        if ($this->is_not_empty($this->dob)) {
            $this->dob = $this->is_date_format_valid($this->dob);
            if($this->dob > date("y-m-d")){
                $this->error_dob = "Please select valid DOB!";
            }
        } else {
            $this->error_dob = "This field can't be empty!";
        }

    }

    public function is_gender_valid()
    {
        if (isset($_POST['gender'])) {
            $this->gender = $_POST['gender'];
        } else {
            $this->error_gender = "Please select your gender!";
        }
    }
    public function is_email_valid()
    {
        $this->email = $_POST['email'];
        if ($this->is_not_empty($this->email)) {
            if ($this->is_email_pattren_valid($this->email)) {
                $this->error_email = "Email must contain (@) and (.)!";
            } else {
                $all_emails = $this->get_user_emails();
                if (in_array($this->email, $all_emails)) {
                    $this->error_email = "This email is already associated with an account.!";
                }
            }
        } else {
            $this->error_email = "Please enter your email address!";
        }
    }

    public function is_phone_valid()
    {
        $this->phone = $_POST['phone'];        
        if(!ctype_digit($this->phone)){
            $this->error_phone="You can enter only numbers";
        }
        
        if ($this->is_not_empty($this->phone)) {
            if ($this->is_phone_length_valid($this->phone)) {
                $this->error_phone = "Phone must contain 10 digits!";
            } else {
                $all_phones = $this->get_user_phone();
                if (in_array($this->phone, $all_phones)) {
                    $this->error_phone = "This phone number is already associated with an account.!";
                }
            }
        } else {
            $this->error_phone = "Please enter your phone number!";
        }
    }

    public function is_address_valid()
    {
        $this->address = $_POST['address'];
        if (empty(trim($this->address))) {
            $this->error_address= "Please enter valid address!";
        }        
    }

    public function is_security_question()
    {
        $this->security_question = $_POST['user_security'];
        if (empty(trim($this->security_question))) {
            $this->error_security_question= "Please answer this question for your future security!";
        }
    }
    public function is_password_valid()
    {
        $this->password = $_POST['password'];
        if ($this->is_not_empty($this->password)) {
            if ($this->is_password_length_valid($this->password)) {
                $this->error_password = "Password must contain atleast 8 characters!";
            } elseif ($this->is_number($this->password)) {
                $this->error_password = "Password must contain atleast one numeric value!";
            } elseif ($this->is_capital($this->password)) {
                $this->error_password = "Password must contain atleast one capital letter!";
            } elseif ($this->is_small($this->password)) {
                $this->error_password = "Password must contain atleast one small letter!";
            }
        } else {
            $this->error_password = "Please enter your password!";
        }
    }

    public function is_new_password_valid()
    {      
        $this->password = $_POST['password'];
        if ($this->is_password_length_valid($this->password)) {
            $this->error_password = "Password must contain atleast 8 characters!";
        } elseif ($this->is_number($this->password)) {
            $this->error_password = "Password must contain atleast one numeric value!";
        } elseif ($this->is_capital($this->password)) {
            $this->error_password = "Password must contain atleast one capital letter!";
        } elseif ($this->is_small($this->password)) {
            $this->error_password = "Password must contain atleast one small letter!";
        } 
            
    }

    public function is_card_number_valid()
    {
        $this->card_number = $_POST['card_number'];
        if (empty(trim($this->card_number))) {
            $this->error_card_number= "Please enter valid card number!";
        }
        if ($this->is_not_empty($this->card_number)) {
            if (strlen((string) $this->card_number) < 16 or strlen((string) $this->card_number) > 16) {
                $this->error_card_number = "Please enter valid length for credit/debit card number";
            }
        } else {
            $this->error_card_number = "Please enter your debit/credit card number!";
        }
    }
    public function is_name_on_card_valid()
    {
        $this->name_on_card = $_POST['name_on_card'];
        if (empty(trim($this->name_on_card))) {
            $this->error_name_on_card= "Please enter valid card number!";
        }        
    }
    public function is_cvv_valid()
    {
        $this->cvv = $_POST['cvv'];
        if (empty(trim($this->cvv))) {
            $this->error_cvv= "Please enter name on credit/debit card!";
        }

        if (strlen((string) $this->cvv) > 3 or strlen((string) $this->cvv) < 3) {
            $this->error_cvv = "Please enter 3 digits of cvv!";
        }        
    }

    public function is_expiry_date_valid()
    {
        $this->month = $_POST['month'];
        $this->year = $_POST['year'];
        if ($this->is_not_empty($this->year)) {            
            if($this->year % 100 < date("y")){
                $this->error_year = "Please select valid year!";
            }
        } else {
            $this->error_year = "This field can't be empty!";
        }

        if (empty($this->month)) {            
            $this->error_month = "This field can't be empty!";
        }

    }

    public function is_file_valid()
    {
        $this->file = $_FILES['profile_picture'];
        if ($_FILES["profile_picture"]["name"] != "") {
            $this->file_name = $_FILES["profile_picture"]["name"];
            $allowed_extensions = array("jpg", "jpeg", "png");
            $file_extension = pathinfo($this->file["name"], PATHINFO_EXTENSION);
            if (in_array($file_extension, $allowed_extensions)) {
                $this->uploaded_file = "USER_IMAGES/" . $this->file_name;
                $this->extra = uniqid();
            } else {
                $this->error_file = "Invalid file type. Allowed file types: " . implode(", ", $allowed_extensions);
            }
        }
    }

    public function login(){
        $this->email = $_POST['user_email_login'];
        $this->password = $_POST['user_password_login'];
        $all_emails = $this->get_user_emails();
        if (in_array($this->email, $all_emails)) {
            $this->login_user($this->email);
            if (password_verify($this->password, $this->hashedPassword)) {
                $this->check_users_status($this->email);               
                if($this->row['user_status']=='ACTIVE' or $this->row['user_status']=='active'){
                    $_SESSION['login'] = $_POST['user_email_login'];
                    header("location:INDEX.PHP");
                }else{
                    $this->error_email='Please register yourself first!';
                }
            }else{
                $this->error_password = 'Password is Incorrect!';
            }
        } else{
            $this->error_password = 'Please register yourself first!';
        }
    }
    public function category()
    {
        $this->category = $_POST['category'];
        if ($this->is_not_empty($this->category)) {
            $category = $this->get_all_category();
            if (in_array($this->category, $category)) {
                $this->error_category = "This category is already existed, Please try again!";
            }
        } else {
            $this->error_category = "Category can't be empty!";
        }
    }

    public function is_book_sku_valid()
    {
        $this->book_sku = $_POST['book_sku'];
        if ($this->is_not_empty($this->book_sku)) {
            if (!$this->is_length_valid($this->book_sku, 20)) {
                $this->error_book_sku = "The maximum length of SKU can be 20 only!";
            } else {
                if (in_array($this->book_sku, $this->get_all_sku())) {
                    $this->error_book_sku = "This SKU is already existed in the database!";
                }
            }
        } else {
            $this->error_book_sku = "Please enter SKU for this listing!";
        }
    }

    public function is_book_title_valid()
    {
        $this->book_title = $_POST['book_title'];
        if ($this->is_not_empty($this->book_title)) {
            if (!$this->is_length_valid($this->book_title, 50)) {
                $this->error_book_title = "The maximum length of SKU can be 50 only!";
            } else {
                if ($this->is_name_pattren_valid($this->book_title)) {
                    $this->error_book_title = "Only alphabets and whitespace are allowed!";
                }
            }
        } else {
            $this->error_book_title = "Please enter title of the listing!";
        }
    }
    public function is_book_author_valid()
    {
        $this->book_author = $_POST['book_author'];
        if ($this->is_not_empty($this->book_author)) {
            if (!$this->is_length_valid($this->book_author, 50)) {
                $this->error_book_author = "The maximum length of Author Name can be 50 only!";
            } else {
                if ($this->is_name_pattren_valid($this->book_title)) {
                    $this->error_book_author = "Only alphabets and whitespace are allowed!";
                }
            }
        } else {
            $this->error_book_author = "Please enter name of the author!";
        }
    }
    public function is_book_category_valid()
    {
        if (isset($_POST['book_category'])) {
            $this->book_category = $_POST['book_category'];
        } else {
            $this->error_book_category = "Please select the category for this book!";
        }
    }
    public function is_book_discription_valid()
    {
        $this->book_discription = $_POST['book_discription'];
        if (!$this->is_not_empty($this->book_discription)) {
            $this->error_book_discription = "Please enter little bit description about book!";
        }
    }
    public function is_book_quantity_valid()
    {
        $this->book_quantity = $_POST['book_quantity'];
        if (!$this->is_not_empty($this->book_quantity)) {
            $this->error_book_quantity = "Please enter quantity of the book!";
        }
    }

    public function is_book_price_valid()
    {
        $this->book_price = $_POST['book_price'];
        if (!$this->is_not_empty($this->book_price)) {
            $this->error_book_price = "Please enter price of the book!";
        }
    }

    public function is_book_image_valid()
    {
        $this->book_image = $_FILES['book_file'];
        if ($this->is_not_empty($this->book_image)) {
            $this->file_name = $_FILES["book_file"]["name"];
            $allowed_extensions = array("jpg", "jpeg", "png");
            $file_extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
            echo $file_extension;
            echo $_FILES["book_file"]["tmp_name"];
            if (in_array($file_extension, $allowed_extensions)) {
                $this->uploaded_file = "BOOKS_IMAGES/" . $this->file_name;
                $this->extra = uniqid();
            } else {
                $this->error_book_image = "Invalid file type. Allowed file types: " . implode(", ", $allowed_extensions);
            }
        } else {
            $this->error_book_image = "Please upload book cover image!";

        }
    }
    public function is_admin_file_valid()
    {
        $this->file = $_FILES['profile_picture'];
        if ($this->is_not_empty($this->file)) {
            $this->file_name = $_FILES["profile_picture"]["name"];
            $allowed_extensions = array("jpg", "jpeg", "png");
            $file_extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
            if (in_array($file_extension, $allowed_extensions)) {
                $this->uploaded_file = "ADMINS_PROFILE_PICS/" . $this->file_name;
            } else {
                $this->error_file = "Invalid file type. Allowed file types: " . implode(", ", $allowed_extensions);
            }
        }
    }

    public function admin_login(){
        $this->email = $_POST['admin_email_login'];
        $this->password = $_POST['admin_password_login'];
        $this->all_admins();
        $all_emails = $this->get_admin_emails();
        $super_admin_email = $this->get_super_admin_emails();
        $this->login_admin($this->email);
        if (in_array($this->email, $all_emails) && password_verify($this->password, $this->hashedPassword)) {
                $_SESSION['admin_login'] = $_POST['admin_email_login'];
                header("location:ADMIN_DASHBOARD.PHP");
        }else{
            $this->error_password = 'Wrong Password or email!';            
        }

        $this->get_super_admin_password($this->email);
        if (in_array($this->email, $super_admin_email) && ($this->password == $this->row1['s_admin_password'])) {
            $_SESSION['admin_login'] = $_POST['admin_email_login'];
            header("location:ADMIN_DASHBOARD.PHP");
        }else{
            $this->error_password = 'Wrong Password or email!';            
        }

    }

    // public function admin_login()
    // {
    //     $this->email = $_POST['admin_email_login'];
    //     $this->password = $_POST['admin_password_login'];
    //     // echo $this->password;
    //     $this->all_admins();
    //     $all_emails = $this->get_admin_emails();
    //     $super_admin_email = $this->get_super_admin_emails();
    //     if ($this->is_not_empty($this->email)) {
    //         if (in_array($this->email, $all_emails) or in_array($this->email, $super_admin_email)) {
    //             if ($this->is_not_empty($this->password)) {
    //                 $this->login_admin($this->email);
    //                 echo $this->hashedPassword;
    //                 $admin_verify = password_verify($this->password, $this->hashedPassword);
    //                 // if ($admin_verify or $this->get_super_admin_password($this->email)) {
    //                 //     $_SESSION['admin_login'] = $_POST['admin_email_login'];
    //                 //     header("location:ADMIN_DASHBOARD.PHP");
    //                 // } else {
    //                 //     $this->error_password = 'Password is Incorrect!';
    //                 // }
    //             } else {
    //                 $this->error_password = "Please eneter password!";
    //             }
    //         } else {
    //             $this->error_email = "Incorrect email/password!";
    //         }
    //     } else {
    //         $this->error_email = "Please enter email address";
    //     }
    // }

    public function is_duration_valid()
    {
        $this->duration = $_POST['duration'];
        // echo $this->duration; 
        if ($this->is_not_empty($this->duration)) {
            $this->start_date = date('y-m-d');
            $this->max_duration = (new DateTime())->add(new DateInterval('P2M'));            
            $date1 = new DateTime($this->start_date);
            $date2 = new DateTime($this->duration);
            $interval = $date1->diff($date2);
            $this->daysDifference = $interval->days;
            if($this->daysDifference>70){
                $this->error_duration="You can not buy this book more then 2 months on rent!";
            }            
            if($date2 < $date1){
                $this->error_duration = "Please select valid date!";
            }  
        }else{
            $this->error_duration="This feild can't be empty!";
        }
    }
    public function fine(){

    }
    public function is_payment_valid()
    {
        if (!isset($_POST['payment'])) {
            $this->error_payment = "Please check the payment option.";
        }
    }

}

$object_validation = new form_validation("localhost", "param", "161607", "BookRentelSystem");
$object_database = new database("localhost", "param", "161607", "BookRentelSystem");

// var_dump($object_database->login_user('sajal@gmail.com'));
// var_dump($object_database->books_data());
// var_dump($object_database->get_user_phone());
// var_dump($object_database->users_data($_SESSION['login']));
// var_dump($object_database->get_all_category());
// var_dump($_SESSION);
// session_destroy();
if (isset($_SESSION['login'])) {
    $object_database->users_data($_SESSION['login']);
}
if (isset($_POST['register'])) {
    $object_validation->is_name_valid();
    $object_validation->is_dob_valid();
    $object_validation->is_gender_valid();
    $object_validation->is_email_valid();
    $object_validation->is_phone_valid();
    $object_validation->is_address_valid();
    $object_validation->is_security_question();
    $object_validation->is_password_valid();
    $object_validation->is_card_number_valid();
    $object_validation->is_name_on_card_valid();
    $object_validation->is_cvv_valid();
    $object_validation->is_expiry_date_valid();
    $errorVariables = [
        $object_validation->error_name,
        $object_validation->error_dob,
        $object_validation->error_gender,
        $object_validation->error_email,
        $object_validation->error_phone,
        $object_validation->error_address,
        $object_validation->error_card_number,
        $object_validation->error_name_on_card,
        $object_validation->error_cvv,
        $object_validation->error_security_question,
        $object_validation->error_password,
    ];
    $date = date("Y-m-d");
    if ($object_validation->is_array_empty($errorVariables)) {
        $encrypted_password = password_hash($object_validation->password, PASSWORD_DEFAULT);
        $data_to_insert_in_user_reg = array(
            "user_id" => uniqid(),
            "user_email" => "$object_validation->email",
            "user_phone" => "$object_validation->phone",
            "user_name" => "$object_validation->name",
            "user_gender" => "$object_validation->gender",
            "user_dob" => "$object_validation->dob",
            "user_address" => "$object_validation->address",
            "card_number" => "$object_validation->card_number",
            "name_on_card" => "$object_validation->name_on_card",
            "cvv" => "$object_validation->cvv",
            "user_security_question" => "$object_validation->security_question",
            "user_password" => "$encrypted_password",
            "user_status" => "active",
            "user_created" => "$date",
            "user_modified" => "$date"
        );
        if ($_FILES["profile_picture"]["name"] != "") {
            $object_validation->is_file_valid();
            if (!strlen($object_validation->error_file)) {
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $object_validation->uploaded_file)) {
                    $data_to_insert_in_user_file = array(
                        "user_email" => "$object_validation->email",
                        "user_image" => "$object_validation->file_name",
                        "user_image_path" => "$object_validation->uploaded_file",
                        "user_image_uniqe_id" => "$object_validation->extra"
                    );
                    $object_database->insert_to_user_reg("user_reg", $data_to_insert_in_user_reg);
                    $object_database->insert_to_user_file("user_fle", $data_to_insert_in_user_file);
                    header('Location: LOGIN.PHP');
                } else {
                    $object_validation->error_file = "file location error";
                }
            }
        } else {
            $object_database->insert_to_user_reg("user_reg", $data_to_insert_in_user_reg);
            $data_to_insert_in_user_file = array(
                "user_email" => "$object_validation->email"                
            );
            $object_database->insert_to_user_file("user_fle", $data_to_insert_in_user_file);
            header('Location: LOGIN.PHP');
        }
    }
}

if (isset($_POST['publish_book'])) {
    $object_validation->is_book_sku_valid();
    $object_validation->is_book_title_valid();
    $object_validation->is_book_author_valid();
    $object_validation->is_book_category_valid();
    $object_validation->is_book_discription_valid();
    $object_validation->is_book_quantity_valid();
    $object_validation->is_book_price_valid();
    $object_validation->is_book_image_valid();

    $errorVariables = [
        $object_validation->error_book_sku,
        $object_validation->error_book_title,
        $object_validation->error_book_author,
        $object_validation->error_book_category,
        $object_validation->error_book_discription,
        $object_validation->error_book_quantity,
        $object_validation->error_book_price,
        $object_validation->error_book_image,
    ];
    $date = date("Y-m-d");
    if ($object_validation->is_array_empty($errorVariables)) {
        $data_to_insert_in_book = array(
            "book_sku" => "$object_validation->book_sku",
            "book_title" => "$object_validation->book_title",
            "book_author" => "$object_validation->book_author",
            "book_category" => "$object_validation->book_category",
            "book_discription" => "$object_validation->book_discription",
            "book_quantity" => "$object_validation->book_quantity",
            "book_price" => "$object_validation->book_price",
            "book_sale_price" => round($object_validation->book_price * 5 / 100, 0),
            "book_uploaded" => "$date",
            "book_modified" => "$date",
            "book_image" => "$object_validation->file_name",
            "book_path" => "$object_validation->uploaded_file",
            "book_unique_id" => "$object_validation->extra"
        );

        if (move_uploaded_file($_FILES["book_file"]["tmp_name"], $object_validation->uploaded_file)) {
            $object_database->insert_to_book("book", $data_to_insert_in_book);
            header('Location: ALL_BOOKS.PHP');
        } else {
            $object_validation->error_book_image = "file location error";
        }
    }
}



if (isset($_POST['add_category'])) {
    $object_validation->category();
    if (empty($object_validation->error_category)) {
        $object_database->insert_to_category('book_category', $object_validation->category);
        header('location: ALL_BOOKS.PHP');
    }
}

if (isset($_POST['delete_category'])) {
    $category = $_POST['book_category'];
    $object_database->delete_book_category($category);
    header('location: ALL_BOOKS.PHP');
}

if (isset($_POST['login'])) {
    $object_validation->login();
}

if (isset($_POST['update'])) {
    $object_validation->is_name_valid();
    $object_validation->is_dob_valid();
    $object_validation->is_gender_valid();
    // $object_validation->is_phone_valid();
    $object_validation->is_address_valid();
    $object_validation->is_card_number_valid();
    $object_validation->is_name_on_card_valid();
    $object_validation->is_cvv_valid();   
    $object_validation->is_file_valid();
    if(!empty($_POST['password'])){
        $object_validation->is_new_password_valid();
    }    
    $errorVariables = [
        $object_validation->error_name,
        $object_validation->error_dob,
        $object_validation->error_gender,
        $object_validation->error_email,
        $object_validation->error_phone,
        $object_validation->error_address,
        $object_validation->error_card_number,
        $object_validation->error_name_on_card,
        $object_validation->error_cvv,
        $object_validation->error_security_question,
        $object_validation->error_password,        
    ];
    $date = date("Y-m-d");
    if ($object_validation->is_array_empty($errorVariables)) {
        $object_database->login_user($_SESSION['login']);
        $verify = password_verify($_POST['old_password'], $object_database->hashedPassword);
        if ($verify) {                   
            if ($_FILES["profile_picture"]["name"] != "") {
                $object_validation->is_file_valid();
                if (!strlen($object_validation->error_file)) {
                    if (move_uploaded_file($object_validation->file["tmp_name"], $object_validation->uploaded_file)) {

                        if(!empty($_POST['password'])){
                            // $object_validation->is_new_password_valid();   
                            $encrypted_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                            if($object_database->update_password($encrypted_password)){
                                header('Location: LOGIN.PHP');
                            }
                        }

                        $object_database->update_to_user_reg($_POST['name'], $_POST['phone'], $_POST['gender'], $_POST['dob'], $_POST['address'], $_POST['card_number'], $_POST['name_on_card'], $_POST['cvv'], $date);
                                              
                        $object_database->update_to_user_file($_FILES["profile_picture"]["name"],$object_validation->uploaded_file,$object_validation->extra);
                        unset($_SESSION['login']);
                        header('Location: LOGIN.PHP');
                    } else {
                        $object_validation->error_file = "file location error";
                    }
                }
            } else {   
                if(!empty($_POST['password'])){
                    // $object_validation->is_new_password_valid();   
                    $encrypted_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    if($object_database->update_password($encrypted_password)){
                        header('Location: LOGIN.PHP');
                    }
                } 
                $object_database->update_to_user_reg($_POST['name'], $_POST['phone'], $_POST['gender'], $_POST['dob'], $_POST['address'], $_POST['card_number'], $_POST['name_on_card'], $_POST['cvv'], $date);
                unset($_SESSION['login']);                         
               
                header('Location: LOGIN.PHP');
                
            }

        } else {
            $object_validation->extra = 'Password is Incorrect!';
        }

    }
}

if(isset($_POST['delete_user'])){
   $object_database-> delete_from_user($_SESSION['login']);
   unset($_SESSION['login']);
   header('location:LOGIN.PHP');
}
if (isset($_POST['admin_login'])) {
    $object_validation->admin_login();
}
if (isset($_POST['log_out_admin'])) {
    // session_destroy();
    unset($_SESSION['admin_login']);
}
if (isset($_POST['logut_user'])) {
    unset($_SESSION['login']);
}

if (isset($_POST['checkout'])) {

    $object_validation->is_duration_valid();
    $object_validation->is_payment_valid();
    $errorVariables = [
        $object_validation->error_duration,
        $object_validation->error_payment,];
    if ($object_validation->is_array_empty($errorVariables)) {
        $object_database->user_cart();
        foreach ($object_database->all_sku as $key => $value) {
            $data_to_insert_in_orders = array(
                "customer_email" => "$_SESSION[login]",
                "customer_name" => "$_POST[name]",
                "customer_phone" => "$_POST[phone]",
                "book_sku" => "$value[book_sku]",
                "customer_address" => "$_POST[address]",
                "order_date" => "$object_validation->start_date",
                "return_date" => "$object_validation->duration",
                "payment" => "$_POST[payment]",
                "note" => "$_POST[note]",
                "order_id" => uniqid()  
            );
            $data_to_insert_in_returns = array(
                "customer_email" => "$_SESSION[login]",                
                "book_sku" => "$value[book_sku]",                
                "order_date" => "$object_validation->start_date",
                "return_date" => "$object_validation->duration"                
            );
            $object_database->insert_to_orders("orders", $data_to_insert_in_orders);
            $object_database->insert_to_return_order("return_order", $data_to_insert_in_returns);
            $object_database->deduct_quantity($value['book_sku']);
            header('location:ORDER_SUMMARY.PHP');
        }
        // 
    }
    
}

if(isset($_POST['place-order'])){
    $object_database->delete_from_cart($_SESSION['login']);
    header('location:PAYMENT.PHP');
}


?>