<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>

<!-- soruce: https://bbbootstrap.com/snippets/bootstrap-5-myprofile-90806631 + moje poprawki -->

<!-- <div class="container rounded bg-white mt-5 mb-5"> -->
<div class="container rounded bg-white my-sm-5">
    <div class="row">
        <!-- Lewa kolumna - Zjęcie użytkownika -->
        <div class="col-md-0 col-lg-1"></div>
        <div class="col-md-12 col-lg-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">

            <!-- START -->
                <div class = "w-100 fw-bold px-2 pt-1">
                    <form action = "<?=$route->get('user.update')?>" method = "post" enctype="multipart/form-data">

                        <div class = "position-relative mx-auto mt-5" id = "avatarBox">
                            <img id = "avatar" class="rounded-circle" src="<?=$user->avatar?>">
                            <input type = "file" name = "avatar" class = "rounded-circle" id = "file">
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:file:empty'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:notImage'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:maxSize'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:types'))?>

                        <div>
                            <div class="fw-bold"> <?=$user->username?></div>
                            <div class="text-black-50"><?=$user->email?></div>
                            <div class = "border-top w-100 mb-2"></div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary profile-button w-100" type="submit">Zmień awatar</button>
                        </div>

                        <input type = "hidden" name = "update" value = "avatar">
                    </form>
                </div>
             <!-- END -->
            </div>
        </div>

        <!-- Środkowa kolumna - Ustawienia profilu -->
        <div class="col-md-12 col-lg-7 border-right">
            <div class="p-3 pt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Ustawienia profilu</h4>
                </div>

                <!-- Adres email użytkownika -->
                <div class="row mt-3 mb-3">
                    <div class="col-lg-12"><label class="labels">Adres email</label><input type="text" disabled
                            class="form-control" value="<?=$user->email?>"></div>
                </div>

                <!--  Zmiana nazwy użytkownika  -->
                <div class="mb-3 border p-2 pt-1">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                        <h6 class="text-center w-100">Zmień nazwę użytkownika</h6>
                    </div>

                    <form action="<?=$route->get('user.update')?>" method="post">
                        <div class="row mt-1">
                            <div class="col-md-12"><label class="labels">Nazwa użytkownika</label><input type="text"
                                    class="form-control" placeholder="Nazwa użytkownika" value="<?=$user->username?>"
                                    name="username">
                            </div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:username:between'))?>
                        <?php Error::render('input', Session::getNextClear('error:username:specialCharacters'))?>

                        <div class="mt-2 text-center"><button class="btn btn-primary profile-button w-100"
                                type="submit">Aktualizuj nazwę </button></div>

                        <input type = "hidden" name = "update" value = "username">
                    </form>
                </div>

                <!-- Zmiana hasła -->

                <div class="mb-1 border p-2 pt-1">
                    <form action="<?=$route->get('user.update')?>" method="post">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                            <h6 class="text-center w-100">Zmień hasło</h6>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Aktualne hasło</label><input type="password"
                                    class="form-control" placeholder="Aktualne hasło" name="current_password"></div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:password:current'))?>

                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Nowe hasło</label><input type="password"
                                    class="form-control" placeholder="Nowe hasło" name="password">
                            </div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:password:between'))?>

                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Powtórz nowe hasło</label><input
                                    type="password" class="form-control" placeholder="Powtórz now hasło"
                                    name="repeat_password"></div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:password:same'))?>

                        <div class="mt-2 text-center"><button class="btn btn-primary profile-button w-100"
                                type="submit">Aktualizuj hasło</button></div>
                        <input type = "hidden" name = "update" value = "password">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-0 col-lg-1"></div>
    </div>
</div>