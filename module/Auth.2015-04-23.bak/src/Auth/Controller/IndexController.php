<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Db\Adapter\Adapter as DbAdapter;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

use Zend\Db\TableGateway\TableGateway;

use Auth\Model\Auth;
use Auth\Form\AuthForm;

/**
*   Handles The Login and Logout Process
*/
class IndexController extends AbstractActionController {
    protected $usersTable = null;

    public function indexAction() {
		return new ViewModel();
    }
   
	
/**
*    Handles the Action, which logs the User in
*/
    public function loginAction() {
//      Gets the Identity of the User who opened the page    
        $user = $this->identity();
//      Creates a new LoginForm      
        $form        = new AuthForm();
        $boolSuccess = ($this->params()->fromRoute('id', '') == 'success');

        $form->get('submit')->setValue('Log in');
//      Variable to show Error Messages
        $messages = null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $authFormFilters = new Auth();
//          Sets an InputFilter on the Form Elements            
            $form->setInputFilter($authFormFilters->getInputFilter());            
			$form->setData($request->getPost());
			 if ($form->isValid()) {
//              Gets the typed Data from the form
				$data = $form->getData();

				// Crypt pass with SHA1
	            $data['user_passwort'] = sha1($data['user_passwort']);
				
				$sm = $this->getServiceLocator();
//              Database Adapter
				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//              Gets the Configuration
				$config = $this->getServiceLocator()->get('Config');
//              Adapter which will be checked in the AuthenticationService
				$authAdapter = new AuthAdapter($dbAdapter,
                                                        'users', 
                                                        'user_email',
                                                        'user_passwort'
                                                       );
//              Sets the Identity for the AuthAdapter
				$authAdapter
					->setIdentity($data['user_email'])
					->setCredential($data['user_passwort'])
				;
				$auth = new AuthenticationService();
//              Checks if the User, who tries to login really exists
				$result = $auth->authenticate($authAdapter);	
//              Selects the User from the Database by the typed email
                $user_iden = $this->getUsersTable()->select(array('user_email' => $data['user_email']));
//              Gets the first Row from the Selection                
                $objUserIdenCurrent = $user_iden->current();

//              Checks if the a User was selected
                if($user_iden->count()){
//                  Checks if the User was unblocked by the Admin                    
                    if($objUserIdenCurrent->freigeschaltet !== \Auth\Model\Auth::USER_UNBLOCKED){
//                      If the User is blocked, he gets a unique email and a unique password, that doesnt exist in the database
                        $authAdapter = new AuthAdapter($dbAdapter,
                                            'users', 
                                            'user_email', 
                                            'user_passwort'
                                           );
                        $authAdapter
                            ->setIdentity(uniqid().'@'.uniqid().'.de')
                            ->setCredential(uniqid())
                        ;
//                      This result will gets a Failure Code, because the given Identity doesn't exist
                        $result = $auth->authenticate($authAdapter);
                    }
                }
//              gets the Code and Redirects if the User does exist and is unblocked                
				switch ($result->getCode()) {
					case Result::FAILURE_IDENTITY_NOT_FOUND:
						break;

					case Result::FAILURE_CREDENTIAL_INVALID:		
						break;

					case Result::SUCCESS:
//                      Redirects every User so the Adminpage
                        return $this->redirect()->toRoute('homepage/default', array('controller'=> 'Index'));
						break;

					default:
						break;
				}				
//              Sets the Error Message, if the User typed wrong Data				
                $messages .= '<div class="alert alert-danger">Benutzer ist noch nicht freigeschaltet oder Sie haben ungültige Daten eingegeben!</div>';
			 }
                         
		}
//      Returns an empty View, so the User can type his data
        $viewModel = new ViewModel(array('form' => $form, 'messages' => $messages, 'success' => $boolSuccess));
        $viewModel->setTerminal(true);
        return $viewModel;
	}

	/**
	 *    Handles the Action, which logs the User in
	 */
	public function loginIframeAction() {
	    //      Gets the Identity of the User who opened the page
	    $user = $this->identity();
	    //      Creates a new LoginForm
	    $form = new AuthForm();
	    $form->get('submit')->setValue('Log in');
	    //      Variable to show Error Messages
	    $messages = null;
	    $request = $this->getRequest();
	    if ($request->isPost()) {
	        $authFormFilters = new Auth();
	        //          Sets an InputFilter on the Form Elements
	        $form->setInputFilter($authFormFilters->getInputFilter());
	        $form->setData($request->getPost());
	        if ($form->isValid()) {
	            //              Gets the typed Data from the form
	            $data = $form->getData();
	            
	            // Crypt pass with SHA1
	            $data['user_passwort'] = sha1($data['user_passwort']);
	            
	            $sm = $this->getServiceLocator();
	            //              Database Adapter
	            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	            //              Gets the Configuration
	            $config = $this->getServiceLocator()->get('Config');
	            //              Adapter which will be checked in the AuthenticationService
	            $authAdapter = new AuthAdapter($dbAdapter,
	                    'users',
	                    'user_email',
	                    'user_passwort'
	            );
	            //              Sets the Identity for the AuthAdapter
	            $authAdapter
	            ->setIdentity($data['user_email'])
	            ->setCredential($data['user_passwort'])
	            ;
	            $auth = new AuthenticationService();
	            //              Checks if the User, who tries to login really exists
	            $result = $auth->authenticate($authAdapter);
	            //              Selects the User from the Database by the typed email
	            $user_iden = $this->getUsersTable()->select(array('user_email' => $data['user_email']));
	            //              Gets the first Row from the Selection
	            $objUserIdenCurrent = $user_iden->current();
	            //              Checks if the a User was selected
	            if($user_iden->count()){
	                //                  Checks if the User was unblocked by the Admin
	                if($objUserIdenCurrent->freigeschaltet !== \Auth\Model\Auth::USER_UNBLOCKED){
	                    //                      If the User is blocked, he gets a unique email and a unique password, that doesnt exist in the database
	                    $authAdapter = new AuthAdapter($dbAdapter,
	                            'users',
	                            'user_email',
	                            'user_passwort'
	                    );
	                    $authAdapter
	                    ->setIdentity(uniqid().'@'.uniqid().'.de')
	                    ->setCredential(uniqid())
	                    ;
	                    //                      This result will gets a Failure Code, because the given Identity doesn't exist
	                    $result = $auth->authenticate($authAdapter);
	                }
	            }
	            //              gets the Code and Redirects if the User does exist and is unblocked
	            switch ($result->getCode()) {
	            	case Result::FAILURE_IDENTITY_NOT_FOUND:
	            	    break;
	
	            	case Result::FAILURE_CREDENTIAL_INVALID:
	            	    break;
	
	            	case Result::SUCCESS:
	            	    //                      Redirects every User so the Adminpage
	            	    return $this->redirect()->toRoute('homepage/default',
	            	    array('controller'=> 'admin',
	            	    'action' => 'index'));
	            	    break;
	
	            	default:
	            	    break;
	            }
	            //              Sets the Error Message, if the User typed wrong Data
	            $messages .= '<div class="alert alert-danger">Benutzer ist noch nicht freigeschaltet oder Sie haben ungültige Daten eingegeben!</div>';
	        }
	         
	    }
	    //      Returns an empty View, so the User can type his data
	    $viewModel = new ViewModel(array('form' => $form, 'messages' => $messages));
	    $viewModel->setTerminal(true);
	    return $viewModel;
	}
	
/**
* Handles the Action, which logs the User out
*/
	public function logoutAction() {
//      Creates a new Authentication Service    
		$auth = new AuthenticationService();
//      Checks if a User is logged in 		
		if ($auth->hasIdentity()) {
//          Gets the Users Identity
			$identity = $auth->getIdentity();
		}			
//      Clears the Identity
		$auth->clearIdentity();
//      Creates a new SessionManager
		$sessionManager = new \Zend\Session\SessionManager();
//      Deletes the Session		
		$sessionManager->forgetMe();
//      Redirects the User to the Login View
		return $this->redirect()->toRoute('auth/default', array('controller' => 'index', 'action' => 'login'));		
	}
	
/**
* Selects Userrows from the User-Database
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
}