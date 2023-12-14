<?php 
include("USER_MENU.PHP");
if (!isset($_SESSION['login'])) {
    header("Location: LOGIN.PHP");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="">
    <main>
        <section class="flex w-full">
            <div class="bg-black bg-[url('../IMAGES/LIBRARY.jpeg')] h-screen w-1/3 p-5">                
            </div>
            <div class="w-full p-10 space-y-5">
                <h1 class="text-5xl font-bold text-center underline">UPDATE</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="py-5 px-10 rounded-lg grid grid-cols-3 gap-10" enctype="multipart/form-data">
                    <div class="flex flex-col">
                        <label for="name">Username</label>
                        <input type="text" name="name" id="name" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["user_name"]; ?>">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_name ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="email">User Email</label>
                        <input type="text" name="email" id="email" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $_SESSION['login']; ?>" disabled>
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_email ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="phone">User Phone</label>
                        <input type="text" name="phone" id="phone" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["user_phone"]; ?>">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_phone ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["user_gender"];; ?>">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="others">Others</option>
                        </select>
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_gender; ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="dob">DOB</label>
                        <input type="date" name="dob" id="dob" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["user_dob"];?>">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_dob; ?></span>
                    </div>                    
                    <div class="flex flex-col col-span-3">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" cols="30" rows="1" class="outline-none pb-2 border-b border-gray-500 text-gray-500"><?php echo $object_database->row["user_address"];?></textarea>
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_address; ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="card_number">Credit/Debit Card Number</label>
                        <input type="text" name="card_number" id="card_number" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["card_number"]; ?>">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_card_number; ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="name_on_card">Name on Credit/Debit Card</label>
                        <input type="text" name="name_on_card" id="name_on_card" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["name_on_card"]; ?>">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_name_on_card; ?></span>
                    </div>
                    <div class="flex flex-col">
                        <label for="cvv">CVV</label>
                        <input type="password" name="cvv" id="cvv" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500" value="<?php echo $object_database->row["cvv"]; ?>">
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_cvv; ?></span>
                    </div>                    
                    <div class="flex flex-col">
                        <label for="old_password">Old Password <span class="text-red-500">*</span></label>
                        <input type="password" name="old_password" id="old_password" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">  
                        <span class="text-red-500 text-sm"><?php echo $object_validation->extra ?></span>                      
                    </div>                    
                    <div class="flex flex-col">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" class="outline-none border-b border-gray-500 bg-grayy-200 h-10 text-gray-500">                        
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_password; ?></span>

                    </div>
                    <div class="flex flex-col">                        
                        <div class="flex justify_between">
                            <input type="file" name="profile_picture" id="profile_picture" class="outline-none bg-grayy-200 h-10 rounded-md" value="">
                            <span><?php echo $object_database->row["user_image"]; ?></span>
                        </div>
                        <span class="text-red-500 text-sm"><?php echo $object_validation->error_file ?></span>
                    </div>
                    <div class="flex flex-col col-span-3">
                        <button type="submit" name="update" class="outline-none bg-green-400 rounded-lg p-3">Update</button>
                    </div>                    
                </form>
                <div class="flex justify-between items-center w-full">
                    <a href="LOGIN.PHP" class="text-blue-600 underline p-10">Go to login-></a>
                    <form action="" method="post">
                        <button name="delete_user" class="bg-red-800 text-white px-5 py-1 rounded-md">Delete Account</button>
                    </form>
                </div>
            </div>

        </section>
    </main>
</body>

</html>