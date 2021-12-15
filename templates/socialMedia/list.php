<?php

declare (strict_types = 1);

use App\Error;
use App\Helper\Session;

?>

<div class = "container my-sm-5">
    <div class = "row bg-white py-md-5">
        <div class="col-12 offset-0 offset-md-1 col-md-10 p-2 px-4">
        <!-- MEDIA SECTION [ START ] -->
           <div class = "mb-5">
                <h2 class = "text-center fw-bold border-bottom py-1 col-12">Media społecznościowe</h2>
                <div>
                    <div class = "row">
                        <?php foreach ($params['media'] ?? [] as $medium): ?>
                            <!-- <div class = "col-3 col-md-2"> -->
                            <div class = "col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1">
                                <a href = "#"><img src = "<?=$medium['icon']?>" class = "medium-img mb-2"></a>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
           </div>
        <!-- MEDIA SECTION [ END ] -->

        <!-- ADD MEDIUM SECTION [ START ] -->
            <div>
                <h2 class = "text-center fw-bold border-bottom py-1 col-12">Dodaj nowe medium społecznościowe</h2>

                <form action="<?=$route['social.create']?>" method="post" enctype="multipart/form-data">
                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <label class="labels">Nazwa</label>
                            <input type="text" class="form-control" placeholder="Podaj nazwę medium społecznościowego" name="name" value = "<?=$params['data']['name'] ?? ""?>">
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:name:between'))?>

                        <div class="col-sm-12 mt-1">
                            <label class="labels">Wybierz ikonę</label>
                            <input type="file" class="form-control" name="icon">
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:file:empty'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:notImage'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:maxSize'))?>
                        <?php Error::render('input', Session::getNextClear('error:file:types'))?>

                        <div class="col-sm-12 mt-1">
                            <label class="labels">Podaj link</label>
                            <input type="text" class="form-control" placeholder="Podaj link do medium społecznościowego" name="link" value = <?=$params['data']['link'] ?? ""?>>
                        </div>

                        <?php Error::render('input', Session::getNextClear('error:link:max'))?>
                        <?php Error::render('input', Session::getNextClear('error:link:require'))?>
                    </div>

                    <div class="mt-2 text-center"><button class="btn btn-primary profile-button w-100"
                            type="submit">Dodaj </button>
                    </div>
                </form>
            </div>
        <!-- ADD MEDIUM SECTION [ END ] -->
        </div>
    </div>
</div>