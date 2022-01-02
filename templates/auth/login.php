<?php

declare (strict_types = 1);

use App\Component;
use App\Helper\Session;

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
         <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Logowanie</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('auth.login')?>" method="post">
                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i class="bi bi-envelope text-white"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Adres email"
                            value="<?=$params['email'] ?? ""?>">
                    </div>

                    <?php Component::render('error', ['text' => Session::getNextClear('error:email:null')])?>

                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i class="bi bi-key-fill text-white"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Hasło">
                    </div>

                    <?php Component::render('error', ['text' => Session::getNextClear('error:password:incorrect')])?>

                    <div class="d-grid col-12 mx-auto mt-3">
                        <button class="btn btn-primary" type="submit"><span></span> Zaloguj się </button>
                    </div>

                    <div class="text-center mt-3 w-100">
                        <div> Nie masz jeszcze konta?
                            <a href="<?=$route->get('auth.register')?>" class="link">Zarejestruj się</a>
                        </div>
                        <div> Zapomniałeś hasła?
                            <a href="<?=$route->get('auth.forgotPassword')?>" class="link">Przypomnij hasło</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>