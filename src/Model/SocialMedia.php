<?php

declare (strict_types = 1);

namespace App\Model;

class SocialMedia
{
    public $id;
    public $name;
    public $icon;
    public $link;

    public function findAll()
    {
        $media = $this->repository->getAll();
    }

    public function fill()
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->icon = $data['icon'];
        $this->link = $data['link'];
    }
}
