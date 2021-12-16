<?php

declare (strict_types = 1);

namespace App\Model;

use App\Validator\AbstractValidator;

class SocialMedium extends AbstractValidator
{
    public $id;
    public $name;
    public $icon;
    public $link;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->icon = $data['icon'] ?? null;
        $this->link = $data['link'] ?? null;
    }

    public function valdiatetData($data, $rules)
    {
        return $this->validate(
            [
                'name' => $data["name"],
                'link' => $data["link"],
            ], $rules
        );
    }

    public function update(array $data)
    {
        $this->name = $data['name'];
        $this->link = $data['link'];
    }
}
