<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Repository\SocialMediaRepository;
use App\Rules\SocialMediaRules;
use App\Validator\AbstractValidator;

class SocialMedia extends AbstractValidator
{
    public $id;
    public $name;
    public $icon;
    public $link;
    public $rules;

    public function __construct()
    {
        $this->repository = new SocialMediaRepository();
    }

    public function fill($data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->icon = $data['icon'] ?? null;
        $this->link = $data['link'] ?? null;
    }

    public function save($FILE, $path, $data)
    {
        $this->rules = new SocialMediaRules();
        $validateStatus = $this->validate($data);

        $this->rules->selectType('icon');
        $validateImageStatus = $this->validateImage($FILE, $this->rules);

        if ($validateStatus && $validateImageStatus) {
            $target_dir = $path;
            $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));
            $target_file = $target_dir . basename($FILE["name"]);
            $data['icon'] = $path . $FILE['name'];

            if ($ok = move_uploaded_file($FILE["tmp_name"], $target_file)) {
                $this->repository->save($data);
                return true;
            } else {
                Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            }
        }

        return false;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    // ===== ===== ===== ===== =====

    private function deleteFile($file)
    {
        if ($file != "" || $file != null) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
