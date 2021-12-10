<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>

<div style="margin-top:150px">
    <div class="rounded d-flex justify-content-center">
        <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Logowanie</h3>
            </div>
            <div class="p-4">
                <form action="?type=auth&action=login" method="post">
                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i class="bi bi-envelope text-white"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Adres email"
                            value="<?=$params['email'] ?? ""?>">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:email:null'))?>

                    <div class="input-group mt-3">
                        <span class="input-group-text bg-primary"><i class="bi bi-key-fill text-white"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Hasło">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:password:incorrect'))?>

                    <div class="d-grid col-12 mx-auto mt-3">
                        <button class="btn btn-primary" type="submit"><span></span> Zaloguj się </button>
                    </div>
                    <p class="text-center mt-3">Nie masz jeszcze konta?
                        <a href="?type=auth&action=register" class="link">Zarejestruj się</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>