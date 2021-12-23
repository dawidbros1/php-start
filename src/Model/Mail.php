<?php

declare (strict_types = 1);

namespace App\Model;

class Mail
{
    protected static $config = [];

    public static function initConfiguration(array $config): void
    {
        self::$config = $config;
    }

    public static function contact(array $data)
    {
        $headers = "From: " . strip_tags($data['from']) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($data['from']) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $data['name'] = htmlentities($data['name']);
        $data['message'] = htmlentities($data['message']);

        $html = "<html> <head> </head> <body> <p>Imię i nazwisko: " . $data['name'] . " </p> " . $data['message'] . " </body> </html>";

        return Mail::send(self::$config['email'], $data['subject'], $html, $headers);
    }

    public static function forgotPassword(array $data)
    {
        $headers = "From: " . strip_tags(self::$config['email']) . "\r\n";
        $headers .= "Reply-To: " . strip_tags(self::$config['email']) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = "";
        $message .= "Witaj " . $data['username'] . ", <br><br>";
        $message .= "<strong>Otrzymaliśmy prośbę o zmianę hasła do Twojego konta</strong><br><br>";
        $message .= "Jeśli nie zażądałeś tej zmiany, możesz zignorować tę wiadomość.<br><br>";
        $message .= "Aby ustawić nowe hasło, kliknij w poniższy link: <br>";
        $message .= '<a href = "' . $data['link'] . '">' . $data['link'] . '</a> <br><br>';
        $message .= "Link wygaśnie za 24 godziny<br><br>";
        $message .= "Wiadomość została wysłana automatycznie, prosimy na nią nie odpowiadać.<br><br>";
        $message .= "Pozdrawiam";

        $html = "<html><head></head><body>" . $message . "</body></html>";

        return Mail::send($data['email'], $data['subject'], $html, $headers);
    }

    public static function send($email, $subject, $html, $headers)
    {
        if (mail($email, $subject, $html, $headers)) {
            return true;
        } else {
            Session::set('error', "Wystąpił problem podczas wysyłania wiadomości, prosimy spróbować później");
            return false;
        }
    }
}
