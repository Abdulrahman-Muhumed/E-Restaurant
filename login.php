<?php

session_start();

require_once("./php/database.php");

$database = new mydatabase("users");

$tempEmails = array();
$checkEmail = "SELECT Email FROM users";

$resultck = $database->getData_sql($checkEmail);

while ($row = mysqli_fetch_assoc($resultck)) {
    $tempEmails[] = $row;
}

if (isset($_SESSION['users'])) 
{
    header("location: index.php");
}

// Auto increment reset 

/*
SET  @num := 0;
UPDATE users SET id = @num := (@num+1);
ALTER TABLE users AUTO_INCREMENT =1;
*/

if (isset($_POST['Login'])) {
    $userEmail = $_POST['email'];
    $userpass = $_POST['password'];


    $sql = "SELECT * FROM users WHERE Email = '$userEmail' ";

    $result = $database->getData_sql($sql);

    if (mysqli_num_rows($result) === 0) {
        echo "<script> alert ('Wrong Email or password'); </script>";
    } else {

        while ($row = mysqli_fetch_assoc($result)) {
            $Password = $row['Password'];
            $is_admin = $row['is_admin'];
            $userId = $row['id'];
        }


        if ($userpass === $Password) // password_verify($userpass, $Password)
        {
            $_SESSION['users'] = $userId;

            if ($is_admin === '1') 
            {
                echo "<script>  var pwd = prompt('Please enter your password: (any value but empty)', '');
                        
                    
                        window.location.replace('./admin.php');

                        </script>";
            } else {
                header("location: ./menu.php");
                exit();
            }
        } else {
            echo "<script> alert ('Wrong Email or password'); </script>";
        }
    }
}

