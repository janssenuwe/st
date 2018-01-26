<?php
namespace Auth\Controller;

use Application\Util\MailUtil;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\TableGateway\TableGateway;

use Auth\Model\Auth;
use Auth\Form\RegistrationForm;
use Auth\Form\RegistrationFilter;

use Auth\Form\CompleteRegistrationForm;
use Auth\Form\CompleteRegistrationFilter;

use Auth\Form\ForgottenPasswordForm;
use Auth\Form\ForgottenPasswordFilter;
use CsnBase\Zend\Validator\ConfirmPassword;

/**
*   Monitors every Action, which handles Registration, Forgotten Passwort and Confirmation
*/  
class RegistrationController extends AbstractActionController
{

    // Those consts should go into an utility class
    const HOST_AND_DOMAIN_NAME = 'localhost.aicovo.com';
    const LOCAL_IP = '127.0.0.1';
    const MAIL_PORT = 25;

    protected $usersTable;

    public function indexAction()
    {
        // Creates a new Registration Form
        $form = new RegistrationForm();
        // Sets the value of the Submit Button
        $form->get('user_submit')->setValue('Registrieren');
        // Gets the Request Method
        $request = $this->getRequest();

        if ($request->isPost()) {
            // Sets an InputFilter on the Registration Form
            $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // Gets the Data from the Form
                $data = $form->getData();

                // Crypt pass with SHA1
                $data['user_passwort_nocrypt'] = $data['user_passwort'];
                $data['user_passwort'] = sha1($data['user_passwort']);

                // Creates an Auth Model
                $auth = new Auth();
                $auth->exchangeArray($data);
                // Unsets the submit-value, agb-value and datenschutz-value because these values will not be saved into the Database
                unset($data['user_submit']);
                unset($data['user_agb']);
                unset($data['user_datenschutz']);

                foreach ($data as $key => $value)
                    $data[$key] = utf8_decode($value);

                // Creates an unique id, so send as id for the confirmation link
                $uniqid = uniqid();
                // Inserts the User into the Database
                $this->getUsersTable()->insert($data);
                // Inserts the generated id into the user_hasid column
                $this->getUsersTable()->update(array('user_hashid' => $uniqid), array('user_email' => $data['user_email']));

                // Send an Email to User that gives him the possibility to confirm his email
                /** @var $strEmail string */
                // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
                // aus diesem scope hier verwendet werden
                include_once("public/mails/registerEmail.php");
                // $strEmail is defined inside include file

                try {
                    MailUtil::sendMail(
                        $this->getServiceLocator(),
                        $auth->user_email,
                        $strEmail,
                        'Bitte bestätigen Sie Ihre E-Mail-Adresse'
                    );
                } catch (\Exception $ex) {
                    echo "<pre> Mail Problem: ";
                    var_dump("$ex");
                    die();
                    echo '<meta http-equiv="refresh" content="0; url=http://www.stellenanzeigen-texten.de/check/public/auth/registration/registration-error">';
                    exit;
                }

                echo '<meta http-equiv="refresh" content="0; url=http://www.stellenanzeigen-texten.de/check/public/auth/registration/registration-success">';
                exit;
                // Redirects the User to the 'Registration-Success' page
                return $this->redirect()->toRoute('auth/default', array('controller' => 'registration', 'action' => 'registration-success'));
            }
        }
        //      Returns an empty Registration Form

