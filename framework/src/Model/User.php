<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Helper\Session;

class User extends Model
{
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
            return $this->avatar ?? self::$defaultAvatar;
        } else {
            return self::$uploadedLocation . $this->avatar;
        }
    }

    public static function ID()
    {
        return Session::get('user:id', 0);
    }
}
