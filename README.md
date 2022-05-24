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

# IN PROGRESS

### Rules
Class `./src/model/rules` is created to define validate rules for data given by user.
+ **createRules(string type, array rules)** Add rule to property rules.
+ **createMessages(string type, array rules)** Add error messages to rule.
+ **value(?string name = null)** Return value of rules.
+ **message(?string name = null**) Return messages of rules.
+ **arrayValue(string name, bool uppercase = false)** Return array value of rules as string. Value of rule name must be array.
+ **hasType(string type)** Check if exists input type.
+ **selectType(string type)** Set selectedType.
+ **clearType()** Set selectedType on null.
+ **typeHasKeys(array keys, ?string type = null)** Check if type of rule has all input keys.

#### How to create rule
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

### Validator

### 

### How create
#### How create new Controller
#### How create new Model
#### How create new Rules
#### How works route
### Helpers
#### Session
#### Request
### Repository
### Component
### View




