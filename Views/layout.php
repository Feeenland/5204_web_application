<?php
/**
 * layout.php = This file connects the various contents and integrates the header and JS data.
 */

if ($p == 'login'){
    //$UserCheck = new CLogin();
    //$UserCheck->userLogin();
    //print_r($UserCheck);

    $user = new UserModel();
    $user->getUsersByNickname('test');
    print_r($user);


    $values2 = [
        'id' => 4,
        'name' =>"test2",
        'nickname' =>"test3",
        'favourite_card' =>"island",
        'password' =>"test"//,
        //'banned_at' =>"2021-01-13 11:06:37",
        //'login_try' => 1
    ];

    $user2 = new UserModel();
    $user2->updateSave($values2);
    print_r($user2);

 /*   $user3 = new UserModel();
    $user3->delete(5);      // delete works
    print_r($user3);*/
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Magic Decks -<?php if (isset($pageTitle)){print $pageTitle;} else {print 'Magic';}  ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lakki+Reddy&display=swap" rel="stylesheet">
</head>
<body>

<?php include('navigation.php') ?>
<div class="container">
    <div class="row">
        <div class="col-10 offset-1">
            <span class="login_output"> <!--// prints infos from the login (like failed etc.)-->
                <?php if (isset($user->login_output)){print $user->login_output;} ?>
            </span>
        </div>
    </div>
</div>

<?php

if(isset($page)){ //is the var $page defined
    include($page); // include $page
}else{
    if (isset($content)){ //is the var $content defined //content is not defined jet!
        print $content; // print content
    }else{
        //print 'nothing found'; // else load default file
        include('default.php');
    }
}
?>


<?php include('footer.php') ?>




<!-- js -->
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- My js -->
<script src="js/app.js"></script>

</body>
</html>