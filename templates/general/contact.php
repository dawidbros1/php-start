<!-- https://bbbootstrap.com/snippets/bootstrap-5-get-touch-contact-form-75878843 -->

<div class="content" id = "contact">
    <div class="contact">
        <div class="other">
            <div class="info">
                <h2>Informacje o nas</h2>
                <h3>Adres email</h3>
                <div class = "email"> example@gmail.com </div>
                <h3>Media społecznościowe</h3>

                <div id="icons">
                    <?php foreach ($social = $params['social'] as $item): ?>
                        <a target="_blank" href = "<?=$item['link']?>"><img src = "<?=$item['icon']?>"></a>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div class="form">
            <h1>Napisz do nas</h1>
            <form action="<?=$route['contact']?>" method = "POST">
                <div class="flex-rev"> <input type="text" placeholder="Podaj swoje imię oraz nazwisko" name="name"
                        id="name" /> <label for="name">Imię i nazwisko</label> </div>

                <div class="flex-rev"> <input type="email" placeholder="example@gmail.com" name="from" id="from" />
                    <label for="from">Adres email</label>
                </div>

                <div class="flex-rev"> <input type="text" placeholder="Temat wiadomości" name="subject" id="subject" />
                    <label for="subject">Temat</label>
                </div>

                <div class="flex-rev"> <textarea name="message" placeholder="Powiedz, w jakim celu do nas piszesz"
                        id="message"></textarea>
                    <label for="message">Wiadomość</label>
                </div> <button>Wyślij wiadomość</button>
            </form>
        </div>
    </div>
</div>