        $form->prepare();

        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function registrationSuccessAction()
    {
        $user_email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach ($flashMessenger->getMessages() as $key => $value) {
                $user_email .= $value;
            }
        }
//      Returns an Registration Success View
        $viewModel = new ViewModel(array('user_email' => $user_email));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function registrationErrorAction()
    {
        $user_email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach ($flashMessenger->getMessages() as $key => $value) {
                $user_email .= $value;
            }
        }
//      Returns an Registration Success View
        $viewModel = new ViewModel(array('user_email' => $user_email));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function confirmEmailAction()
    {
        // Gets the unique id that is given with the url
        $token = $this->params()->fromRoute('id');
        $form = new CompleteRegistrationForm();
        $viewModel = new ViewModel(array('token' => $token, 'form' => $form));


        $viewModel->setTerminal(true);

        // Sets the value of the Submit Button
        $form->get('user_submit')->setValue('Registrieren vervollständigen');

        try {
            $request = $this->getRequest();

            // Selects the User by his unique id
            $user = $this->getUsersTable()->select(array('user_hashid' => $token));


            // Selects the first row
            $objUserIdenCurrent = $user->current();

            // Check if Token is Null
            if (!$objUserIdenCurrent) {
                $viewModel->setTemplate('auth/registration/confirm-email-error.phtml');
                return $viewModel;
            }

            $viewModel->setVariable('isActiv', ($objUserIdenCurrent->user_confirmed ? true : false));
            $viewModel->setVariable('objUser', $objUserIdenCurrent);


            if ($request->isPost()) {
                // Sets an InputFilter on the Registration Form
                $form->setInputFilter(new CompleteRegistrationFilter($this->getServiceLocator()));
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    // Gets the Data from the Form
                    $data = $form->getData();

                    // Unset Submit Button & AGB
                    unset($data['user_submit']);
                    unset($data['user_agb']);
                    unset($data['user_datenschutz']);

                    // Selects the User by his unique id
                    $user = $this->getUsersTable()->select(array('user_hashid' => $token));

                    // Selects the first row
                    $objUserIdenCurrent = $user->current();

                    foreach ($data as $key => $value)
                        $data[$key] = utf8_decode($value);

                    if (!$objUserIdenCurrent->user_confirmed) {
                        // Update the User in Database

                        $this->getUsersTable()->update($data, array('user_hashid' => $token));
                        $user = $this->getUsersTable()->select(array('user_hashid' => $token));
                        $objUserIdenCurrent = $user->current();

                        /** @var $strEmail string */
                        // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
                        // aus diesem scope hier verwendet werden
                        include_once("public/mails/confirmRegisterEmail.php");
                        // $strEmail is defined inside include file
                        MailUtil::sendMail(
                            $this->getServiceLocator(),
                            $objUserIdenCurrent->user_email,
                            $strEmail,
                            'Ihre Registrierung ist abgeschlossen'
                        );

                        /** @var $strEmail string */
                        // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
                        // aus diesem scope hier verwendet werden
                        include_once("public/mails/newUserNotification.php");
                        // $strEmail is defined inside include file
                        // send new user notification e-mail
                        MailUtil::sendMail(
                            $this->getServiceLocator(),
                            "axel.haitzer@aicovo.com",
                            $strEmail,
                            'Neue Registrierung für die TOOLBOX für Stellenanzeigen',
                            "uwe.janssen@aicovo.com"
                        );

                        // Sets the column 'confirmed' in the Database to 1
                        $this->getUsersTable()->update(array('user_confirmed' => '1', 'user_passwort_nocrypt' => ''), array('user_hashid' => $token));
                    }

                    // Add User to AcyMail
                    $objMySQLi = mysqli_connect('dedi1612.your-server.de', 'aicovz_1', 'iZnJ2ERyBtn5uyC6', 'aicovz_db1');

                    $objResult = $objMySQLi->query("SELECT `subid` FROM `uwvy_acymailing_subscriber` WHERE `email` = '" . $objUserIdenCurrent->user_email . "';");
                    $objResult = $objResult->fetch_array(MYSQLI_ASSOC);

                    if (is_null($objResult)) {
                        $strInsertSQL = "INSERT INTO
                                               `uwvy_acymailing_subscriber`
                                           SET
                                               `email`					= '" . $objUserIdenCurrent->user_email . "',
                                               `name`					= '" . $objUserIdenCurrent->user_nachname . "',
                                               `created`				= '" . time() . "',
                                               `confirmed`				= '1',
                                               `ip`					    = '" . $_SERVER['REMOTE_ADDR'] . "',
                                               `confirmed_date`		    = '" . time() . "',
                                               `confirmed_ip`			= '" . $_SERVER['REMOTE_ADDR'] . "',
                                               `source`				    = '',
                                               `anrede`				    = '" . $objUserIdenCurrent->user_anrede . "',
                                               `titel`					= '" . $objUserIdenCurrent->user_titel . "',
                                               `vorname`				= '" . $objUserIdenCurrent->user_vorname . "',
                                               `firma`					= '" . $objUserIdenCurrent->user_firmenname . "',
                                               `ort`					= '" . $objUserIdenCurrent->user_ort . "'
                                               ;";
                        mysqli_query($objMySQLi, $strInsertSQL);


                        $strInsertSQL = "INSERT INTO
                                               `uwvy_acymailing_listsub`
                                           SET
                                               `listid`				    = '4',
                                               `subid`				    = '" . mysqli_insert_id($objMySQLi) . "',
                                               `subdate`				= '" . time() . "',
                                               `status`				    = '1'
                                               ;";
                        mysqli_query($objMySQLi, $strInsertSQL);
                        mysqli_close($objMySQLi);
                    }

                    echo '<meta http-equiv="refresh" content="0; url=http://www.stellenanzeigen-texten.de/check/public/auth/index/login/success">';
                    exit;
                    // Redirects the User to the 'Login' page
                    return $this->redirect()->toRoute('auth/default', array('controller' => 'index', 'action' => 'login', 'id' => 'success'));
                }
            }
        } catch (\Exception $e) {
            echo "<pre>";
            print_r($e);
            exit;
            $viewModel->setTemplate('auth/registration/confirm-email-error.phtml');
        }
        return $viewModel;
    }

    /**
     *   Handles the Action, which sends the User a new Password
     */
    public function forgottenPasswordAction()
    {
//      Creates a new Form
        $form = new ForgottenPasswordForm();
        $form->get('submit')->setValue('Senden');
        $request = $this->getRequest();
        if ($request->isPost()) {
//          Sets an InputFilter on the Form
            $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
//              Gets the Forms typed Data 
                $data = $form->getData();
//              Gets the Forms Email Adress
                $user_email = $data['user_email'];

                $user = $this->getUsersTable()->select(array('user_email' => $user_email));

//              Generates a 8-digit password
                $password = $this->generatePassword();

//              Updates the Users password in the database    
                $this->getUsersTable()->update(array('user_passwort' => sha1($password)), array('user_email' => $user_email));
//              Sends an Email to the User with the new password

                $user = $user->current();
                /** @var $strEmail string */
                // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
                // aus diesem scope hier verwendet werden
                include_once("public/mails/forgottenPassword.php");
                // $strEmail is defined inside include file

                MailUtil::sendMail($this->getServiceLocator(),
                    $user->user_email,
                    $strEmail,
                    'Ihr Passwort wurde geändert');

                echo '<meta http-equiv="refresh" content="0; url=http://www.stellenanzeigen-texten.de/check/public/auth/registration/password-change-success">';
                exit;
                return $this->redirect()->toRoute('auth/default', array('controller' => 'registration', 'action' => 'password-change-success'));
            }
        }

