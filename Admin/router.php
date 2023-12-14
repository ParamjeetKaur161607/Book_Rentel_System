<?php

$request=$_SERVER['REQUEST_URI'];
$routes=str_replace('BOOK-RENTEL-SYSTEM/Admin','',$request);
echo $routes;


// $routes=[
//     '$routes==/'=>'ADMIN_DASHBOARD.PHP',
//     '/ADD_BOOK'=>'ADD_BOOK.PHP',
//     '/ADMIN_LOGIN'=>'ADMIN_LOGIN.PHP',
//     '/ADMIN_REGISTER'=>'ADMIN_REGISTER.PHP',
//     '/ADMIN_UPDATE'=>'ADMIN_UPDATE.PHP',
//     '/ADMINS'=>'ADMINS.PHP',
//     '/ALL_BOOKS'=>'ALL_BOOK.PHP',
//     '/BOOK_CATEGORY'=>'ADMINS',
//     '/CATEGORY'=>'CATEGORY.PHP',
//     '/DELETE_BOOK'=>'DELETE_BOOK.PHP',
//     '/DELETE_CATEGORY'=>'DELETE_CATEGORY.PHP',
//     '/INACTIVE_DELETE_USER'=>'INACTIVE_DELETE_USER.PHP',
//     '/ORDER'=>'ORDER.PHP',
//     '/RENTED_BOOK'=>'RENTED_BOOK.PHP',
//     '/UPDATE_BOOK'=>'UPDATE_BOOK.PHP',
//     '/USER'=>'USER.PHP',
//     '/router'=>'router.PHP',
// ];

// function routeToController($uri,$routes){
//     if(array_key_exists($uri,$routes)){

//         require $routes[$uri];
//     }else{
//         // print_r($uri);
//         // print_r($routes);
//         echo "page not found";
//         die();
//     }
// }

// routeToController($uri,$routes);

?>