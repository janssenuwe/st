<?php
namespace Auth\Controller;

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

use Zend\Mail\Message;
use Zend\Mail;

/**
*   Monitors every Action, which handles Registration, Forgotten Passwort and Confirmation
*/  
class RegistrationController extends AbstractActionController {
    
	protected $usersTable;	
	
    public function indexIframeAction() {
        //      Creates a new Registration Form
        $form = new RegistrationForm();
        //      Sets the value of the Submit Button
        $form->get('user_submit')->setValue('Registrieren');
        //      Gets the Request Method
        $request = $this->getRequest();
        if ($request->isPost()) {
            //          Sets an InputFilter on the Registration Form
            $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //              Gets the Data from the Form
                $data = $form->getData();
                
                // Crypt pass with SHA1
                $data['user_passwort'] = sha1($data['user_passwort']);
                
                //              Creates an Auth Model
                $auth = new Auth();
                $auth->exchangeArray($data);
                //              Unsets the submit-value, agb-value and datenschutz-value because these values will not be saved into the Database
                unset($data['user_submit']);
                unset($data['user_agb']);
                unset($data['user_datenschutz']);
                //              Creates an unique id, so send as id for the confirmation link
                $uniqid = uniqid();
                //              Inserts the User into the Database
                $this->getUsersTable()->insert($data);
                //              Inserts the generated id into the user_hasid column
                $this->getUsersTable()->update(array('user_hashid' => $uniqid), array('user_email' => $data['user_email']));
                //              Send an Email to User that gives him the possibility to confirm his email
                $this->sendConfirmationEmail($auth, $uniqid);
                //              Redirects the User to the 'Registration-Success' page
                return $this->redirect()->toRoute('auth/default', array('controller'=>'registration', 'action'=>'registration-successiframe'));
            }
        }
        //      Returns an empty Registration Form

        $form->prepare();

        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
/**
*   Handles the Action, which allows the user to register
*/
	public function indexAction() {
//      Creates a new Registration Form
        $form = new RegistrationForm();
//      Sets the value of the Submit Button        
        $form->get('user_submit')->setValue('Registrieren');

//      Gets the Request Method        
        $request = $this->getRequest();
        if ($request->isPost()) {
//          Sets an InputFilter on the Registration Form          
            $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));            
            $form->setData($request->getPost());
             if ($form->isValid()) {
//              Gets the Data from the Form                 
                $data = $form->getData();
                
                // Crypt pass with SHA1
                $data['user_passwort'] = sha1($data['user_passwort']);
                
//              Creates an Auth Model
                $auth = new Auth();                
                $auth->exchangeArray($data);
//              Unsets the submit-value, agb-value and datenschutz-value because these values will not be saved into the Database                
                unset($data['user_submit']);
                unset($data['user_agb']);
                unset($data['user_datenschutz']);
//              Creates an unique id, so send as id for the confirmation link
                $uniqid = uniqid();
//              Inserts the User into the Database                
                $this->getUsersTable()->insert($data);
//              Inserts the generated id into the user_hasid column
                $this->getUsersTable()->update(array('user_hashid' => $uniqid), array('user_email' => $data['user_email']));
//              Send an Email to User that gives him the possibility to confirm his email
                $this->sendConfirmationEmail($auth, $uniqid);
//              Redirects the User to the 'Registration-Success' page
                return $this->redirect()->toRoute('auth/default', array('controller'=>'registration', 'action'=>'registration-success'));					
            }			 
        }
