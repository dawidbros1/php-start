# PHP Start
The created project is a ready file package to create applications in PHP technology.

## Build with
• PHP 7.4 \
• PDO

## Features
• Registration / Login \
• Password recovery \
• User profile management (name / photo / password)

## Setup
• Download repozitorium \
• Create database (project) \
• Import database from file ./sql/database.sql

## DOCUMENTATION - IN PROGRESS

<!-- CONTROLLER -->

<details>
 <summary>Controller</summary>
 <ul>
  <li>
   <details>
    <summary>initConfiguration</summary>
    Initialize properties such as config and route.
   </details>
  </li>

  <li>
   <details>
    <summary>__construct</summary>
    Check connection with database. Initialize configuration in repozitory and mail.
    Get user if he is logged. Assigns an request class object to a property.
    Create object of view class and set to a property.
   </details>
  </li>

  <li>
   <details>
    <summary>run</summary>
    If given action exists run it else redirect to homePage with error message.
   </details>
  </li>

  <li>
   <details>
    <summary>redirect</summary>
    Redirect user to selected page with parameters.
   </details>
  </li>

  <li>
   <details>
    <summary>action</summary>
    Return action param from request.
   </details>
  </li>

  <li>
   <details>
    <summary>guest</summary>
    Method check if user is not logged in. Logged user is redirect to homePage with error message.
   </details>
  </li>

  <li>
   <details>
    <summary>requireLogin</summary>
    Method check if user is logged in. Guest is redirect to login page with error message.
   </details>
  </li>

  <li>
   <details>
    <summary>requireAdmin</summary>
    Method check if user is admin. Guest is redirect to login page with error message.
    User which is not admin is redirect to homePage with error message.
   </details>
  </li>

  <li>
   <details>
    <summary>uploadFile</summary>
    Method upload file on server.
   </details>
  </li>

  <li>
   <details>
    <summary>hash</summary>
    Method return hash of input param.
    If hash method isn't sent, selected is default hash method from config.
   </details>
  </li>

  <li>
   <details>
    <summary>hashFile</summary>
    Method create unique filename.
   </details>
  </li>
 </ul>
</details>

<!-- AUTH CONTROLLER -->

<details>
 <summary>AuthController</summary>
 <ul>
  <li>
   <details>
    <summary>registerAction</summary>
    <b>GET: </b> Show register form. <br>
    <b>POST: </b> Validate data given by user. If data is validated, user is added to database.
   </details>
  </li>

  <li>
   <details>
    <summary>loginAction</summary>
    <b>GET: </b> Show login form. <br>
    <b>POST: </b>Action check if exist user with appropriate e-mail address and password.
   </details>
  </li>

  <li>
   <details>
    <summary>forgotPasswordAction</summary>
    <b>GET: </b> Show form to reset password. <br>
    <b>POST: </b> Send a message on address-email given from user with special code which is used to user
    authorize to reset password.
   </details>
  </li>

  <li>
   <details>
    <summary>resetPasswordAction</summary>
    <b>GET: </b> Show reset password form. <br>
    <b>POST: </b> Action check if code is sent and active, next set new password given by user.
   </details>
  </li>
 </ul>
</details>

<!-- USER CONTROLLER -->

<details>
 <summary>UserController</summary>
 <ul>
  <li>
   <details>
    <summary>logoutAction</summary>
    Logout user, clear session data.
   </details>
  </li>

  <li>
   <details>
    <summary>profileAction</summary>
    Show user profile.
   </details>
  </li>

  <li>
   <details>
    <summary>updateAction</summary>
    <b>POST: </b> Select method which data will be updated by post param(update), next redirect to user profile.
   </details>
  </li>

  <li>
   <details>
    <summary>updateUsername</summary>
    <b>POST: </b> Validate username given by user and set new username.
   </details>
  </li>

  <li>
   <details>
    <summary>updatePassword</summary>
    <b>POST: </b> Validate data given by user and set new password.
   </details>
  </li>

  <li>
   <details>
    <summary>updateAvatar</summary>
    <b>POST: </b> Validate image sent by user. If validate is ok, old avatar is deleted and new avatar is uploaded.
   </details>
  </li>
 </ul>
</details>

<!-- GENERAL CONTROLLER -->

<details>
 <summary>GeneralController</summary>
 <ul>
  <li>
   <details>
    <summary>homeAction</summary>
    Show home page.
   </details>
  </li>

  <li>
   <details>
    <summary>policyAction</summary>
    Show policy page.
   </details>
  </li>

  <li>
   <details>
    <summary>regulationsAction</summary>
    Show regulations page.
   </details>
  </li>

  <li>
   <details>
    <summary>contactAction</summary>
    <b>GET: </b> Show contant form. <br>
    <b>POST: </b> Send message to website admin.
   </details>
  </li>
 </ul>
</details>
