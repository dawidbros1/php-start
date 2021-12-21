<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Rejestracja</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('auth.register')?>" method="post">
                    <div class="input-group">
                        <span class="input-group-text bg-primary"><i class="bi bi-envelope text-white"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="Adres email"
                            value="<?=$params['email'] ?? ''?>">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:email:sanitize'))?>
                    <?php Error::render('input', Session::getNextClear('error:email:validate'))?>
                    <?php Error::render('input', Session::getNextClear('error:email:unique'))?>

                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i
                                class="bi bi-person-plus-fill text-white"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Nazwa użytkownika"
                            value="<?=$params['username'] ?? ''?>">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:username:between'))?>
                    <?php Error::render('input', Session::getNextClear('error:username:specialCharacters'))?>

                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i class="bi bi-key-fill text-white"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Hasło">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:password:between'))?>

                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i class="bi bi-key-fill text-white"></i></span>
                        <input type="password" name="repeat_password" class="form-control" placeholder="Powtórz hasło">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:password:same'))?>

                    <div class="d-grid col-12 mx-auto mt-3">
                        <button class="btn btn-primary" type="submit"><span></span> Utwórz konto </button>
                    </div>
                    <p class="text-center mt-3">Masz już konto?
                        <a href="<?=$route->get('auth.login')?>" class="link">Zaloguj się</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>