//      Returns an empty Registration Form        
        return new ViewModel(array('form' => $form));
	}

	
	public function registrationSuccessiframeAction() {
	    $user_email = null;
		$flashMessenger = $this->flashMessenger();
		if ($flashMessenger->hasMessages()) {
			foreach($flashMessenger->getMessages() as $key => $value) {
				$user_email .=  $value;
			}
		}
//      Returns an Registration Success View
        $viewModel = new ViewModel(array('user_email' => $user_email));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
/**
*   Handles the Action, which shows an successful registration
*/
	public function registrationSuccessAction() {
//      Defines an email variable    
		$user_email = null;
		$flashMessenger = $this->flashMessenger();
		if ($flashMessenger->hasMessages()) {
			foreach($flashMessenger->getMessages() as $key => $value) {
				$user_email .=  $value;
			}
		}
//      Returns an Registration Success View		
		return new ViewModel(array('user_email' => $user_email));
	}	

	public function confirmEmailiframeAction() {
	    // Gets the unique id that is given with the url
	    $token        = $this->params()->fromRoute('id');
	    $form         = new CompleteRegistrationForm();
	    $viewModel    = new ViewModel(array('token' => $token, 'form' => $form));


	    $viewModel->setTerminal(true);
	    
	    // Sets the value of the Submit Button
	    $form->get('user_submit')->setValue('Registrieren vervollständigen');


	    // Check if Token is Null
	    if(is_null($token)){
	        $viewModel->setTemplate('auth/registration/confirm-email-error.phtml');
	        return $viewModel;
	    }
	    
	    try {
	        $request  = $this->getRequest();

		    // Selects the User by his unique id
		    $user = $this->getUsersTable()->select(array('user_hashid' => $token));

		    // Selects the first row
		    $objUserIdenCurrent = $user->current();
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

                    if(!$objUserIdenCurrent->user_confirmed){
	                    // Update the User in Database

	                    $this->getUsersTable()->update($data, array('user_hashid' => $token));
	    
	    
	                    // Sets the column 'confirmed' in the Database to 1
	                    $this->getUsersTable()->update(array('user_confirmed' => '1', 'freigeschaltet' => 'ja'), array('user_hashid' => $token));
	    
	                    // Sends an Email to the User, which says that his email has been confirmed
	                    $this->sendEditMail($objUserIdenCurrent);
	                    $this->sendMailtoServer($objUserIdenCurrent);
	                }

                    // Add User to AcyMail
                    $objMySQLi          = mysqli_connect('dedi1612.your-server.de', 'aicovz_2', 'AXyL5rGDWXujk343', 'aicovz_db2');

                    $objResult          = $objMySQLi->query("SELECT `subid` FROM `ofyq0_acymailing_subscriber` WHERE `email` = '".$objUserIdenCurrent->user_email."';");
                    $objResult          = $objResult->fetch_array(MYSQLI_ASSOC);

                    if(is_null($objResult))
                    {
                        $strInsertSQL   = "INSERT INTO
                                               `ofyq0_acymailing_subscriber`
                                           SET
                                               `email`					= '".$objUserIdenCurrent->user_email."',
                                               `name`					= '".$objUserIdenCurrent->user_nachname."',
                                               `created`				= '".time()."',
                                               `confirmed`				= '1',
                                               `ip`					    = '".$_SERVER['REMOTE_ADDR']."',
                                               `confirmed_date`		    = '".time()."',
                                               `confirmed_ip`			= '".$_SERVER['REMOTE_ADDR']."',
                                               `source`				    = '',
                                               `anrede`				    = '".$objUserIdenCurrent->user_anrede."',
                                               `titel`					= '".$objUserIdenCurrent->user_titel."',
                                               `vorname`				= '".$objUserIdenCurrent->user_vorname."',
                                               `url_b`					= '',
                                               `firma`					= '".$objUserIdenCurrent->user_firmenname."',
                                               `anzahl_mitarbeiter`	    = '',
                                               `ort`					= '".$objUserIdenCurrent->user_ort."'
                                               ;";
                        mysqli_query($objMySQLi, $strInsertSQL);


                        $strInsertSQL   = "INSERT INTO
                                               `ofyq0_acymailing_listsub`
                                           SET
                                               `listid`				    = '8',
                                               `subid`				    = '".mysqli_insert_id($objMySQLi)."',
                                               `subdate`				= '".time()."',
                                               `status`				    = '1'
                                               ;";
                        mysqli_query($objMySQLi, $strInsertSQL);
                        mysqli_close($objMySQLi);
                    }

	                // Redirects the User to the 'Login' page
                    return $this->redirect()->toRoute('auth/default', array('controller'=>'index', 'action' => 'login', 'id' => 'success'));
	            }
	        }
	    }
	    catch(\Exception $e) {
            echo "<pre>";
            print_r($e);
            exit;
	        $viewModel->setTemplate('auth/registration/confirm-email-error.phtml');
	    }
	    return $viewModel;
	}
	
