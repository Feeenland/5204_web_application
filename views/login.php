<?php
/**
 * login.php = the login form for the backend of the website.
 * */
?>
<?php

?>

<div class="container login">
    <div class="row justify-content-between">
        <div class="col-12 col-lg-6 login__register">
                <form action="index.php?p=login" method="POST">
                    <h2>New Register</h2>
                    <input type="hidden" name="register" value="1">
                    <div class="form-group row">
                        <label for="name" class="col col-form-label">name:</label>
                        <input
                                type="text"
                                name="name"
                                class="form-control"
                                id="name"
                                placeholder="kate"
                                value="">
                    </div>
                    <input type="hidden" name="login_try" value="1">
                    <div class="form-group row">
                        <label for="card" class="col col-form-label">Most loved magic card:</label>
                        <input
                                type="text"
                                name="card"
                                class="form-control"
                                id="card"
                                placeholder="card name"
                                value="">
                    </div>
                    <input type="hidden" name="login_try" value="1">
                    <div class="form-group row">
                        <label for="newuser" class="col col-form-label">nik name:</label>
                        <input
                                type="text"
                                name="nik"
                                class="form-control"
                                id="newuser"
                                placeholder="kitty =^.^="
                                value="">
                    </div>
                    <div class="form-group row">
                        <label for="newPassword" class="col col-form-label">Passwort:</label>
                        <input
                                type="password"
                                name="password"
                                class="form-control"
                                id="newPassword"
                                placeholder="Password">
                        <p class="error_message"></p>
                    </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn_1 btn btn_send">Senden</button>
                    </div>
                </form>
        </div>
        <div class="col-0 col-md-1 login__space">

        </div>
        <div class="col-12 col-lg-5 login__login">
                <form action="index.php?p=login" method="POST">
                    <h2>Login</h2>
                    <input type="hidden" name="login_try" value="1">
                    <div class="form-group row">
                        <label for="nickname" class="col col-form-label">nick name:</label>
                        <input
                                type="text"
                                name="nickname"
                                class="form-control<?php if(strlen($UserByNick->errorMessage)>0){ ?> has_error <?php } ?>"
                                id="nickname"
                                placeholder="kitty =^.^="
                                value="">
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col col-form-label">Passwort:</label>
                        <input
                                type="password"
                                name="password"
                                class="form-control<?php if(strlen($UserByNick->errorMessage)>0){ ?> has_error <?php } ?>"
                                id="inputPassword"
                                placeholder="Password">
                        <?php if (isset($UserByNick->errorMessage) && (strlen($UserByNick->errorMessage)>0)) { ?>
                            <p class="error_message"><?php print $UserByNick->errorMessage; ?></p>
                        <?php } ?>
                    </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn_1 btn btn_send">Senden</button>
                    </div>
                </form>

            <button class="btn btn_1">forgot password!</button>

        </div>
    </div>
</div>