//      Returns a new View
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     *   Handles the Action, which shows that the Password was changed successfully
     */
    public function passwordChangeSuccessAction()
    {
//      Defines a variable to save the user email    
        $user_email = null;

//      Returns a new View
        $viewModel = new ViewModel(array('user_email' => $user_email));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     *   Generates a 8-digit random password
     */
    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
        // get count of all required minimum special chars
        $count = $c + $n + $s;
        $out = '';
        // sanitize inputs; should be self-explanatory
        if (!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        } elseif ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        } elseif ($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }

        // all inputs clean, proceed to build password

        // change these strings if you want to include or exclude possible password characters
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";

        // build the base password of all lower-case letters
        for ($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        // create arrays if special character(s) required
        if ($count) {
            // split base password to array; create special chars array
            $tmp1 = str_split($out);
            $tmp2 = array();

            // add required special character(s) to second array
            for ($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            for ($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            for ($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            // hack off a chunk of the base password array that's as big as the special chars array
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            // merge special character(s) array with base password array
            $tmp1 = array_merge($tmp1, $tmp2);
            // mix the characters up
            shuffle($tmp1);
            // convert to string for output
            $out = implode('', $tmp1);
        }

        return $out;
    }

    /**
     *   Selects all Data from the User-Table from the Database
     */
    public function getUsersTable()
    {
//      Checks if usersTable has got a selection 
        if (!$this->usersTable) {
//          Creates a Tablegateway for the 'users' table in the Database                    
            $this->usersTable = new TableGateway(
                'users',
                $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
            );
        }
        return $this->usersTable;
    }
}