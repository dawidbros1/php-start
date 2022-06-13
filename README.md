# PHP Start
The project is a complete file package to create applications in PHP technology.

## Build with
1. PHP 7.4

## Features
1. Registration / Login
2. Password recovery
3. User profile management (username / photo / password)

## Installation Instructions
1. Run `git clone https://github.com/dawidbros1/php-start.git`
2. Run `componser install`
3. Create a MySQL database for the project
4. From the project root folder run `cd .\config\` and next `rename config_dist.php config.php`
5. Configure your `./config/config.php` file
6. Import tables from file `./sql/database.sql` to your database

## Routes
| Method | URI ( ./? ) | Action | Name |
| --- | --- | --- | --- |
| GET | action=home | src/Controller/GeneralController@homeAction | home |
| GET | action=regulations | src/Controller/GeneralController@policyAction | regulations |
| GET | action=policy | src/Controller/GeneralController@regulationsAction | pollicy |
| GET\|POST | action=contact| src/Controller/GeneralController@contactAction | contact |
| GET\|POST | type=auth&action=register | src/Controller/AuthController@registerAction | auth.register |
| GET\|POST | type=auth&action=login | src/Controller/AuthController@loginAction | auth.login |
| GET\|POST | type=auth&action=forgotPassword | src/Controller/AuthController@forgotPasswordAction | auth.forgotPassword |
| GET\|POST | type=auth&action=resetPassword&code=... | src/Controller/AuthController@resetPasswordAction | auth.resetPassword |
| ANY | type=user&action=logout | src/Controller/UserController@logoutAction | user.logout |
| GET | type=user&action=profile | src/Controller/UserController@profileAction | user.profile |
| POST | type=user&action=update&update=... | src/Controller/UserController@updateAction | user.update |

## Tree directory
   - [composer.json](composer.json)
   - [composer.lock](composer.lock)
   - __config__
     - [config\_dist.php](config/config_dist.php)
   - [index.php](index.php)
   - __public__
     - __css__
       - [contact.css](public/css/contact.css)
       - [profile.css](public/css/profile.css)
       - [style.css](public/css/style.css)
     - __images__
       - __SocialMedia__
         - [facebook.png](public/images/SocialMedia/facebook.png)
         - [instagram.png](public/images/SocialMedia/instagram.png)
         - [linkedin.png](public/images/SocialMedia/linkedin.png)
         - [twitter.png](public/images/SocialMedia/twitter.png)
         - [youtube.png](public/images/SocialMedia/youtube.png)
       - [avatar.png](public/images/avatar.png)
     - __js__
       - [main.js](public/js/main.js)
   - [recaptchalib.php](recaptchalib.php)
   - __routes__
     - [routes.php](routes/routes.php)
   - __sql__
     - [database.sql](sql/database.sql)
   - __src__
     - [Component.php](src/Component.php)
     - __Controller__
       - [AuthController.php](src/Controller/AuthController.php)
       - [Controller.php](src/Controller/Controller.php)
       - [GeneralController.php](src/Controller/GeneralController.php)
       - [UserController.php](src/Controller/UserController.php)
     - __Exception__
       - [AppException.php](src/Exception/AppException.php)
       - [ConfigurationException.php](src/Exception/ConfigurationException.php)
       - [NotFoundException.php](src/Exception/NotFoundException.php)
       - [StorageException.php](src/Exception/StorageException.php)
     - __Helper__
       - [Request.php](src/Helper/Request.php)
       - [Session.php](src/Helper/Session.php)
     - __Model__
       - [Auth.php](src/Model/Auth.php)
       - [Config.php](src/Model/Config.php)
       - [Mail.php](src/Model/Mail.php)
       - [Model.php](src/Model/Model.php)
       - [Route.php](src/Model/Route.php)
       - [Rules.php](src/Model/Rules.php)
       - [User.php](src/Model/User.php)
     - __Repository__
       - [AuthRepository.php](src/Repository/AuthRepository.php)
       - [Repository.php](src/Repository/Repository.php)
       - [UserRepository.php](src/Repository/UserRepository.php)
     - __Rules__
       - [AuthRules.php](src/Rules/AuthRules.php)
       - [UserRules.php](src/Rules/UserRules.php)
     - __Utils__
       - [debug.php](src/Utils/debug.php)
     - __Validator__
       - [Validator.php](src/Validator/Validator.php)
     - [View.php](src/View.php)
   - __templates__
     - __auth__
       - [forgotPassword.php](templates/auth/forgotPassword.php)
       - [login.php](templates/auth/login.php)
       - [register.php](templates/auth/register.php)
       - [resetPassword.php](templates/auth/resetPassword.php)
     - __component__
       - __button__
         - [back.php](templates/component/button/back.php)
       - [error.php](templates/component/error.php)
       - __form__
         - [button.php](templates/component/form/button.php)
         - [input.php](templates/component/form/input.php)
     - __general__
       - [contact.php](templates/general/contact.php)
       - [home.php](templates/general/home.php)
       - [policy.php](templates/general/policy.php)
       - [regulations.php](templates/general/regulations.php)
     - __layout__
       - [main.php](templates/layout/main.php)
     - [messages.php](templates/messages.php)
     - __user__
       - [profile.php](templates/user/profile.php)
   - __uploads__
     - __images__
       - __avatar__

# IN PROGRESS

## Rules
Class `src/model/rules` is created to define validate rules.

+ **createRules(string $type, array $rules): void**
```
public function createRule(string $type, array $rules): void
{
  foreach ($rules as $name => $value) {
      $this->rules[$type][$name]['value'] = $value;
  }
}
```
Add rule to property rules.

+ **createMessages(string $type, array $rules): void**
```
public function createMessages(string $type, array $rules): void
{
  foreach ($rules as $name => $message) {
      if ($name == 'between') {
          $this->rules[$type]['min']['message'] = $message;
          $this->rules[$type]['max']['message'] = $message;
      } else {
          $this->rules[$type][$name]['message'] = $message;
      }
  }
}
```
Add error messages to rules of type.

+ **value(?string $name = null)**
```
public function value(?string $name = null)
{
  return $this->getRule($name)['value'];
}
```
Return value of rule.
 
+ **message(?string $name = null: string): string**
```
public function message(?string $name = null): string
{
  return $this->getRule($name)['message'];
}
```
Return messages of rule.

+ **arrayValue(string $name, bool $uppercase = false): string**
```
public function arrayValue(string $name, bool $uppercase = false): string
{
  $type = strtok($name, '.');
  $rule = substr($name, strpos($name, '.') + 1);
  $output = '';

  foreach ($this->rules[$type][$rule]['value'] as $value) {
      $output .= $value . ', ';
  }

  if ($uppercase) {
      $output = strtoupper($output);
  }
  $output = substr($output, 0, -2);
  return $output;
}
```
Return array value of rules as string
 
+ **hasType(string $type): bool**
```
public function hasType(string $type): bool
{
  if (array_key_exists($type, $this->rules)) {
      return true;
  } else {
      return false;
  }
}
```
Check if exists given type.
 
+ **selectType(string $type): void**
```
public function selectType(string $type): void
{
  if (!$this->hasType($type)) {
      throw new AppException('The selected type does not exist');
  }
  $this->selectedType = $type;
}
```
Set selectedType on given type

+ **clearType(): void** 
```
public function clearType(): void
{
  $this->selectedType = null;
}
```
Set selectedType on null

+ **getType(): array**
```
public function getType(?string $type = null): array
{
  if ($type === null) {
      if ($this->selectedType !== null) {
          return $this->rules[$this->selectedType];
      } else {
          throw new AppException('Rule type has not been entered');
      }
  } else {
      if (!$this->hasType($type)) {
          throw new AppException('The selected type does not exist');
      } else {
          return $this->rules[$type];
      }
  }
}
```
Return rules of selectedType or given type.

+ **typeHasRules(array $keys, ?string $type = null): bool**
```
public function typeHasRules(array $rules, ?string $type = null): bool
{
  if ($this->selectedType != null) {
      $type = $this->rules[$this->selectedType];
  } elseif ($type == null) {
      throw new AppException('Typ reguły nie został wprowadzony');
  } elseif (!$this->hasType($type)) {
      throw new AppException('Wybrany typ nie istnieje');
  } else {
      $type = $this->rules[$type];
  }

  foreach ($rules as $rule) {
      if (!array_key_exists($rule, $type)) {
          return false;
      }
  }

  return true;
}
```
Check if type of rule has all given keys.

+ **getRule(string $name): array**
```
private function getRule(string $name): array
{
  if ($this->selectedType) {
      return $this->getType()[$name]; // Name like a min | max
  } else {
      $typeName = strtok($name, '.');
      $ruleName = substr($name, strpos($name, '.') + 1);

      $type = $this->getType($typeName); // Name like a password.min | password.max

      if ($this->typeHasRules([$ruleName], $typeName)) {
          return $type[$ruleName];
      } else {
          throw new AppException('Wybrana reguła nie istnieje');
      }
  }
}
```
Private method to return rule.

### How to create new rule
1. Create new file in ./src/rules/ with name like a **NameRules.php**
2. Example rule file:
```
<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Model\Rules;

