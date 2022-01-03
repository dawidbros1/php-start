<?php

declare (strict_types = 1);

use App\Component;
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
                    <?php Component::render('auth.input', ['class' => "", 'type' => "email", 'name' => "email", "placeholder" => "Adres email", 'value' => $params['email'] ?? ''])?>
                    <?php Component::render('error', ['type' => "email", 'names' => ['sanitize', 'validate', 'unique']])?>

                    <?php Component::render('auth.input', ['class' => "mt-3", 'type' => "text", 'name' => "username", "placeholder" => "Nazwa użytkownika", 'value' => $params['username'] ?? ''])?>
                    <?php Component::render('error', ['type' => "username", 'names' => ['between', 'specialCharacters']])?>

                    <?php Component::render('auth.input', ['class' => "mt-3", 'type' => "password", 'name' => "password", "placeholder" => "Hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['between']])?>

                    <?php Component::render('auth.input', ['class' => "mt-3", 'type' => "password", 'name' => "repeat_password", "placeholder" => "Powtórz hasło"])?>
                    <?php Component::render('error', ['type' => "repeat_password", 'names' => ['same']])?>

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