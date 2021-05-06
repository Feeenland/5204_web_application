<?php
/**
 * I need this file only to save the pw in the database as a hash. (for the admin and before i could add users in the backend)
 * I used this function because it only compares and it is not possible to derive the password from the hash
 */


// echo password_verify($pwd, $hash) ? 'Success' : 'Failed';

// user Tatze
$hash = password_hash("123456", PASSWORD_DEFAULT);
$pwd = '123456';

print "check password " . $hash;

$hash = '$2y$10$Bmp1bnVlURxW6urgqbb7duE0cSJ5BSNNYfCtlnQ5vHcjdALl7V7nW';


if (password_verify($pwd, $hash)) {  //password_verify = verifies that a password matches a hash
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