class NameRules extends Rules
{
    public function rules()
    {
        $this->createRule('username', ['min' => 3, "max" => 16, 'specialCharacters' => false]);
    }

    public function messages()
    {
        $this->createMessages('username', [
            'min' => "Username cannot contain less than". $this->value('username.min') "characters",
            'max' => "Username cannot contain more than". $this->value('username.max') "characters",
            'specialCharacters' => "Username cannot contain special characters",
        ]);
    }
}
```

**Min** and **max** can be replace with **between** rule:
```
'between' => "Username should contain from". $this->value('username.min'). "to". $this->value('username.max'). "characters",
```

## Controllers
Controllers are designed to manage the entire application.

### Basic controllers
<details>
   <summary>Controller</summary>
   
  + initConfiguration(Config $config, Route $route): void
```
public static function initConfiguration(Config $config, Route $route): void
{
  self::$config = $config;
  self::$route = $route;
}
```
Initialize properties such as config and route.

+ __construct(Request $request)
```
public function __construct(Request $request)
{
  if (empty(self::$config->get('db'))) {
      throw new ConfigurationException('Configuration error');
  }

  Repository::initConfiguration(self::$config->get('db'));
  Mail::initConfiguration(self::$config->get('mail'));

  $this->hashMethod = self::$config->get('hash.method');
  $this->userRepository = new UserRepository();

  if ($id = Session::get('user:id')) {
      $this->user = $this->userRepository->get((int) $id);
  }

  $this->request = $request;
  $this->view = new View($this->user, self::$route);
}
```

+ initConfiguration(Config $config, Route $route): void
```
public static function initConfiguration(Config $config, Route $route): void
{
  self::$config = $config;
  self::$route = $route;
}
```
Initialize properties such as config and route.

+ __construct(Request $request)
```
public function __construct(Request $request)
{
  if (empty(self::$config->get('db'))) {
      throw new ConfigurationException('Configuration error');
  }

  Repository::initConfiguration(self::$config->get('db'));
  Mail::initConfiguration(self::$config->get('mail'));

  $this->hashMethod = self::$config->get('hash.method');
  $this->userRepository = new UserRepository();

  if ($id = Session::get('user:id')) {
      $this->user = $this->userRepository->get((int) $id);
  }

  $this->request = $request;
  $this->view = new View($this->user, self::$route);
}
```
Check connection with database. Initialize configuration in repository and mail.
Get user if he is logged. Assigns an request class object to a property.
Create object of view class and set to a property.

+ run(): void
```
public function run(): void
{
  try {
      $action = $this->action() . 'Action';
      if (!method_exists($this, $action)) {
          Session::set("error", 'The action you wanted to access does not exist');
          $this->redirect("./");
      }

      $this->$action();
  } catch (StorageException $e) {
      $this->view->render('error', ['message' => $e->getMessage()]);
  }
}
```
If given action exists run it else redirect to homePage with error message.

+ redirect(string $to, array $params = []): void
```
protected function redirect(string $to, array $params = []): void
{
  $location = $to;

  if (count($params)) {
      $queryParams = [];
      foreach ($params as $key => $value) {
          if (gettype($value) == "integer") {
              $queryParams[] = urlencode($key) . '=' . $value;
          } else {
              $queryParams[] = urlencode($key) . '=' . urlencode($value);
          }
      }

      $location .= ($queryParams = "&" . implode('&', $queryParams));
  }

  header("Location: " . $location);
  exit();
}
```
Redirect user to selected page with parameters.

+ action(): string
```
final private function action(): string
{
  return $this->request->getParam('action', "home");
}
 ```
Return action param from request.

+ guest(): void
```
final protected function guest(): void
{
  if ($this->user != null) {
      Session::set("error", "The page you tried to access is only available to users who are not logged in.");
      $this->redirect(self::$route->get('home'));
  }
}
 ```
Method check if user is not logged in. Logged user is redirect to homePage with error message.

+ requireLogin(): void
```
final protected function requireLogin(): void
{
  if ($this->user == null) {
      Session::set('lastPage', $this->request->queryString());
      Session::set("error", "The page you tried to access requires login.");
      $this->redirect(self::$route->get('auth.login'));
  }
}
```
Method check if user is logged in. Guest is redirect to login page with error message.

+ requireAdmin()
```
final protected function requireAdmin(): void
{
  $this->requireLogin();
  Session::clear('lastPage');

  if (!$this->user->isAdmin()) {
      Session::set("error", "You do not have sufficient permissions for the action you wanted to perform");
      $this->redirect(self::$route->get('home'));
  }
}
```
Method check if user is admin. Guest is redirect to login page with error message.
User which is not admin is redirect to homePage with error message.

+ uploadFile($path, $FILE): boolval
```
protected function uploadFile($path, $FILE): boolval
{
  $target_dir = $path;
  $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));
  $target_file = $target_dir . basename($FILE["name"]);

  if (move_uploaded_file($FILE["tmp_name"], $target_file)) {
      return true;
  } else {
      Session::set('error', 'Sorry, there was a problem sending the file');
      return false;
  }
}
```
Method upload file on server.

+ hash($param, $method = null): string
```
protected function hash($param, $method = null): string
{
  return hash($method ?? $this->hashMethod, $param);
}
```
Method return hash of input param.
If hash method isn't sent, selected is default hash method from config.

+ hashFile($file)
```
protected function hashFile($file)
{
  $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $name = $this->hash(date('Y-m-d H:i:s') . "_" . $file['name']);
  $file['name'] = $name . '.' . $type;
  return $file;
}
```
Method create unique filename.
      
</details>

<details>
   <summary>AuthController</summary>
   
+ registerAction(): void
```
public function registerAction(): void
{
  View::set(['title' => "Rejestracja"]);
  $names = ['username', 'email', 'password', 'repeat_password'];

  if ($this->request->isPost() && $this->request->hasPostNames($names)) {
      $data = $this->request->postParams($names);
      $emails = $this->repository->getEmails();

      if ($this->validate($data, $this->rules) && !Auth::isBusyEmail($data['email'], $emails)) {
          $data['password'] = $this->hash($data['password']);
          $data['avatar'] = self::$config->get('default.path.avatar');
          $user = new User($data);
          $user->escape();

          $this->repository->register($user);
          Session::set('success', 'Konto zostało utworzone');
          $this->redirect(self::$route->get('auth.login'), ['email' => $user->email]);
      } else {
          unset($data['password'], $data['repeat_password']);
          $this->redirect(self::$route->get('auth.register'), $data);
      }
  } else {
      $this->view->render('auth/register', $this->request->getParams(['username', 'email']));
  }
}
```
<b>GET: </b> Show register form. <br>
<b>POST: </b> Validate data given by user. If data is validated, user is added to database.

+ loginAction(): void
```
public function loginAction(): void
{
  View::set(['title' => "Logowanie"]);
  $names = ['email', 'password'];

  if ($this->request->isPost() && $this->request->hasPostNames($names)) {
      $data = $this->request->postParams($names);

      if ($id = $this->repository->login($data['email'], $this->hash($data['password']))) {
          Session::set('user:id', $id);
          $lastPage = Session::getNextClear('lastPage');
          $this->redirect($lastPage ? "?" . $lastPage : self::$route->get('home'));
      } else {
          if (in_array($data["email"], $this->repository->getEmails())) {
              Session::set("error:password:incorrect", "The entered password is incorrect");
          } else {
              Session::set("error:email:null", "The email address provided does not exist");
          }

          unset($data['password']);
          $this->redirect(self::$route->get('auth.login'), $data);
      }
  } else {
      $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
  }
}
```
<b>GET: </b> Show login form. <br>
<b>POST: </b>Action check if exist user with appropriate e-mail address and password.

+ forgotPasswordAction(): void
```
public function forgotPasswordAction()
{
  View::set(['title' => "Przypomnienie hasła"]);
  if ($this->request->isPost() && $email = $this->request->postParam('email')) {
      if (in_array($email, $this->repository->getEmails())) {
          $location = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
          $code = rand(1, 1000000) . "_" . date('Y-m-d H:i:s');
          $hash = $this->hash($code, 'md5');

          Session::set($hash, $email);
          Session::set('created:' . $hash, time());

          $data = [];
          $data['email'] = $email;
          $data['link'] = $location . self::$route->get('auth.resetPassword') . "&code=$hash";
          $data['subject'] = $_SERVER['HTTP_HOST'] . " - Reset hasła";
          $data['username'] = $this->userRepository->get($email, 'email')->username;

          if (Mail::forgotPassword($data)) {
              Session::set('success', "A link to reset your password has been sent to the email address you provided");
          }
      } else {
          Session::set("error:email:null", "The email address provided does not exist");
      }

      $this->redirect(self::$route->get('auth.forgotPassword'));

  } else {
      $this->view->render('auth/forgotPassword');
  }
}
```
<b>GET: </b> Show form to reset password. <br>
<b>POST: </b> Send a message on address-email given from user with special code which is used to user
authorize to reset password.

+ resetPasswordAction(): void
```
public function resetPasswordAction()
{
  View::set(['title' => "Reset hasła"]);
  $names = ['password', 'repeat_password', 'code'];

  if ($this->request->isPost() && $this->request->hasPostNames($names)) {
      $data = $this->request->postParams($names);
      $code = $data['code'];
      $this->checkCodeSession($data['code']);

      if ($this->validate($data, $this->rules)) {
          $user = $this->userRepository->get(Session::get($code), 'email');
          $user->password = $this->hash($data['password']);
          $this->userRepository->update($user, 'password');
          Session::clearArray([$code, "created:" . $code]);
          Session::set('success', 'Hasło do konta zostało zmienione');
          $this->redirect(self::$route->get('auth.login'), ['email' => $user->email]);
      } else {
          $this->redirect(self::$route->get('auth.resetPassword'), ['code' => $code]);
      }
  }

  if ($this->request->isGet() && $code = $this->request->getParam('code')) {
      $this->checkCodeSession($code);
      $this->view->render('auth/resetPassword', ['email' => Session::get($code), 'code' => $code]);
  } else {
      Session::set('error', 'Kod resetu hasła nie został podany');
      $this->redirect(self::$route->get('auth.forgotPassword'));
  }
}
```
<b>GET: </b> Show reset password form. <br>
<b>POST: </b> Action check if code is sent and active, next set new password given by user.
   
+ checkCodeSession(): void   
```
private function checkCodeSession($code): void
{
  $names = [$code, "created:" . $code];

  if (Session::hasArray($names)) {
      if ((time() - Session::get("created:" . $code)) > 86400) {
          Session::set('error', 'The link to reset your password has expired');
          Session::clearArray($names);
          $this->redirect(self::$route->get('auth.forgotPassword'));
      }
  } else {
      Session::set('error', 'Invalid password reset code');
      $this->redirect(self::$route->get('auth.forgotPassword'));
  }
}
```
Private method to check session code.
   
</details>


<details>
   <summary>UserController</summary>

+ logoutAction()
```
public function logoutAction()
{
  $this->user->logout();
  Session::set('success', "Nastąpiło wylogowanie z systemu");
  $this->redirect(self::$route->get('auth.login'), ['email' => $this->user->email]);
}
```
Logout user, clear session data.

+ profileAction()
```
public function profileAction()
{
  View::set(['title' => "Profil użytkownika", 'style' => "profile"]);
  $this->view->render('user/profile');

}
```
Show user profile.
   
+ updateAction()
```
public function updateAction()
{
  if ($this->request->isPost()) {
      $update = $this->request->postParam('update');

      switch ($update) {
          case 'username':{
                  $this->updateUsername();
                  break;
              }
          case 'password':{
                  $this->updatePassword();
                  break;
              }
          case 'avatar':{
                  $this->updateAvatar();
                  break;
              }
      }
  }

  $this->redirect(self::$route->get('user.profile'));
}
```
<b>POST: </b> Select method which data will be updated by post param(update), next redirect to user profile.
   
+ updateUsername()
```
private function updateUsername()
{
  if ($this->request->hasPostName('username')) {
      $data = ['username' => $this->request->postParam('username')];

      if ($this->validate($data, $this->rules)) {
          $this->user->update($data);
          $this->userRepository->update($this->user, 'username');
          Session::set('success', "The username has been changed");
      }
  }
}
```
<b>POST: </b> Validate username given by user and set new username.

+ updatePassword()
```
private function updatePassword()
{
  $names = ['current_password', 'password', 'repeat_password'];

  if ($this->request->hasPostNames($names)) {
      $data = $this->request->postParams($names);

      if (!$same = ($this->user->password == $this->hash($data['current_password']))) {
          Session::set("error:current_password:same", "The password provided is incorrect");
      }

      if ($this->validate($data, $this->rules) && $same) {
          $data['password'] = $this->hash($data['password']);
          $this->user->update($data);
          $this->userRepository->update($this->user, 'password');
          Session::set('success', 'The password has been updated');
      }
  }
}
```
<b>POST: </b> Validate data given by user and set new password.

+ updateAvatar()
```
private function updateAvatar(): void
{
  $path = self::$config->get('upload.path.avatar');
  $defaultAvatar = self::$config->get('default.path.avatar');

  if ($file = $this->request->file('avatar')) {
      if ($this->validateImage($file, $this->rules, 'avatar')) {
          $file = $this->hashFile($file);

          if ($this->uploadFile($path, $file)) {
              if ($this->user->avatar != $defaultAvatar) {
                  $this->user->deleteAvatar();
              }

              $this->user->update(['avatar' => $path . $file['name']]);
              $this->userRepository->update($this->user, 'avatar');
              Session::set('success', 'Avatar has been updated');
          }
      }
  }
}
```
<b>POST: </b> Validate image sent by user. If validate is ok, old avatar is deleted and new avatar is uploaded.
</details>

<details>
   <summary>GeneralController</summary>

+ homeAction()
```
public function homeAction()
{
  View::set(['title' => "Home"]);
  $this->view->render('general/home');
}
```
Show home page.

+ policyAction()
```
public function policyAction()
{
  View::set(['title' => "privacy policy"]);
  $this->view->render('general/policy');
}
```
Show policy page.

+ regulationsAction()
```
public function regulationsAction()
{
  View::set(['title' => "Regulations"]);
  $this->view->render('general/regulations');
}
```
Show regulations page.
   
+ contactAction()
```
public function contactAction()
{
  View::set(['title' => "Contact page", 'style' => "contact"]);
  $names = ['name', 'from', 'message', 'subject', 'g-recaptcha-response'];

  if ($this->request->isPost() && $this->request->hasPostNames($names)) {
      $secret = self::$config->get('reCAPTCHA.key.secret');
      $response = null;
      $reCaptcha = new \ReCaptcha($secret);

      $data = $this->request->postParams($names);

      $response = $reCaptcha->verifyResponse(
          $_SERVER["REMOTE_ADDR"],
          $data['g-recaptcha-response']
      );

      if ($response != null && $response->success) {
          if (Mail::contact($data)) {
              Session::set('success', "Message was sent");
          }
      } else {
          Session::set('error:reCAPTCHA:robot', "We don't let robots in");
      }

      $this->redirect(self::$route->get('contact'));
  }

  $path = self::$config->get('default.path.medium') ?? "";
  $this->view->render('general/contact', ['path' => $path, 'sideKey' => self::$config->get('reCAPTCHA.key.side')]);
}
```
<b>GET: </b> Show contant form. <br>
<b>POST: </b> Send message to website admin.
</details>


### How to create new controller
1. Create new file in src/controller/ with name like a **NameController.php**
2. Example controller file:
 ```
