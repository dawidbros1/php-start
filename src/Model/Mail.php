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
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'To: ' . $config['to'];
        $headers[] = 'From: ' . $this->from;

        $message = "<html> <head> </head> <body> " . $this->message . " </body> </html>";

        return mail($config['to'], $this->subject, $message, $headers);
    }
}
