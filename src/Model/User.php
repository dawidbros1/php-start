<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\UserRepository;
use App\Rules\UserRules;
use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;
use Phantom\Model\Config;
use Phantom\Validator\Validator;

class User extends AbstractModel
{
    private $id, $username, $email, $password, $avatar, $role, $created_at;
    public static $defaultAvatar;
    public static $uploadedLocation;
    protected $table = "users";
    public static function initConfiguration(Config $config)
    {
        self::$defaultAvatar = $config->get('default.path.avatar');
        self::$uploadedLocation = $config->get('upload.path.avatar');
    }

    # Method updates username
    public function updateUsername($username)
    {
        $validator = new Validator(['username' => $username], new UserRules());

        if ($validator->validate()) {
            Session::success("Nazwa użytkownika została zmieniona");
            $this->setUsername($username);
            $this->update(['username']);
        }
    }

    # Method updates password
    public function updatePassword($data)
    {
        $validator = new Validator($data, new UserRules());

        # Checks if currunt password is corrent
        if (!$same = ($this->password == $this->hash($data['current_password']))) {
            Session::set("error:current_password:same", "Podane hasło jest nieprawidłowe");
        }

        # Validate password and repeat_password
        if ($validator->validate() && $same) {
            $this->setPassword($data['password']);
            $this->password = $this->hash($this->password);
            $this->update(['password']);

            Session::success('Hasło zostało zaktualizowane');
        }
    }

    # Method updates avatar
    public function updateAvatar($file, $path)
    {
        $validator = new Validator([], new UserRules());

        # Validate the image with the size and extension
        if ($validator->validateImage($file, 'avatar')) {
            $file = $this->hashFile($file); # Change file name to unique file name

            # Upload file to selected path from config
            if ($this->uploadFile($path, $file)) {
                $this->deleteAvatar(); # Delete old avatar
                $this->setAvatar($file['name']);
                (new UserRepository())->updateAvatar($this, $file['name']);
                Session::success('Awatar został zaktualizowany');
            }
        }
    }

    # Method checks if user role is admin
    public function isAdmin()
    {
        return (bool) ($this->role === "admin");
    }

    // ===== ===== ===== ===== =====

    # Method deletes avatar
    public function deleteAvatar()
    {
        if ($this->avatar != null && file_exists($this->getAvatar())) {
            unlink($this->getAvatar());
        }
    }

    # Method returns id of logged user
    public static function ID()
    {
        return Session::get('user:id', 0);
    }

    # GETTERS * SETTERS
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function hashPassword()
    {
        $this->password = $this->hash($this->password);

        return $this;
    }

    # Method returns avatar
    public function getAvatar(bool $toView = false)
    {
        if ($this->avatar == null) {
            # if avatar is not sets -> get path to default avatar
            $avatar = $this->avatar == null ? self::$defaultAvatar : $this->avatar;
        } else {
            # if avatar is set -> get path to uploadedLocation avatar
            $avatar = self::$uploadedLocation . $this->avatar;
        }

        if ($toView === true) {
            $avatar = $this->_getLocation() . $avatar;
        }

        return $avatar;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}