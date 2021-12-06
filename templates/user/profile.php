<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>



<!-- soruce: https://bbbootstrap.com/snippets/bootstrap-5-myprofile-90806631 -->

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <!-- Lewa kolumna - Zjęcie użytkownika -->
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5"
                    width="150px"
                    src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span
                    class="font-weight-bold">Edogaru</span><span class="text-black-50"><?=$user->email?></span><span>
                </span></div>
        </div>

        <!-- Środkowa kolumna - Ustawienia profilu -->
        <div class="col-md-5 border-right">
            <div class="p-3 pt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Ustawienia profilu</h4>
                </div>

                <!-- Adres email użytkownika -->
                <div class="row mt-3 mb-3">
                    <div class="col-md-12"><label class="labels">Adres email</label><input type="text" disabled
                            class="form-control" value="<?=$user->email?>"></div>
                </div>

                <!--  Zmiana nazwy użytkownika  -->
                <div class="mb-3 border p-2 pt-1">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                        <h6 class="text-center w-100">Zmień nazwę użytkownika</h6>
                    </div>

                    <form action="<?=$route['user.updateUsername']?>" method="post">
                        <div class="row mt-1">
                            <div class="col-md-12"><label class="labels">Nazwa użytkownika</label><input type="text"
                                    class="form-control" placeholder="Nazwa użytkownika" value="<?=$user->username?>"
                                    name="username">
                            </div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:username:strlen'))?>

                        <div class="mt-2 text-center"><button class="btn btn-primary profile-button w-100"
                                type="submit">Aktualizuj </button></div>
                    </form>
                </div>

                <!-- Zmiana hasła -->

                <div class="mb-1 border p-2 pt-1">
                    <form action="<?=$route['user.updatePassword']?>" method="post">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                            <h6 class="text-center w-100">Zmień hasło</h6>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Aktualne hasło</label><input type="password"
                                    class="form-control" placeholder="Aktualne hasło" name="current_password"></div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:current_password:same'))?>

                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Nowe hasło</label><input type="password"
                                    class="form-control" placeholder="Nowe hasło" name="password">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Powtórz nowe hasło</label><input
                                    type="password" class="form-control" placeholder="Powtórz now hasło"
                                    name="repeat_password"></div>
                        </div>

                        <div class="mt-2 text-center"><button class="btn btn-primary profile-button w-100"
                                type="submit">Aktualizuj hasło</button></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Prawo koluman - Informacje o koncie -->
        <div class="col-md-4">
            <div class="px-3 col-12 pt-md-5">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                    <h6 class="text-center w-100">Informacje o koncie</h6>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span>Data utworzenia: <?=$user->created?></span>
                </div>
            </div>
        </div>
    </div>
</div>