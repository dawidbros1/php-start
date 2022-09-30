<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Helper\Session;
use Phantom\Model\Config;
use Phantom\Model\Model;

class User extends Model
{
    public $id, $username, $email, $password, $avatar, $role, $created;
    public static $defaultAvatar;
    public static $uploadedLocation;
    public static function initConfiguration(Config $config)
    {
        self::$defaultAvatar = $config->get('default.path.avatar');
        self::$uploadedLocation = $config->get('upload.path.avatar');
    }
    public $fillable = ['id', 'username', 'email', 'password', 'avatar', 'role', 'created'];

    public function logout()
    {
        Session::clear('user:id');
        Session::success("Nastąpiło wylogowanie z systemu");
    }

    public function updateUsername()
    {
        if ($this->update(['username'])) {
            Session::success("Nazwa użytkownika została zmieniona");
        }
    }

    public function updatePassword($data)
    {
        if (!$same = ($this->password == $this->hash($data['current_password']))) {
            Session::set("error:current_password:same", "Podane hasło jest nieprawidłowe");
        }

        if ($this->validate($data) && $same) {
            $this->set('password', $this->hash($data['password']));

            if ($this->update([], false)) {
                Session::success('Hasło zostało zaktualizowane');
            }
        }
    }

    public function updateAvatar($file, $path)
    {
        if ($this->validateImage($file, 'avatar')) {
            $file = $this->hashFile($file);

            if ($this->uploadFile($path, $file)) {
                $this->deleteAvatar();
                $this->set('avatar', $file['name']);

                if ($this->update([], false)) {
                    Session::success('Awatar został zaktualizowany');
                }
            }
        }
    }

    public function isAdmin()
    {
        return (bool) ($this->role === "admin");
    }

    // ===== ===== ===== ===== =====

    public function deleteAvatar()
    {
        if ($this->avatar != null && file_exists($this->getAvatar())) {
            unlink($this->getAvatar());
        }
    }
    public function getAvatar()
    {
        if ($this->avatar == null) {
            $avatar = $this->avatar ?? self::$defaultAvatar;
        } else {
            $avatar = self::$uploadedLocation . $this->avatar;
        }

        return self::$config->get("project.location") . $avatar;
    }

    public function hashPassword()
    {
        $this->password = $this->hash($this->password);
    }

    public static function ID()
    {
        return Session::get('user:id', 0);
    }
}