/**
*   Handles the Action, which Shows the User that his Email Adress has been confirmed
*/
	public function confirmEmailAction() {
        // Gets the unique id that is given with the url    
		$token        = $this->params()->fromRoute('id');
		$form         = new CompleteRegistrationForm();
		$viewModel    = new ViewModel(array('token' => $token, 'form' => $form));
        
		// Sets the value of the Submit Button
		$form->get('user_submit')->setValue('Registrieren vervollständigen');
		
		// Check if Token is Null
		if(is_null($token)){
		    $viewModel->setTemplate('auth/registration/confirm-email-error.phtml');
		    return $viewModel;
		}
		
		try {
		    $request  = $this->getRequest();
		    
		    // Selects the User by his unique id
		    $user = $this->getUsersTable()->select(array('user_hashid' => $token));
		     
		    // Selects the first row
		    $objUserIdenCurrent = $user->current();
		    $viewModel->setVariable('isActiv', ($objUserIdenCurrent->user_confirmed ? true : false));
		    $viewModel->setVariable('objUser', $objUserIdenCurrent);
		    
		    if ($request->isPost()) { 
		        // Sets an InputFilter on the Registration Form
		        $form->setInputFilter(new CompleteRegistrationFilter($this->getServiceLocator()));
		        $form->setData($request->getPost());

		        if ($form->isValid()) {
		            // Gets the Data from the Form
		            $data = $form->getData();
                    
                    // Unset Submit Button 
		            unset($data['user_submit']);
		            
		            // Selects the User by his unique id
		            $user = $this->getUsersTable()->select(array('user_hashid' => $token));

		            // Selects the first row
		            $objUserIdenCurrent = $user->current();
		            
		            if(!$objUserIdenCurrent->user_confirmed){
		                // Update the User in Database
    		            $this->getUsersTable()->update($data, array('user_hashid' => $token));
    		            
    		            
    		            // Sets the column 'confirmed' in the Database to 1
    		            $this->getUsersTable()->update(array('user_confirmed' => '1', 'freigeschaltet' => 'ja'), array('user_hashid' => $token));
    		            
    		            // Sends an Email to the User, which says that his email has been confirmed
            			$this->sendEditMail($objUserIdenCurrent);
            			$this->sendMailtoServer($objUserIdenCurrent);
		            }
		            
		            // Redirects the User to the 'Login' page
		            return $this->redirect()->toRoute('auth/default', array('action'=>'index'));
		        }
		    }
		}
		catch(\Exception $e) {
			$viewModel->setTemplate('auth/registration/confirm-email-error.phtml');
		}		
		return $viewModel;
	}

