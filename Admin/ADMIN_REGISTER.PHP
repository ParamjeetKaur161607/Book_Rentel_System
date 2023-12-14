<?php 
require("ADMIN_PANEL.PHP");
if (isset($_POST['add_admin'])) {
    $object_validation->is_name_valid();
    $object_validation->is_dob_valid();
    $object_validation->is_gender_valid();
    $object_validation->is_email_valid();
    $object_validation->is_phone_valid();
    $object_validation->is_address_valid();    
    $object_validation->is_password_valid(); 
    $errorVariables = [
        $object_validation->error_name,
        $object_validation->error_dob,
        $object_validation->error_gender,
        $object_validation->error_email,
        $object_validation->error_phone,
        $object_validation->error_address,        
        $object_validation->error_password,
    ];
    $date = date("Y-m-d");
    if ($object_validation->is_array_empty($errorVariables)) {
        $encrypted_password = password_hash($object_validation->password, PASSWORD_DEFAULT);
        
        if ($_FILES["profile_picture"]["name"] != "") {
            $object_validation->is_admin_file_valid();
            if (!strlen($object_validation->error_file)) {
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $object_validation->uploaded_file)) {
                    $file="$object_validation->file_name";
                } else {
                    $object_validation->error_file = "file location error";
                }
            }
        } else {
            $file=" ";
        }
        $data_to_insert_in_admin = array(
            "admin_id" => uniqid(),
            "admin_email" => "$object_validation->email",
            "admin_phone" => "$object_validation->phone",
            "admin_name" => "$object_validation->name",
            "admin_gender" => "$object_validation->gender",
            "admin_dob" => "$object_validation->dob",
            "admin_address" => "$object_validation->address",            
            "admin_password" => "$encrypted_password",
            "admin_image" => "$file",
            "admin_created" => "$date",
            "admin_modified" => "$date"
        );
        $object_database->insert_to_admin("admin",$data_to_insert_in_admin);

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="">
    <main>
        <section class="flex pl-80">
            
            <div class="w-full p-20 space-y-5">
                <h1 class="text-5xl font-bold text-center">ADD ADMIN</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="py-5 px-10 rounded-lg grid grid-cols-2 gap-8 w-full" enctype="multipart/form-data">                    
                    <div class="flex flex-col">
                        <label for="name">Admin Name</label>
                        <input type="text" name="name" id="admin_name" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_name ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="email">Admin Email</label>
                        <input type="text" name="email" id="email" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_email ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="phone">Admin Phone</label>
                        <input type="text" name="phone" id="phone" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_phone ?></span>
                    </div>
                    <div class="flex gap-10 justify-between">
                    <div class="flex flex-col flex-1">
                            <label for="gender">Admin Gender</label>
                            <select name="gender" id="gender" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">
                                <option value="none" selected disabled hidden>Select Admin's age</option> 
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="others">Others</option>
                            </select>
                            <span class="text-red-500 text-sm"><?php echo $object_validation->error_gender; ?></span>
                        </div>
                    <div class="flex flex-col flex-1">
                        <label for="dob">Admin's DOB</label>
                        <input type="date" name="dob" id="dob" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_dob; ?></span>
                    </div>
                    </div>
                    
                    <div class="flex flex-col col-span-2">
                        <label for="address">Admin Address</label>
                        <textarea name="address" id="" cols="30" rows="1" class="outline-none pb-4 border-b border-gray-500 text-gray-500"></textarea>
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_address; ?></span>
                    </div>                    
                    <div class="flex flex-col">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_password ?></span>
                    </div>
                    <div class="flex flex-col justify-end">                        
                        <input type="file" name="profile_picture" id="profile_picture" class="outline-none bg-grayy-200 h-10 border-b border-gray-500">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_file ?></span>
                    </div>
                    <div class="flex flex-col col-span-2">
                        <button type="submit" name="add_admin" class="outline-none bg-green-400 rounded-lg p-3">Register</button>
                    </div>
                </form>
                </div>

        </section>
    </main>
</body>

</html>