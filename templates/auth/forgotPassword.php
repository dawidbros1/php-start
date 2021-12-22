<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
         <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Resetowanie hasła</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('auth.forgotPassword')?>" method="post">
                    <div class="input-group mt-3">
                        <!-- <label for="email" class= "w-100">Adres email: </label> -->
                        <span class="input-group-text bg-primary"><i class="bi bi-envelope text-white"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Adres email"
                            value="<?=$params['email'] ?? ""?>">
                    </div>

                    <?php Error::render('input', Session::getNextClear('error:email:null'))?>

                    <div class="d-grid col-12 mx-auto mt-3">
                        <button class="btn btn-primary" type="submit"><span></span> Przypomnij hasło </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>