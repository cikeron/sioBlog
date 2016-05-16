<?php

/**
 * Class OneFileLoginApplication
 *
 * An entire php application with user registration, login and logout in one file.
 * Uses very modern password hashing via the PHP 5.5 password hashing functions.
 * This project includes a compatibility file to make these functions available in PHP 5.3.7+ and PHP 5.4+.
 *
 * @author Panique
 * @link https://github.com/panique/php-login-one-file/
 * @license http://opensource.org/licenses/MIT MIT License
 */
class OneFileLoginApplication
{
    /**
     * @var string Type of used database (currently only SQLite, but feel free to expand this with mysql etc)
     */
    private $db_type = "sqlite"; //

    /**
     * @var string Path of the database file (create this with _install.php)
     */
    private $db_sqlite_path = "./login/users.db";

    /**
     * @var object Database connection
     */
    private $db_connection = null;

    /**
     * @var bool Login status of user
     */
    private $user_is_logged_in = false;

    /**
     * @var string System messages, likes errors, notices, etc.
     */
    public $feedback = "";
    //PAra mostrar el usuario logeado.
    public $usuario = "";

    /**
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct()
    {
        if ($this->performMinimumRequirementsCheck()) {
            $this->runApplication();
            //$this->feedback="Seguridad iniciada";
        }
    }

    /**
     * Performs a check for minimum requirements to run this application.
     * Does not run the further application when PHP version is lower than 5.3.7
     * Does include the PHP password compatibility library when PHP version lower than 5.5.0
     * (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
     * @return bool Success status of minimum requirements check, default is false
     */
    private function performMinimumRequirementsCheck()
    {
        if (version_compare(PHP_VERSION, '5.3.7', '<')) {
            echo "Lo siento pero no es posible continuar si la version de PHP es inferior a 5.3.7 !";
        } elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
            require_once("libraries/password_compatibility_library.php");
            return true;
        } elseif (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * This is basically the controller that handles the entire flow of the application.
     */
    public function runApplication()
    {
        // check is user wants to see register page (etc.)
        if (isset($_GET["action"]) && $_GET["action"] == "register") {
            $this->doStartSession();
            $this->performUserLoginAction();
            if ($this->getUserLoginStatus()){
            $this->doRegistration();
            $this->showPageRegistration2();
            //$this->feedback="OKKKKKKK";
            }

        } else {
            // start the session, always needed!

            $this->doStartSession();
            // check for possible user interactions (login with session/post data or logout)
            $this->performUserLoginAction();
            // show "page", according to user's login status
            if ($this->getUserLoginStatus()) {
                //$this->showPageLoggedIn();
                $this->usuario=$_SESSION['user_name'];

            } 
/*            elseif (isset($_GET["action"]) && $_GET["action"] == "iniciarsesion2") {
                $this->usuario="";
                $this->showPageLoginForm2();
            } 
*/            elseif (isset($_GET["action"]) && $_GET["action"] == "iniciarsesion") {
                $this->usuario="";
                $this->showPageLoginForm();

            }

        }
            // start the session, always needed!
    }

    /**
     * Creates a PDO database connection (in this case to a SQLite flat-file database)
     * @return bool Database creation success status, false by default
     */
    private function createDatabaseConnection()
    {
        try {
            $this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);
            return true;
        } catch (PDOException $e) {
            $this->feedback = "PDO database connection problem: " . $e->getMessage();
        } catch (Exception $e) {
            $this->feedback = "General problem: " . $e->getMessage();
        }
        return false;
    }

