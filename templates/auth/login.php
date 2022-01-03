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
                    <?php Component::render('auth.input', ['class' => "mt-3", 'type' => "email", 'name' => "email", "placeholder" => "Adres email", 'value' => $params['email'] ?? ''])?>
                    <?php Component::render('error', ['type' => "email", 'names' => ['null']])?>


                    <?php Component::render('auth.input', ['class' => "mt-3", 'type' => "password", 'name' => "password", "placeholder" => "Hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['incorrect']])?>

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