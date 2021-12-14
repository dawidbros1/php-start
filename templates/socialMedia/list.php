<?php

declare (strict_types = 1);

use App\Error;

?>

<div class = "mt-4 container">
    <div class="border">
        <div class = "col-12 text-center fw-bold">Media społecznościowe</div>

        <div class = "row media">
            <?php foreach ($params['media'] ?? [] as $medium): ?>
                <div class = "col-2">
                    <div class = "medium col-12">
                        <img src = "<?=$medium['icon']?>">
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- ADD NEW MEDIUM SECTION -->


    <div class = "row">
        <div class = "col-12 text-center fw-bold">Utwórz nowe medium społecznościowe</div>


        <!-- <form action="<?=$route['user.updateUsername']?>" method="post">
            <div class="row mt-1">
                <div class="col-md-12">
                    <label class="labels">Nazwa użytkownika</label>
                    <input type="text" class="form-control" placeholder="Nazwa użytkownika" name="name">
                </div>
                <div class="col-md-12">
                    <label class="labels">Adres obrazka</label>
                    <input type="text" class="form-control" placeholder="Nazwa użytkownika" name="name">
                </div>
                <div class="col-md-12">
                    <label class="labels">Link</label>
                    <input type="text" class="form-control" placeholder="Nazwa użytkownika" name="name">
                </div>
            </div>

            <div class="mt-2 text-center"><button class="btn btn-primary profile-button w-100"
                    type="submit">Aktualizuj nazwę </button>
            </div>
        </form> -->
    </div>
</div>