    /**
     * Handles the flow of the login/logout process. According to the circumstances, a logout, a login with session
     * data or a login with post data will be performed
     */
    private function performUserLoginAction()
    {
        if (isset($_GET["action"]) && $_GET["action"] == "logout") {
            $this->doLogout();
        } elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])) {
            $this->doLoginWithSessionData();
        } elseif (isset($_POST["login"])) {
            $this->doLoginWithPostData();
        }
    }

    /**
     * Simply starts the session.
     * It's cleaner to put this into a method than writing it directly into runApplication()
     */
    private function doStartSession()
    {
        session_start();
    }

    /**
     * Set a marker (NOTE: is this method necessary ?)
     */
    private function doLoginWithSessionData()
    {
        $this->user_is_logged_in = true; // ?
    }

    /**
     * Process flow of login with POST data
     */
    private function doLoginWithPostData()
    {
        if ($this->checkLoginFormDataNotEmpty()) {
            if ($this->createDatabaseConnection()) {
                $this->checkPasswordCorrectnessAndLogin();
            }
        }
    }

    /**
     * Logs the user out
     */
    private function doLogout()
    {
        $_SESSION = array();
        session_destroy();
        $this->user_is_logged_in = false;
        $this->feedback = "Has cerrado la sesion.";
    }

    /**
     * The registration flow
     * @return bool
     */
    private function doRegistration()
    {
        if ($this->checkRegistrationData()) {
            if ($this->createDatabaseConnection()) {
                $this->createNewUser();
            }
        }
        // default return
        return false;
    }

    /**
     * Validates the login form data, checks if username and password are provided
     * @return bool Login form data check success state
     */
    private function checkLoginFormDataNotEmpty()
    {
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
            return true;
        } elseif (empty($_POST['user_name'])) {
            $this->feedback = "El nombre de usuario esta vacio.";
        } elseif (empty($_POST['user_password'])) {
            $this->feedback = "Password vacio.";
        }
        // default return
        return false;
    }

    /**
     * Checks if user exits, if so: check if provided password matches the one in the database
     * @return bool User login success status
     */
    private function checkPasswordCorrectnessAndLogin()
    {
        // remember: the user can log in with username or email address
        $sql = 'SELECT user_name, user_email, user_password_hash
                FROM users
                WHERE user_name = :user_name OR user_email = :user_name
                LIMIT 1';
        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':user_name', $_POST['user_name']);
        $query->execute();

        // Btw that's the weird way to get num_rows in PDO with SQLite:
        // if (count($query->fetchAll(PDO::FETCH_NUM)) == 1) {
        // Holy! But that's how it is. $result->numRows() works with SQLite pure, but not with SQLite PDO.
        // This is so crappy, but that's how PDO works.
        // As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
        // If you meet the inventor of PDO, punch him. Seriously.
        $result_row = $query->fetchObject();
        if ($result_row) {
            // using PHP 5.5's password_verify() function to check password
            if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
                // write user data into PHP SESSION [a file on your server]
                $_SESSION['user_name'] = $result_row->user_name;
                $_SESSION['user_email'] = $result_row->user_email;
                $_SESSION['user_is_logged_in'] = true;
                $this->user_is_logged_in = true;
                return true;
            } else {
                $this->feedback = "Algo esta equivocado.";
            }
        } else {
            $this->feedback = "Algo esta equivocado";
        }
        // default return
        return false;
    }

    /**
     * Validates the user's registration input
     * @return bool Success status of user's registration data validation
     */
    private function checkRegistrationData()
    {
        // if no registration form submitted: exit the method
        if (!isset($_POST["register"])) {
            return false;
        }

        // validating the input
        if (!empty($_POST['user_namereg'])
            && strlen($_POST['user_namereg']) <= 64
            && strlen($_POST['user_namereg']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_namereg'])
            && !empty($_POST['user_emailreg'])
            && strlen($_POST['user_emailreg']) <= 64
            && filter_var($_POST['user_emailreg'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_newreg'])
            && !empty($_POST['user_password_repeatreg'])
            && ($_POST['user_password_newreg'] === $_POST['user_password_repeatreg'])
        ) {
            // only this case return true, only this case is valid
            return true;
        } elseif (empty($_POST['user_namereg'])) {
            $this->feedback = "Empty Username";
        } elseif (empty($_POST['user_password_newreg']) || empty($_POST['user_password_repeatreg'])) {
            $this->feedback = "Empty Password";
        } elseif ($_POST['user_password_newreg'] !== $_POST['user_password_repeatreg']) {
            $this->feedback = "Los passwords no coinciden. Lo sentimos, intentelo de nuevo.<br>";
        } elseif (strlen($_POST['user_password_newreg']) < 6) {
            $this->feedback = "Password has a minimum length of 6 characters";
        } elseif (strlen($_POST['user_namereg']) > 64 || strlen($_POST['user_namereg']) < 2) {
            $this->feedback = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_namereg'])) {
            $this->feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (empty($_POST['user_emailreg'])) {
            $this->feedback = "Email cannot be empty";
        } elseif (strlen($_POST['user_emailreg']) > 64) {
            $this->feedback = "Email cannot be longer than 64 characters";
        } elseif (!filter_var($_POST['user_emailreg'], FILTER_VALIDATE_EMAIL)) {
            $this->feedback = "Your email address is not in a valid email format";
        } else {
            $this->feedback = "An unknown error occurred.<br>";
        }

        // default return
        return false;
    }

    /**
     * Creates a new user.
     * @return bool Success status of user registration
     */
    private function createNewUser()
    {
        // remove html code etc. from username and email
        $user_name = htmlentities($_POST['user_namereg'], ENT_QUOTES);
        $user_email = htmlentities($_POST['user_emailreg'], ENT_QUOTES);
        $user_password = $_POST['user_password_newreg'];
        // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 char hash string.
        // the constant PASSWORD_DEFAULT comes from PHP 5.5 or the password_compatibility_library
        $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

        $sql = 'SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email';
        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':user_name', $user_name);
        $query->bindValue(':user_email', $user_email);
        $query->execute();

        // As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
        // If you meet the inventor of PDO, punch him. Seriously.
        $result_row = $query->fetchObject();
        if ($result_row) {
            $this->feedback = "Lo Sentimos, nombre o email ya usado. Por favor escoge otro.<br>";
        } else {
            $sql = 'INSERT INTO users (user_name, user_password_hash, user_email)
                    VALUES(:user_name, :user_password_hash, :user_email)';
            $query = $this->db_connection->prepare($sql);
            $query->bindValue(':user_name', $user_name);
            $query->bindValue(':user_password_hash', $user_password_hash);
            $query->bindValue(':user_email', $user_email);
            // PDO's execute() gives back TRUE when successful, FALSE when not
            // @link http://stackoverflow.com/q/1661863/1114320
            $registration_success_state = $query->execute();

            if ($registration_success_state) {
                $this->feedback = "Your account has been created successfully. You can now log in.<br>";
                return true;
            } else {
                $this->feedback = "Lo sentimos pero el proceso ha FALLADO. Por favor intentelo de nuevo.<br>";
            }
        }
        // default return
        return false;
    }

    /**
     * Simply returns the current status of the user's login
     * @return bool User's login status
     */
    public function getUserLoginStatus()
    {
        return $this->user_is_logged_in;
    }

    /**
     * Simple demo-"page" that will be shown when the user is logged in.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    private function showPageLoggedIn()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/>";
        }

        echo 'Hola ' . $_SESSION['user_name'] . ', has iniciado sesion correctamente.<br/><br/>';
        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?p=mayca&action=logout">Salir de la sesión</a>';
    }

    /**
     * Simple demo-"page" with the login form.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    //ESTO ES PRIVVATE EN EL ORIGINAL
    private function showPageLoginForm()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }

        echo '<h2>Login</h2>';

        echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="loginform">';
        echo '<label for="login_input_username">Usuario (o email): </label> ';
        echo '<input id="login_input_username" type="text" name="user_name" required /> ';
        echo '<br>';
        echo '<label for="login_input_password">Password: </label> ';
        echo '<input id="login_input_password" type="password" name="user_password" required /> ';
        echo '<input type="submit"  name="login" value="Entrar" />';
        echo '</form>';

        //echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?p=mayca&action=register">Register new account</a>';
    }


    public function showPageLoginForm2($direccion)
    { 
        echo'
        <div id="loginbox" style="margin-top:10px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel block-unit" >
                    <div style="padding-top:10px" class="panel-body" >
                        <img src="images/personal-256.png" alt="" class="img-circle">
                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="loginform" class="form-horizontal" role="form" method="post" action="' . $direccion . '" name="loginform">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login_input_username" type="text" class="form-control" name="user_name" value="" placeholder="usuario o email">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login_input_password" type="password" class="form-control" name="user_password" placeholder="password" required>
                                    </div>

                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-12 controls">
                                      <input type="submit" class="btn btn-info" name="login" value="Entrar" />
                                    </div>
                                </div>
                            </form>     

                        </div>
                    </div>  
        </div>
        ';
    }

    /**
     * Simple demo-"page" with the registration form.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    public function showPageRegistration2()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }
//        echo '<style type="text/css">.btn {background-color: #274569;}</style>';
        echo '<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Registro de nuevo usuario</div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>';

        echo '<form method="post" class="form-horizontal" role="form" action="' . $_SERVER['SCRIPT_NAME'] . '?action=register" name="registerform">';
        echo '<div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login_input_username" type="text" class="form-control" pattern="[a-zA-Z0-9]{2,64}" name="user_namereg" value="" placeholder="Usuario (Solo letras y numeros, 2 a 64 letras)" required />
                                    </div>';
        echo '<div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                        <input id="login_input_email" type="email" class="form-control" name="user_emailreg" value="" placeholder="Direccion email" required />
                                    </div>';
        echo '<div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-certificate"></i></span>
                                        <input id="login_input_password_new" type="password" pattern=".{6,}" class="form-control" name="user_password_newreg" value="" placeholder="Password (min. 6 caracteres)" required autocomplete="off" />
                                    </div>';
        echo '<div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-certificate"></i></span>
                                        <input id="login_input_password_repeat" type="password" pattern=".{6,}" class="form-control" name="user_password_repeatreg" value="" placeholder="Repite password" required autocomplete="off" />
                                    </div>';
        echo '                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-12 controls">
                                      <input type="submit" class="btn btn-info" name="register" value="Registrar" />
                                    </div>
                                </div>';
        echo '                                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        </div>';
        echo '</form>';

        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '">Volver al inicio.</a>';
        echo '   </div>
                    </div>
        </div>';
    }


    public function showPageRegistration()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }

        echo '<h2>Registro de usuario nuevo:</h2>';

        echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?action=register" name="registerform">';
        echo '<label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>';
        echo '<input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_namereg" required />';
        echo '<label for="login_input_email">User\'s email</label>';
        echo '<input id="login_input_email" type="email" name="user_emailreg" required />';
        echo '<label for="login_input_password_new">Password (min. 6 characters)</label>';
        echo '<input id="login_input_password_new" class="login_input" type="password" name="user_password_newreg" pattern=".{6,}" required autocomplete="off" />';
        echo '<label for="login_input_password_repeat">Repeat password</label>';
        echo '<input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeatreg" pattern=".{6,}" required autocomplete="off" />';
        echo '<input type="submit" name="register" value="Register" />';
        echo '</form>';

        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '">Homepage</a>';
    }

}// END CLASS

// run the application
//$application = new OneFileLoginApplication();

?>