<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>

<?php $medium = $params['medium']?>

<div class = "container my-sm-5">
    <div class = "row bg-white py-md-5">
        <div class="col-12 offset-0 offset-md-1 col-md-10 p-2">
        <!-- EDIT MEDIUM SECTION [ START ] -->
            <div class = "border px-4 position-relative">
                <h2 class = "text-center fw-bold border-bottom py-1 col-12">Edytuj medium społecznościowe</h2>
                <form action="<?=$route['medium.edit'] . "&id=" . $medium->id?>" method="post" enctype="multipart/form-data">
                    <div class="row mt-1">
                        <div class="col-12 mt-1">
                            <label class="labels">Nazwa</label>
                            <input type="text" class="form-control" placeholder="Podaj nazwę medium społecznościowego" name="name" value = "<?=$medium->name?>">
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:name:between'))?>

                        <div class="mt-1" style = "">
                            <div class = "d-flex align-items-center">
                                <div class = "col-2 col-lg-1">
                                    <img src = "<?=$medium->icon?>" class = "pe-2 py-1">
                                </div>

                                <div class="col-10 col-lg-11">
                                        <label class="labels">Wybierz ikonę</label>
                                        <input type="file" class="form-control" name="icon">
                                </div>
                            </div>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:file:empty'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:notImage'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:maxSize'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:types'))?>

                        <div class="col-12 mt-1">
                            <label class="labels">Podaj link</label>
                            <input type="text" class="form-control" placeholder="Podaj link do medium społecznościowego" name="link" value = <?=$medium->link?>>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:link:max'))?>
                        <?php Error::render('input', Session::getNextClear('error:link:require'))?>


                        <div class="mt-2 text-center col-9 col-lg-10"><button class="btn btn-primary w-100" type="submit">Edytuj </button></div>
                    </div>
                </form>


                <div class="text-center delete offset-1 col-2 col-lg-1"><button class="btn btn-danger w-100" type="submit"> <a href = "<?=$route['medium.delete'] . "&id=" . $medium->id?>">Usuń</a> </button></div>

            </div>
        <!-- EDIT MEDIUM SECTION [ END ] -->
        </div>
    </div>
</div>