/**
*   Handles the Action, which sends the User a new Password
*/
	public function forgottenPasswordAction() {
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
//              Generates a 8-digit password
                $password = $this->generatePassword();
//              Updates the Users password in the database    
                $this->getUsersTable()->update(array('user_passwort' => sha1($password)), array('user_email' => $user_email));
//              Sends an Email to the User with the new password
                $this->sendPasswordByEmail($user_email, $password);
//              Redirects the User to the Password-changed page 
                return $this->redirect()->toRoute('auth/default', array('controller'=>'registration', 'action'=>'password-change-success'));
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
	public function passwordChangeSuccessAction() { 
//      Defines a variable to save the user email    
        $user_email = null;
        
//      Returns a new View        
        return new ViewModel(array('user_email' => $user_email));
	}	
	
/**
*   Generates a 8-digit random password
*/
	public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0) {
		 // get count of all required minimum special chars
		 $count = $c + $n + $s;
		 $out = '';
		 // sanitize inputs; should be self-explanatory
		 if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
			  trigger_error('Argument(s) not an integer', E_USER_WARNING);
			  return false;
		 }
		 elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
			  trigger_error('Argument(s) out of range', E_USER_WARNING);
			  return false;
		 }
		 elseif($c > $l) {
			  trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
			  return false;
		 }
		 elseif($n > $l) {
			  trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
			  return false;
		 }
		 elseif($s > $l) {
			  trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
			  return false;
		 }
		 elseif($count > $l) {
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
		 for($i = 0; $i < $l; $i++) {
			  $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		 }
	 
		 // create arrays if special character(s) required
		 if($count) {
			  // split base password to array; create special chars array
			  $tmp1 = str_split($out);
			  $tmp2 = array();
	 
			  // add required special character(s) to second array
			  for($i = 0; $i < $c; $i++) {
				   array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
			  }
			  for($i = 0; $i < $n; $i++) {
				   array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
			  }
			  for($i = 0; $i < $s; $i++) {
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
	public function getUsersTable() {
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

/**
*   Sends an Email to the User with an confirmation link
*/
    public function sendConfirmationEmailIframe($auth, $uniqid) {
//      Creates a Mail Object        
        $mail = new Mail\Message();
//      Sets the Encoding of the Mail to UTF-8
        $mail->setEncoding('utf-8');
//      Sets the Content of the Mail        
        $mail->setBody('Guten Tag, Herr '.$auth->user_nachname.', 
            
vor wenigen Minuten haben Sie, oder jemand der Ihre E-Mail-Adresse 
'.
$auth->user_email.' verwendet hat, sich kostenlos bei stellenanzeigen-texten.de 
registriert.

Damit wir sicher sind, dass die Registrierung tatsächlich von Ihnen erfolgt ist,
und Sie damit einverstanden sind, bitten wir Sie um eine kurze Bestätigung
durch einen Klick auf den folgenden Link: 
'.$this->getRequest()->getServer('HTTP_ORIGIN').
            $this->url()->fromRoute('auth/default', array(
                    'controller' => 'registration', 
                    'action' => 'confirm-emailiframe',
                    'id' => $uniqid)).         
'

Sollte der Link nicht funktionieren, kopieren Sie den vorstehenden Link bitte 
in die Adresszeile Ihres Browsers.

Solange Sie die Freischaltung nicht durchgeführt haben, können Sie sich
nicht einloggen und die Funktionen auf diesem Portal nicht nutzen.

Wenn Sie sich nicht selbst registriert haben, ignorieren und löschen Sie bitte
diese E-Mail.

Vielen Dank!

Mit freundlichen Grüßen

Ihr Team von stellenanzeigen-texten.de
--------------------------------------------
stellenanzeigen-texten.de ist ein Produkt von
aicovo gmbh 
... wir helfen Menschen gewinnen ® 
Hechtseestraße 16
83022 Rosenheim
Deutschland, Bayern
Telefon  +49 - 80 31 - 222 76 56 Fax  +49 - 80 31 - 222 78 10
E-Mail willkommen@aicovo.com Internet  www.aicovo.com
------------------------------------------------------------------------
Impressum der aicovo gmbh >> Sitz: 83071 Stephanskirchen, Deutschland
Handelsregister:  HRB 13900 - Amtsgericht Traunstein
Geschäftsführerin: Martina Haitzer - USt.-ID-Nummer: DE 219762137
------------------------------------------------------------------------');

                        

//      Sets the Adressor        
        $mail->setFrom('stellenanzeigen-texten.de[mailto:willkommen@aicovo.com]');
//      Sets the Acceptor        
        $mail->addTo($auth->user_email);
//      Sets the Subject
        $mail->setSubject('Bitte aktivieren Sie Ihre Registrierung bei der stellenanzeigen-texten.de');
//      Sends the Mail to the given email adress        
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
//      Redirects the User to the login Page        
        return $this->redirect()->toRoute('auth/default', array('controller' => 'index', 'action' => 'login'));
    }

    
    public function sendConfirmationEmail($auth, $uniqid) {
//      Creates a Mail Object        
        $mail = new Mail\Message();
//      Sets the Encoding of the Mail to UTF-8
        $mail->setEncoding('utf-8');
//      Sets the Content of the Mail   
        $mail->setBody('Guten Tag, Herr '.$auth->user_vorname.' '.$auth->user_nachname.',
            
vor wenigen Minuten haben Sie, oder jemand der Ihre E-Mail-Adresse 
'.
$auth->user_email.' verwendet hat, sich kostenlos bei stellenanzeigen-texten.de 
registriert.

Damit wir sicher sind, dass die Registrierung tatsächlich von Ihnen erfolgt ist,
und Sie damit einverstanden sind, bitten wir Sie um eine kurze Bestätigung
durch einen Klick auf den folgenden Link: 
'.$this->getRequest()->getServer('HTTP_ORIGIN').
            $this->url()->fromRoute('auth/default', array(
                    'controller' => 'registration', 
                    'action' => 'confirm-emailiframe',
                    'id' => $uniqid)).         
'

Sollte der Link nicht funktionieren, kopieren Sie den vorstehenden Link bitte 
in die Adresszeile Ihres Browsers.

Solange Sie die Freischaltung nicht durchgeführt haben, können Sie sich
nicht einloggen und die Funktionen auf diesem Portal nicht nutzen.

Wenn Sie sich nicht selbst registriert haben, ignorieren und löschen Sie bitte
diese E-Mail.

Vielen Dank!

Mit freundlichen Grüßen

Ihr Team von stellenanzeigen-texten.de
--------------------------------------------
stellenanzeigen-texten.de ist ein Produkt von 
aicovo gmbh 
... wir helfen Menschen gewinnen ® 
Hechtseestraße 16
83022 Rosenheim
Deutschland, Bayern
Telefon  +49 - 80 31 - 222 76 56 Fax  +49 - 80 31 - 222 78 10
E-Mail willkommen@aicovo.com Internet  www.aicovo.com
------------------------------------------------------------------------
Impressum der aicovo gmbh >> Sitz: 83071 Stephanskirchen, Deutschland
Handelsregister:  HRB 13900 - Amtsgericht Traunstein
Geschäftsführerin: Martina Haitzer - USt.-ID-Nummer: DE 219762137
------------------------------------------------------------------------');

                        

//      Sets the Adressor        
        $mail->setFrom('willkommen@aicovo.com', 'stellenanzeigen-texten.de');
//      Sets the Acceptor        
        $mail->addTo($auth->user_email);
//      Sets the Subject
        $mail->setSubject('Bitte aktivieren Sie Ihre Registrierung bei der stellenanzeigen-texten.de');
        
//      Sends the Mail to the given email adress        
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
//      Redirects the User to the login Page        
        return $this->redirect()->toRoute('auth/default', array('controller' => 'index', 'action' => 'login'));
    }
/**
*   Sends an Email to the User with the newly created password
*/
    public function sendPasswordByEmail($user_email, $password) {
//      Creates a Mail Object       
        $mail = new Mail\Message();
//      Sets the Encoding of the Mail to UTF-8
        $mail->setEncoding('utf-8');
//      Sets the Content of the Mail        
        $mail->setBody('Hallo! Ihr neues Passwort ist: '.$password);
//      Sets the Adressor
        $mail->setFrom('willkommen@aicovo.com');
//      Sets the Acceptor
        $mail->addTo($user_email);
//      Sets the Subject
        $mail->setSubject('Ihr Passwort wurde geändert');
//      Sends the Mail to the given email adress        
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }	
    
/**
*   Sends an Email to the User to inform him about his account confirmation
*/
    public function sendEditMail($user) {
//      Creates a new Mail Object
        $mail = new Mail\Message();
//      Sets the Encoding of the Mail to UTF-8
        $mail->setEncoding('utf-8');
//      Sets the Content of the Mail
        $mail->setBody('Guten Tag, Herr '.$user->user_vorname.' '.$user->user_nachname.',
        
Sie haben sich erfolgreich als Benutzer bei stellenanzeigen-texten.de angemeldet.
Die Anmeldung erfolgte für Sie wie vereinbart kostenfrei.

Nach Prüfung Ihrer Daten und Freischaltung können Sie sich bei stellenanzeigen-texten.de einloggen.

Ihre Zugangsdaten lauten:
Benutzername:   '.$user->user_email.'
Passwort:       '.$user->user_passwort.'

Nach dem Login stehen Ihnen viele Informationen und Funktionen zur Verfügung.

Wenn Sie Fragen haben sprechen Sie uns bitte an.

Mit freundlichen Grüßen

Ihr Team von stellenanzeigen-texten.de
--------------------------------------------
stellenanzeigen-texten.de ist ein Produkt von 
aicovo gmbh 
... wir helfen Menschen gewinnen ® 
Hechtseestraße 16
83022 Rosenheim
Deutschland, Bayern
Telefon  +49 - 80 31 - 222 76 56 Fax  +49 - 80 31 - 222 78 10
E-Mail willkommen@aicovo.com Internet  www.aicovo.com
------------------------------------------------------------------------
Impressum der aicovo gmbh >> Sitz: 83071 Stephanskirchen, Deutschland
Handelsregister:  HRB 13900 - Amtsgericht Traunstein
Geschäftsführerin: Martina Haitzer - USt.-ID-Nummer: DE 219762137
------------------------------------------------------------------------');
//      Sets the Adressor
        $mail->setFrom('willkommen@aicovo.com');
//      Sets the Acceptor
        $mail->addTo($user->user_email);
//      Sets the Subject
        $mail->setSubject('Herzlich willkommen bei stellenanzeigen-texten.de!');
//      Sends the Mail to the given email adress        
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
    
/**
*   Sends an Email to the Admin to inform him about a new account
*/
    public function sendMailtoServer($objUserIdenCurrent) {
//      Creates a new Mail Object
        $mail = new Mail\Message();
//      Sets the Encoding of the Mail to UTF-8
        $mail->setEncoding('utf-8');
//      Sets the Content of the Mail
        $mail->setBody('Guten Tag,
            
eben hat sich ein neuer Benutzer bei stellenanzeigen-texten.de registriert.

Loggen Sie sich ein um ihn freizuschalten, damit sich der neue Benutzer anmelden kann.

--------------------------------------------
stellenanzeigen-texten.de ist ein Produkt von
aicovo gmbh
... wir helfen Menschen gewinnen ®
Hechtseestraße 16
83022 Rosenheim
Deutschland, Bayern
Telefon  +49 - 80 31 - 222 76 81 Fax  +49 - 80 31 - 222 78 10 
E-Mail sandra.pohl@aicovo.com Internet  www.aicovo.com
------------------------------------------------------------------------
Impressum der aicovo gmbh >> Sitz: 83071 Stephanskirchen, Deutschland
Handelsregister:  HRB 13900 - Amtsgericht Traunstein
Geschäftsführerin: Martina Haitzer - USt.-ID-Nummer: DE 219762137
------------------------------------------------------------------------');

//      Sets the Adressor
        $mail->setFrom('willkommen@aicovo.com');
//      Sets the Acceptor
        $mail->addTo('willkommen@aicovo.com');
//      Sets the Subject
        $mail->setSubject('Neue Registrierung bei stellenanzeigen-texten.de!');
//      Sends the Mail to the given email adress        
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
    
}