if (isset($_POST['signup'])) {

    $Fname = $_POST['FName'];
    $Lname = $_POST['Lname'];
    $Email = $_POST['regEmail'];
    $Phone = $_POST['Phone'];
    $regPassword = $_POST['regPassword'];

    $hPassword = password_hash($regPassword, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(First_Name, is_admin, Last_Name, Email, Password, Phone) VALUES ('$Fname' , 0 , '$Lname' , '$Email' , '$hPassword' , '$Phone')";

    $database->StoreUsers($sql);
}

if (isset($_POST['ResetPass'])) {
    $email_reset = $_POST['Email_reset'];
    $newpass = $_POST['newpassword'];

    echo "<script> alert (' $email_reset'); </script>";
    $pass_hash = password_hash($newpass, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET Password='$pass_hash' WHERE Email='$email_reset'";
    $result = $database->Run_query($sql);

    if ($result) {
        echo "<script> alert ('Password has been changed, Please login'); </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="images/MainLogo.png" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="css/style.css">

</head>

<body id="page-top">


    <!-- header -->
    <header>
        <!--1st top nav-->
        <div class="nav ">
            <div class="container-fluid  " id="Topnavbar">

                <div class="row pt-2 justify-content-center ">
                    <div class="col-md-5 p-0 pb-3 " id="WebLogo">
                        <a href="./index.php">
                            <img src="./images/MainLogo.png" alt="" class="LogoTop">
                        </a>
                        <h4 class="font-weight-bold text-dark LogoHeading pl-2">
                            <span class="text-primary">E</span>-Resturant
                        </h4>

                    </div>
                    <div class="col-md-5  pt-3 ">
                        <div class="row text-right">
                            <div class="col">
                                <form class="example" action="">
                                    <input type="text" placeholder="Search.." name="search" class="mysearch">
                                    <button type="submit" class="searchBut"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="row pt-3 ">

                            <div class="col-md-12 text-right ">

                                <ul class="navbar nav d-inline-flex ml-auto">

                                    <li class="dropdown no-arrow mr-5 pr-2">
                                        <a class="" href="./login.php" role="button" style="text-decoration: none;">
                                            <img class="img-profile rounded-circle" src="images/MainLogo.png" style="position: relative; top: 5px; width: 35px;">
                                            <span class="mr-1 d-none d-lg-inline text-gray-600 small">Welcome, Sign-in

                                            </span>
                                            <div style="position: absolute; top: 19px; left: 45px;">
                                                <span class="font-weight-bold mr-2 d-none d-lg-inline small">MY ACCOUNT</span>
                                            </div>

                                        </a>

                                    </li>

                                    <li class="dropdown no-arrow ">
                                        <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="./images/outline_shopping_bag_black_24dp.png" alt="" style="position: absolute; width: 35px; left: -20px; background-color: rgba(173, 216, 230, 0.726); border-radius: 30px; padding: 5px;">
                                            <!-- Counter - Cart Items -->
                                            <?php
                                            $sql = "SELECT COUNT(item_id) AS NumberOfItems FROM erestaurant.cart";
                                            $result = $database->getData_sql($sql);
                                            if (mysqli_num_rows($result) === 0) {
                                                echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">0</span>";
                                            } else {
                                                $row = mysqli_fetch_assoc($result);
                                                echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">" .
                                                    $row['NumberOfItems'] . "</span>";
                                            }

                                            ?>


                                        </a>
                                        <!-- Dropdown - Cart -->
                                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in my-3" aria-labelledby="cartDropdown" id="TopCart">
                                            <h6 class="dropdown-header bg-primary text-center">
                                                Shopping Cart
                                            </h6>
                                            <?php
                                            $emptyCart = "d-none";
                                            $cartisEmpty = "";
                                            $sql = "SELECT * FROM cart";
                                            $getcartid = $database->getData_sql($sql);

                                            $totalPrice = 0.0;
                                            if (mysqli_num_rows($getcartid) === 0) {
                                                $emptyCart = "";
                                                $cartisEmpty = "d-none";
                                            } else {
                                                while ($row = mysqli_fetch_assoc($getcartid)) {
                                                    $cartId = $row['item_id'];
                                                    $cartqty = $row['quantity'];

                                                    $sqll = "SELECT * FROM items WHERE id = '$cartId' ";
                                                    $getitemid = $database->getData_sql($sqll);

                                                    while ($rows = mysqli_fetch_assoc($getitemid)) {
                                                        $img = $rows['Meal_Img'];
                                                        $name = $rows['name'];
                                                        $type = $rows['category'];
                                                        $price = $rows['price'];

                                                        $totalPrice += $price;
                                                        echo " <a class=\"dropdown-item d-flex align-items-center p-2\" href=\"#\">
                                                                            <div class=\"mr-3 \">
                                                                                <div class=\"circle\">
                                                                                    <img src=\"images/$img\" style=\"width: 100%; max-width: 80px;\" alt=\"\">
                                                                                </div>
                                                                            </div>
                                                                            <div class=\"ml-3\">
                                                                                <span class=\"font-weight-bold\">$name</span>
                                                                                <div class=\"text-gray-900\">
                                                                                    $type
                                                                                </div>
                                                                            </div>
                                                                            <div class=\"text-right w-100\">
                                                                                <span class=\"font-weight-bold\">$ $price</span>
                                                                            </div>
                                                                    </a>";
                                                    }
                                                }
                                            }

                                            echo
                                            "
                                                        <hr>

                                                        <div class=\"text-center $emptyCart\">
                                                            <h6 class=\" text-dark text-center\">Nothing in the cart</h6>
                                                            <a class=\"text-dark  font-weight-bold\" href=\"./menu.php\">
                                                                <button class=\"btn btn-primary\">
                                                                    Start ordering
                                                                </button>
                                                            </a>
                                                        </div>
    
                                                        <div class=\"$cartisEmpty\">
                                                            <div class=\"row text-gray-800 pb-4\">
                                                                <div class=\"col-md-5 text-center\">
                                                                    <span class=\"text-dark\"> Sub total:</span>
                                                                </div>
                                                                <span class=\"col-md-5 text-right text-dark font-weight-bold\">$ $totalPrice</span>
                                                            </div>
                                                            <div class=\"ml-5 mr-5\">
                                                                <a class=\"btn btn-primary w-100 p-1\" href=\"./cart.php\">View Cart</a>
    
                                                                <a class=\"btn btn-primary w-100 my-2 p-1\" href=\"./checkout.php\">CheckOut</a>
                                                            </div>
                                                        </div>
                                                    ";


                                            ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <!-- Navigation-->

        <div class="container-fluid ">

            <div class="row justify-content-center">
                <div class="col-md-11">


                    <nav class="navbar navbar-expand bg-white topbar" id="mainNav">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebutton" class="sidebarLink btn btn-link rounded-circle mr-3" onclick="openNav()">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Nav List -->
                        <div class="navbar" id="navbarLists">
                            <ul class="navbar-nav text-uppercase pl-4" id="MainNavList">
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./index.php" id="registerWord">Home</a></li>
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./about.php" id="registerWord">About</a></li>
                                <!-- <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="#Restaurant" id="registerWord">Restaurant</a></li> -->
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./menu.php" id="registerWord">Menu</a></li>
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./contact.php" id="registerWord">Contact</a></li>
                                </a></li>
                            </ul>
                        </div>

                        <!-- Sidebar Nav List -->
                        <div id="mySidepanel" class="sidepanel">
                            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"></a>
                            <a href="./index.php">Home</a>
                            <a href="./about.php">About</a>
                            <a href="./menu.php">Menu</a>
                            <a href="./contact.php">Contact</a>
                        </div>
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto " id="NavbarRightElement">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-block">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown" id="myinputSearch">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow" id="UserDropDown">
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserProfile">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserPPurchase">
                                        <i class="fas fa-history fa-sm fa-fw mr-2 text-primary"></i>
                                        Past Purchase
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-primary"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>


                            <!-- Nav Item - Cart -->

                            <li class="nav-item dropdown no-arrow mx-1 ">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="./images/outline_shopping_bag_black_24dp.png" alt="" class="CartIcon" style="width: 30px;">
                                    <!-- Counter - Cart Items -->
                                    <?php
                                    $sql = "SELECT COUNT(item_id) AS NumberOfItems FROM erestaurant.cart";
                                    $result = $database->getData_sql($sql);
                                    if (mysqli_num_rows($result) === 0) {
                                        echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">0</span>";
                                    } else {
                                        $row = mysqli_fetch_assoc($result);
                                        echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">" .
                                            $row['NumberOfItems'] . "</span>";
                                    }

                                    ?>


                                </a>
                                <!-- Dropdown - Cart -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in " aria-labelledby="cartDropdown" id="TopCart">
                                    <h6 class="dropdown-header bg-primary text-center p-3">
                                        Shopping Cart
                                    </h6>
                                    <?php
                                    $emptyCart = "d-none";
                                    $cartisEmpty = "";
                                    $sql = "SELECT * FROM cart";
                                    $getcartid = $database->getData_sql($sql);

                                    $totalPrice = 0.0;
                                    if (mysqli_num_rows($getcartid) === 0) {
                                        $emptyCart = "";
                                        $cartisEmpty = "d-none";
                                    } else {
                                        while ($row = mysqli_fetch_assoc($getcartid)) {
                                            $cartId = $row['item_id'];
                                            $cartqty = $row['quantity'];

                                            $sqll = "SELECT * FROM items WHERE id = '$cartId' ";
                                            $getitemid = $database->getData_sql($sqll);

                                            while ($rows = mysqli_fetch_assoc($getitemid)) {
                                                $img = $rows['Meal_Img'];
                                                $name = $rows['name'];
                                                $type = $rows['category'];
                                                $price = $rows['price'];

                                                $totalPrice += $price;
                                                echo " <a class=\"dropdown-item d-flex align-items-center\" href=\"#\">
                                                            <div class=\"mr-3 \">
                                                                <div class=\"circle\">
                                                                    <img src=\"images/$img\" style=\"width: 100%; max-width: 80px;\" alt=\"\">
                                                                </div>
                                                            </div>
                                                            <div class=\"ml-3\">
                                                                <span class=\"font-weight-bold w-100\">$name</span>
                                                                <div class=\"text-gray-900\">
                                                                    $type
                                                                </div>
                                                            </div>
                                                            <div class=\"text-right w-100\">
                                                                <span class=\"font-weight-bold\">$ $price</span>
                                                            </div>
                                                        </a>";
                                            }
                                        }
                                    }

                                    echo
                                    "
                                                        <hr>

                                                        <div class=\"text-center pb-2 $emptyCart\">
                                                            <h6 class=\" text-dark text-center\">Nothing in the cart</h6>
                                                            <a class=\"text-dark  font-weight-bold\" href=\"./menu.php\">
                                                                <button class=\"btn btn-primary\">
                                                                    Start ordering
                                                                </button>
                                                            </a>
                                                        </div>
    
                                                        <div class=\"$cartisEmpty\">
                                                            <div class=\"row text-gray-800 pb-4\">
                                                                <div class=\"col-md-5 text-center\">
                                                                    <span class=\"text-dark\"> Sub total:</span>
                                                                </div>
                                                                <span class=\"col-md-5 text-right text-dark font-weight-bold\">$ $totalPrice</span>
                                                            </div>
                                                            <div class=\"ml-5 mr-5\">
                                                                <a class=\"btn btn-primary w-100 p-1\" href=\"./cart.php\">View Cart</a>
    
                                                                <a class=\"btn btn-primary w-100 my-2 p-1\" href=\"checkout.html\">CheckOut</a>
                                                            </div>
                                                        </div>
                                                    ";


                                    ?>
                                </div>
                            </li>




                        </ul>

                    </nav>
                </div>
            </div>

        </div>
    </header>
                                    

    <!-- Log in & sign up Forms -->
    <section class="page-section pb-5 pt-4">


        <div class="container mx-auto ">

            <div class="contents">


                <div style="background-image: url(./images/blackwall.jpg); background-position: center center; background-size: cover; background-attachment: fixed; width: 100%; position: relative; transition: .7s; transform: translateX(675px);" class="col-md-3 text-center" id="sideForm">
                    <img src="images/MainLogo.png" alt="" width="100px" class="mx-auto pt-4">
                </div>


                <div class="col-md-9  my-4" style="position: relative;">

                    <form action="" method="POST" class="FormCust " style="transform: translateX(-95px); max-width: 360px;" id="LoginForm" novalidate>

                        <h4 class="text-primary font-weight-bold pb-4 text-center">LOGIN</h4>
                        <div class="form-group ">

                            <label class="myicon pl-2 pt-1">
                                <i class="text-muted my-2 fas fa-envelope"></i>
                            </label>
                            <input type="email" name="email" id="email" class="myinput form-control" placeholder="Email Address">
                            <small id="errmsg">

                            </small>

                        </div>
                        <div class="form-group">

                            <label class="myicon pl-2 pt-1">
                                <i class="fas fa-user text-muted my-2 fas fa-lock "></i>
                            </label>
                            <input type="password" name="password" id="password" class="myinput " placeholder="Password">
                            <small id="errmsg">

                            </small>
                        </div>
                        <div class="form-group text-right">
                            <button class="Restpassbtn" onclick="resetPassword()" type="button">
                                <span class="text-dark font-weight-bold  small">Forgot Password?</span>
                            </button>
                        </div>

                        <div class="w-100 text-center">
                            <input type="submit" name="Login" id="" class="mybut2 " value="Login">
                        </div>

                        <br>
                        <br>

                        <div class="form-group text-center small text-dark font-weight-bold pr-2">
                            <span>OR</span>
                            <br>
                            <br>
                            <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-google-plus-g"></i></a>
                        </div>

                        <div class="form-group text-center pr-1 ">
                            <span class="text-dark font-weight-bold small">DONT HAVE AN ACCOUNT?</span>
                            <br><br>
                            <button type="button" style="border: none; background: none; cursor: pointer;" onclick="MyFunction(1)">

                                <span class="small font-weight-bold">CREATE AN ACCOUNT</span>

                            </button>

                        </div>



                    </form>


                    <form action="./login.php" method="POST" id="reset_pass" novalidate>

                        <div id="resetcheck" class="FormCust " style="transform: translateX(-750px); max-width: 360px;">
                            <h4 class="text-primary font-weight-bold pb-2 text-center mr-5">Reset Password</h4>
                            <h6 class="text-dark w-100 pb-3">
                                Enter the <strong>email address</strong> associated with your account.
                            </h6>
                            <div class="form-group">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-envelope"></i>
                                </label>
                                <input type="email" id="email_reset" name="Email_reset" class="myinput" placeholder="Email Address">
                                <small id="errmsg">

                                </small>
                            </div>
                            <div class="w-100">
                                <button class="mybut2" type="button" onclick="checkInput3()">
                                    <span>Verify</span>
                                </button>
                            </div>
                            <div class="w-100 my-3">
                                <button class="mybut2" type="button" onclick="backtoLog()">
                                    <span>BACK</span>
                                </button>

                            </div>
                        </div>


                        <div class="FormCust " id="reset_passPanel" style="transform: translateX(-750px); max-width: 360px;">
                            <h4 class="text-primary font-weight-bold pb-2 text-center mr-5">Reset Password</h4>
                            <!-- <h6 class="text-dark w-100 pb-3">
                                Your New password must be different from previous used passwords.
                            </h6> -->
                            <div class="form-group">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-lock"></i>
                                </label>
                                <input type="password" name="newpassword" id="newPassword" class="myinput" placeholder="Password">
                                <small id="errmsg">

                                </small>
                            </div>
                            <div class="form-group">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-lock"></i>
                                </label>
                                <input type="password" id="NewPasswordconf" class="myinput" placeholder="Confirm Password">
                                <small id="errmsg">

                                </small>
                            </div>
                            <div class="w-100">
                                <input type="submit" value="Reset Password" name="ResetPass" class="mybut2">
                                <a href="./login.php">
                                    <input type="button" value="Cancel" class="mybut2 my-2">
                                </a>
                            </div>
                        </div>

                    </form>

                    <form action="./login.php" method="POST" class="FormCust p-2 " style="left: 20px; max-width: 630px; position: absolute; transform: translateX(740px); " id="SignUpForm" novalidate>
                        <h4 class="text-primary font-weight-bold pb-4 text-center mr-3">CREATE AN ACCOUNT</h4>
                        <div class="row">

                            <div class="col-md-6">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-user"></i>
                                </label>
                                <input type="text" name="FName" id="fname" class="myinput " placeholder="First Name">
                                <small id="errmsg">

                                </small>
                            </div>
                            <div class="col-md-6">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-user"></i>
                                </label>
                                <input type="text" name="Lname" id="lname" class="myinput " placeholder="Last Name">
                                <small id="errmsg">

                                </small>
                            </div>

                        </div>
                        <div class="row pt-4">

                            <div class="col-md-6">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-envelope"></i>
                                </label>
                                <input type="email" name="regEmail" id="regEmail" class="myinput " placeholder="Email Address">
                                <small id="errmsg">
                                    <!-- onkeyup="checkvals(this.value) -->

                                </small>
                                <div id="txtHint"> </div>
                            </div>
                            <div class="col-md-6">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-phone"></i>
                                </label>
                                <input type="text" name="Phone" id="pnumber" class="myinput " placeholder="Phone Number">
                                <small id="errmsg">

                                </small>
                            </div>

                        </div>
                        <div class="row pt-4">

                            <div class="col-md-6">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-lock"></i>
                                </label>
                                <input type="password" name="regPassword" id="regPassword" class="myinput " placeholder="Password">
                                <small id="errmsg">

                                </small>
                            </div>
                            <div class="col-md-6">
                                <label class="myicon pl-2 pt-1">
                                    <i class="text-muted my-2 fas fa-lock"></i>
                                </label>
                                <input type="password" name="CPassword" id="CPassword" class="myinput " placeholder="Confirm Password">
                                <small id="errmsg">

                                </small>
                            </div>

                        </div>

                        <div class="row pt-4  justify-content-center">
                            <div class="col-md-7">
                                <input type="submit" name="signup" id="signUp" class="mybut2 " value="SIGN UP" data-toggle="modal" data-target="#logoutModal">
                            </div>
                        </div>
                        <div class="row pt-1 justify-content-center">
                            <div class="col-md-7">
                                <input type="reset" name="Login" id="reset_signup" class="mybut2 " value="CLEAR" onclick="resetContent()">
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="row p-0 justify-content-center small text-dark font-weight-bold ">
                            <span class="col-md-6" align="center">YOU HAVE ACCOUNT ALREADY?</span>

                        </div>
                        <div class="row pt-1 justify-content-center">
                            <div class="col-md-6" align="center">
                                <button type="button" style="border: none; background: none; cursor: pointer;" onclick="MyFunction(2)">

                                    <span class=" font-weight-bold">Login</span>

                                </button>
                            </div>
                        </div>



                    </form>
                </div>

            </div>




        </div>
    </section>


    <!-- Footer-->
    <footer class="sticky-footer bg-blue">

        <div class="container my-auto">
            <div class="row align-items-center ">
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light">LINKS</span>
                    <div style="display: grid;" class="pt-2">
                        <a href="./index.php" class="footerIcons pt-1">Home</a>
                        <a href="./about.php" class="footerIcons pt-1">About</a>
                        <a href="./contact.php" class="footerIcons pt-1">Contact</a>
                        <a href="./login.php" class="footerIcons pt-1">Login</a>
                    </div>
                </div>
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light">MENU</span>
                    <div style="display: grid;" class="pt-2">
                        <a href="./menu.php" class="footerIcons pt-1">View our menu</a>
                    </div>
                </div>
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light ">CUSTOMER SERVICE</span>
                    <div style="display: grid;" class="pt-2">
                        <a href="./contact.php" class="footerIcons pt-1">Contact US</a>


                    </div>
                </div>
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light ">FOLLOW US ON</span>
                    <div class="pt-2">
                        <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-linkedin-in"></i></a>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr style="background-color: rgba(255, 255, 255, 0.445);">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 text-lg-left pt-3">
                    <span style="color: rgba(255, 255, 255);">
                        Copyright © E-Resturant 2021 All right reserved
                    </span>
                </div>
                <div class="col-lg-4 my-3 my-lg-0 " align="center">
                    <a href="./index.php">
                        <img src="./images/MainLogo.png" alt="" class="LogoFooter">
                    </a>
                </div>
                <div class="col-lg-4 pt-3 text-lg-right">
                    <a class="mr-3" href="#!" style="color: rgba(255, 255, 255);">Privacy Policy</a>
                    <a href="#!" style="color: rgb(255, 255, 255);">Terms of Use</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to top -->
    <a class="box js-scroll-trigger" id="topbutton" href="#page-top">

        <span class="animatespan">
            <i class="fas fa-chevron-up fa-lg"></i>
        </span>
        <span class="animatespan">
            <i class="fas fa-chevron-up fa-lg"></i>
        </span>
        <span class="animatespan">
            <i class="fas fa-chevron-up fa-lg"></i>
        </span>

    </a>
    <!-- Bootstrap core JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Third party plugin JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- Contact form JS-->
    <script src="assets/mail/jqBootstrapValidation.js"></script>
    <script src="assets/mail/contact_me.js"></script>
    <!-- Core theme JS-->
    <script src="js/script.js"></script>

    <!-- Form validation & styles -->
    <script>
        
        var signup = document.getElementById("SignUpForm");
        var login = document.getElementById("LoginForm");
        var sideForm = document.getElementById("sideForm");
        var reset_passPanel = document.getElementById('reset_passPanel');
        var reset = document.getElementById('resetcheck');

        reset.addEventListener('submit', e => {
            e.preventDefault();
        });

        function resetPassword() {
            login.style.transform = "translateX(-700px)";
            reset.style.transform = "translateX(-95px)";
        }

        function backtoLog() {
            login.style.transform = "translateX(-95px)";
            reset.style.transform = "translateX(-755px)";

        }

        function OpenSelectPass() {
            reset.style.transform = "translateX(-755px)";
            reset_passPanel.style.transform = "translateX(-95px)";
        }

        function MyFunction(num) {
            if (num == 1) {
                login.style.transform = "translateX(-700px)";
                signup.style.transform = "translateX(0px)";

                sideForm.style.transform = "translateX(0px)";
            } else {
                sideForm.style.transform = "translateX(675px)";
                signup.style.transform = "translateX(700px)";
                login.style.transform = "translateX(-150px)";

            }

        }


        // Form validation 

        // Login var
        var logForm = document.getElementById('LoginForm');
        var logemail = document.getElementById('email');
        var logpass = document.getElementById('password');
        var logarray = [logemail, logpass];
        // Signup var
        var signForm = document.getElementById('SignUpForm');
        var fname = document.getElementById('fname');
        var lname = document.getElementById('lname');
        var regEmail = document.getElementById('regEmail');
        var regPassword = document.getElementById('regPassword');
        var cpassword = document.getElementById('CPassword');
        var phonenum = document.getElementById('pnumber');

        var regarray = [fname, lname, , regEmail, regPassword, cpassword, phonenum];
        // rest password form
        var reset_pass = document.getElementById('reset_pass');
        var newpass = document.getElementById('newPassword');
        var newpassconfrim = document.getElementById('NewPasswordconf');

        var passarray = [newpass, newpassconfrim];
        // Prevent from submit

        logForm.addEventListener('submit', e => {

            // e.preventDefault();

            checkInputs();

            let result = logarray.every(function(e) {
                if (e.className === 'myinput isvvalid') {
                    return true;
                }

            });

            if (!result) {
                e.preventDefault();
                e.stopPropagation();
            }

        }, false);
        //........................................................
        signForm.addEventListener('submit', e => {

            checkInputs2();

            let result = regarray.every(function(e) {
                if (e.className === 'myinput isvvalid') {
                    return true;

                }

            });


            if (!result) {
                e.preventDefault();
                e.stopPropagation();
            }

        }, false);
        //........................................................
        reset_pass.addEventListener('submit', e => {

            checkInputs4();

            let result = passarray.every(function(e) {
                if (e.className === 'myinput isvvalid') {
                    return true;
                }

            });

            if (!result) {
                e.preventDefault();
                e.stopPropagation();
            }


        }, false);
        //........................................................

        function checkInputs() {
            // trim to remove the whitespaces
            var emailValue = logemail.value.trim();
            var passvalue = logpass.value.trim();

            if (emailValue === '') {
                setErrorFor(logemail, 'Please provied an email');
            } else if (!isEmail(emailValue)) {
                setErrorFor(logemail, 'Please provied a proper email (example@example.com)');
            } else {
                setSuccessFor(logemail);
            }

            if (passvalue === '') {
                setErrorFor(logpass, 'Password are required');
            } else {
                setSuccessFor(logpass);
            }



        }

        function checkInputs2() {
            var fnameValue = fname.value.trim();
            var lnamevalue = lname.value.trim();
            var regemailvalue = regEmail.value.trim();
            var regpassvalue = regPassword.value.trim();
            var regcpassvalue = cpassword.value.trim();
            var phonenumvalue = phonenum.value.trim();

            // First name validation
            if (fnameValue === '') {
                setErrorFor(fname, 'First name are required');
            } else {
                setSuccessFor(fname);
            }

            // Last name validation

            if (lnamevalue === '') {
                setErrorFor(lname, 'Last name are required');
            } else {
                setSuccessFor(lname);
            }

            // Email validation

            if (regemailvalue != '') {
                if (!isEmail(regemailvalue)) {
                    setErrorFor(regEmail, 'Please provide proper Email (example@example.com)');
                } else if (!checkEmaildub(regemailvalue)) {
                    setErrorFor(regEmail, 'Email is already taken');
                } else {
                    setSuccessFor(regEmail);
                }
            } else {
                setErrorFor(regEmail, 'Email are required');
            }

            // Phone number validation
            if (phonenumvalue != '') {
                if (isNumber(phonenumvalue)) {
                    setSuccessFor(phonenum);
                } else {
                    setErrorFor(phonenum, 'Please provide Numbers only')
                }
            } else {
                setErrorFor(phonenum, 'Phone number are required');
            }

            //Password validation
            if (regpassvalue != '') {
                if (regpassvalue.length < 6) {
                    setErrorFor(regPassword, 'Password length must be 6 or more');
                } else {
                    setSuccessFor(regPassword);
                }
            } else {
                setErrorFor(regPassword, 'Password are required');
            }

            if (regcpassvalue != '') {
                if (regcpassvalue.match(regpassvalue)) {
                    setSuccessFor(cpassword);
                } else {
                    setErrorFor(cpassword, 'Please match with the password');
                }
            } else {
                setErrorFor(cpassword, 'Password confirm are required')
            }

            return true;
        }

        // check email password (rest pas)
        function checkInput3() {
            var email_resetVal = email_reset.value.trim();


            if (email_resetVal === '') {
                setErrorFor(email_reset, 'Please provied an email');
            } else if (!isEmail(email_resetVal)) {
                setErrorFor(email_reset, 'Please provied a proper email');
            } else if (checkEmaildub(email_resetVal)) {
                setErrorFor(email_reset, 'No such email found');
            } else {
                setSuccessFor(email_reset);
                OpenSelectPass();
            }




        }

        function checkInputs4() {
            var newpassval = newpass.value.trim();
            var newpassconfrimval = newpassconfrim.value.trim();

            if (newpassval === '') {
                setErrorFor(newpass, 'Field are required');
            } else if (newpassval.length < 6) {
                setErrorFor(newpass, 'Password length must be 6 or more');
            } else {
                setSuccessFor(newpass);
            }

            if (newpassconfrimval === '') {
                setErrorFor(newpassconfrim, 'Field are required');
            } else if (newpassconfrimval != newpassval) {
                setErrorFor(newpassconfrim, 'Please match the password');
            } else {
                setSuccessFor(newpassconfrim);
            }
        }

        function isNumber(input) {
            var numers = /^\d*\.?\d*$/;

            if (input.match(numers)) {
                return true;
            } else {
                return false;
            }
        }

        function isEmail(email) {
            var emailformat = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if (email.match(emailformat)) {
                return true;
            } else {
                return false;
            }
        }

        function checkEmaildub(chemail) {

            var Emails = <?php echo json_encode($tempEmails); ?>;
            var lenth = Emails.length;

            for (var i = 0; i < lenth; i++) {
                if (Emails[i].Email === chemail) {
                    return false;
                }

            }

            return true;

        }

        function setErrorFor(input, message) {

            var formControl = input.parentElement;
            var small = formControl.querySelector('small');

            input.className = 'myinput isnotValid';


            small.innerText = message;
        }

        function setSuccessFor(input) {
            input.className = 'myinput isvvalid';

            var formControl = input.parentElement;
            var small = formControl.querySelector('small');

            small.innerText = "";

        }

        function resetContent() {
            fname.className = 'myinput';
            lname.className = 'myinput';
            regEmail.className = 'myinput';
            regPassword.className = 'myinput';
            cpassword.className = 'myinput';
            phonenum.className = 'myinput';

            var formControl = fname.parentElement;
            var small = formControl.querySelector('small');
            small.innerText = "";

            var formControl = lname.parentElement;
            var small = formControl.querySelector('small');
            small.innerText = "";

            var formControl = regEmail.parentElement;
            var small = formControl.querySelector('small');
            small.innerText = "";

            var formControl = regPassword.parentElement;
            var small = formControl.querySelector('small');
            small.innerText = "";

            var formControl = cpassword.parentElement;
            var small = formControl.querySelector('small');
            small.innerText = "";

            var formControl = phonenum.parentElement;
            var small = formControl.querySelector('small');
            small.innerText = "";



        }
    </script>

    <script>
        window.onload = function() {
            history.replaceState("", "", "./login.php");
        }
    </script>

</body>



</html>