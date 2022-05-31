# PHP Start
The project is a complete file package to create applications in PHP technology.

### Build with
1. PHP 7.4

### Features
1. Registration / Login
2. Password recovery
3. User profile management (username / photo / password)

### Installation Instructions
1. Run `git clone https://github.com/dawidbros1/php-start.git`
2. Run `componser install`
3. Create a MySQL database for the project
4. From the project root folder run `cd .\config\` and next `rename config_dist.php config.php`
5. Configure your `./config/config.php` file
6. Import tables from file `./sql/database.sql` to your database

### Routes
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

### Tree directory
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

### Rules
Class `src/model/rules` is created to define validate rules.
+ **createRules(string $type, array $rules)** Add rule to property rules
+ **createMessages(string $type, array $rules)** Add error messages to rules of type
+ **value(?string $name = null)** Return value of rule
+ **message(?string $name = null**) Return messages of rule
+ **arrayValue(string $name, bool $uppercase = false)** Return array value of rules as string
+ **hasType(string $type)** Check if exists given type
+ **selectType(string $type)** Set selectedType on given type
+ **clearType()** Set selectedType on null
+ **typeHasRules(array $keys, ?string $type = null)** Check if type of rule has all given keys

#### How to create new rule
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

# IN PROGRESS

### Controllers
Controllers are designed to manage the entire application.

#### Basic controllers

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
         Session::set("error", "Strona, na którą próbowałeś się dostać, jest dostępna wyłącznie dla użytkowników nie zalogowanych.");
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
         Session::set("error", "Strona, na którą próbowałeś się dostać, wymaga zalogowania się");
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
         Session::set("error", "Nie posiadasz wystarczających uprawnień do akcji, którą chciałeś wykonać");
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
         Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
         return false;
     }
 }
```
Method upload file on server.

+ hash($param, $method = null)
```
protected function hash($param, $method = null)
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
   
   registerAction
   <b>GET: </b> Show register form. <br>
   <b>POST: </b> Validate data given by user. If data is validated, user is added to database.

   loginAction
   <b>GET: </b> Show login form. <br>
   <b>POST: </b>Action check if exist user with appropriate e-mail address and password.

   forgotPasswordAction
   <b>GET: </b> Show form to reset password. <br>
   <b>POST: </b> Send a message on address-email given from user with special code which is used to user
   authorize to reset password.

   resetPasswordAction
   <b>GET: </b> Show reset password form. <br>
   <b>POST: </b> Action check if code is sent and active, next set new password given by user.
</details>


<details>
   <summary>UserController</summary>

logoutAction
Logout user, clear session data.

profileAction
Show user profile.
   
   
updateAction
<b>POST: </b> Select method which data will be updated by post param(update), next redirect to user profile.
   
updateUsername
<b>POST: </b> Validate username given by user and set new username.

updatePassword
<b>POST: </b> Validate data given by user and set new password.

updateAvatar
<b>POST: </b> Validate image sent by user. If validate is ok, old avatar is deleted and new avatar is
uploaded.
</details>

<details>
   <summary>GeneralController</summary>

homeAction
Show home page.

policyAction
Show policy page.

regulationsAction
Show regulations page.
   
contactAction
<b>GET: </b> Show contant form. <br>
<b>POST: </b> Send message to website admin.
</details>


#### How to create new controller
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

### Validator
Validator is use to validate data given by user.

We can validate data with the following rules: 
1. **min** and **max** for length of input string
2. **validate** and **sanitize** for adress email
3. **require** to check if the field is not empty
4. **specialCharacters** to check if string have special characters

We can also validate images with the following rules:
1. **maxSize** to limited max size of image
2. **types** to check if sent image have extension like a (.png, .jpg etc.)

#### How validate data ( in NameController )
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

### Repositories
...
#### How to create new repository
1. Create new file in src/repository/ with name like a **NameRepository.php**
2. Example repository file:
```
<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\Name;
use App\Repository\Repository;
use PDO;

class NameRepository extends Repository
{
    public function methodOne( ... ) { ... }
    public function methodTwo( ... ) { ... }
}
```

### Routing
#### How create new routing
Register a new group in `./routes/routes.php`
```
$route->group('name', ['one', 'two', 'three']);
```

example:
```
$route->group('user', ['logout', 'profile', 'update']);
```

#### How use created routing
Inside of method in NameController
```
$this->redirect(self::$route->get('name.action'), ['param' => $value, 'param2' => $value2]);
```
example: 
```
$this->redirect(self::$route->get('auth.login'), ['email' => $this->user->email]);
```

### Helpers
#### Session
#### Request

### Component
### View




