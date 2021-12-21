<?php

declare (strict_types = 1);

namespace App\Model;

class Mail
{
    public function __construct(array $data)
    {
        $this->name = $data["name"];
        $this->subject = $data["subject"];
        $this->from = $data["from"];
        $this->message = $data['message'];
    }

    public function send($config)
    {
        $headers = "From: " . strip_tags($this->from) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($this->from) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = "<html> <head> </head> <body> <p>Imię i nazwisko: " . $this->name . " </p> " . $this->message . " </body> </html>";

        return mail($config['to'], $this->subject, $message, $headers);
    }
}