<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Request;
use App\Helper\Session;
use App\Repository\NameRepository;
use App\Rules\NameRules;
use App\View;

class NameController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireLogin();
        $this->rules = new NameRules(); // Here is object of rules to validate data
    }
   
   public function methodOneAction( ... ) { ... }
}
```

## Validator
Validator is use to validate data given by user.

We can validate data with the following rules: 
1. **min** and **max** for length of input string
2. **validate** and **sanitize** for adress email
3. **require** to check if the field is not empty
4. **specialCharacters** to check if string have special characters

We can also validate images with the following rules:
1. **maxSize** to limited max size of image
2. **types** to check if sent image have extension like a (.png, .jpg etc.)

### How validate data ( in NameController )
```
public function methodOneAction()
{
  if ($this->request->hasPostName('username')) {
      $data = ['username' => $this->request->postParam('username')];

      if ($this->validate($data, $this->rules)) {
         // ... OK ...
      }
      else {
         // ... NOT OK ...
      }
  }
}
```

## Repositories
Repositories are a collection of methods to communicate with database.

### Basic repositories
<details>
   <summary>Repository</summary>
   
  + initConfiguration($config): void
```
public static function initConfiguration($config): void
{
  self::$config = $config;
}
```
Initialize properties such as config.

+ __construct()
```
public function __construct()
{
  try {
      $this->validateConfig(self::$config);
      $this->createConnection(self::$config);
  } catch (PDOException $e) {
      throw new StorageException('Connection error');
  }
}
```
Config data are validate and next is created connection to database.
   
+ createConnection(array $config): void
```
private function createConnection(array $config): void
{
  $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
  $this->pdo = new PDO($dsn, $config['user'], $config['password'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ]);
}
```
Create  connection to database.
   
+ validateConfig(array $config): void
```
private function validateConfig(array $config): void
{
  if (
      empty($config['database']) ||
      empty($config['host']) ||
      empty($config['user']) ||
      !isset($config['password'])
  ) {
      throw new ConfigurationException('Storage configuration error');
  }
}
```
Validate config data.   
</details>

<details>
   <summary>AuthRepository</summary>
   
+ register(User $user): void
```
public function register(User $user): void
{
  try {
      $data = [
          'username' => $user->username,
          'email' => $user->email,
          'password' => $user->password,
          'avatar' => $user->avatar,
          'role' => "user",
          'created' => date('Y-m-d H:i:s'),
      ];

      $sql = "INSERT INTO users (username, email, password, avatar, role, created) VALUES (:username, :email, :password, :avatar, :role, :created)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
  } catch (Throwable $e) {
      throw new StorageException('Failed to create a new account', 400, $e);
  }
}
```
Add new user to database.
   
+ login(string $email, string $password): ?int
```
public function login(string $email, string $password): ?int
{
  $id = null;
  $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email=:email AND password=:password");
  $stmt->execute([
      'email' => $email,
      'password' => $password,
  ]);

  $data = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($data) {$id = (int) $data['id'];}
  return $id;
}
```
Return id user with ($email | $password) data.
   
+ getEmails(): array
```
public function getEmails(): array
{
  $stmt = $this->pdo->prepare("SELECT email FROM users");
  $stmt->execute();
  $emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 'email');
  return $emails;
}
```
Return array of emails from user table.  
</details>

===

<details>
   <summary>UserRepository</summary>
   
+ get($param, $type = "id"): ?User
```
public function get($param, $column = "id"): ?User
{
  $user = null;
  $stmt = $this->pdo->prepare("SELECT * FROM users WHERE $column=:$column");
  $stmt->execute([$column => $param]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($data) {$user = new User($data);}
  return $user;
}
```
Return user by param(value) and type(id | email).
   
+ update(User $user, string $property): void
```
public function update(User $user, string $property): void
{
  $user->escape();
  $data = $user->getArray(['id', $property]);
  $sql = "UPDATE users SET $property=:$property WHERE id=:id";
  $stmt = $this->pdo->prepare($sql);

  $stmt->execute($data);
}
```
Return id of user by ($email and $password) data.
   
</details>

### How to create new repository
1. Create new file in src/repository/ with name like a **NameRepository.php**
2. Example repository file:
```
<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\Product;
use App\Repository\Repository;
use PDO;

class ProductRepository extends Repository
{
   public function getById($id): Product
    {
        $product = null;
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE $id=:$id");
        $stmt->execute(["id" => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {$product = new Product($data);}
        return $user;
    }
}
```

## Routing
### How create new routing
Register a new group in `routes/routes.php`
```
$route->group('name', ['one', 'two', 'three']);
```

example:
```
$route->group('user', ['logout', 'profile', 'update']);
```

### How use created routing
Inside of method in NameController
```
$this->redirect(self::$route->get('name.action'), ['param' => $value, 'param2' => $value2]);
```
example: 
```
$this->redirect(self::$route->get('auth.login'), ['email' => $this->user->email]);
```

## Helpers
### Session
### Request

## Component
## View




