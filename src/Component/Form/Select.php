<?php

namespace App\Component\Form;

class Select
{
    public $require = ['name', 'options', 'selected', 'show', 'label'];
    public $template = "components/form/select.php";
}
