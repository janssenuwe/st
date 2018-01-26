<?php
namespace Homepage\Controller;

use Homepage\Model\FPDFBehaviorProfiles;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

use Zend\Session\Container;
use Zend\Session\SessionManager;

use Zend\Http\Request;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Db\Adapter\Adapter as DbAdapter;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

use Homepage\Model\Auth;

class BehaviorProfilesController extends AbstractActionController
{
    
    // Priavte variable
    private $arrBehaviorProfileTable    = array();
    private $usersTable                 = null;

    /**
    * To prevent Users from using the Editor without having an account, this function checks if someone is logged in
    */
    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        parent::onDispatch($e);

        if(!$this->identity() || !$this->accessCheck()){
            return $this->redirect()->toRoute('auth/default',
                                        array('controller'=>'index',
                                              'action' => 'login'));
        }
    }
    public function indexAction()
    {

    }
    public function behaviorprofilesAction()
    {
        // Überprüft ob der Nutzer diese Action nutzen darf


        // Get User
        global $arrUser, $objMySQL;
        $objMySQL           = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $result             = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser            = $result->current();

        // Lädt alle Länder aus der Datenbank
        $objSelectCountry                   = $this->getBehaviorProfileTable('country')->select(function (Select $select) {
            $select->order('sort ASC')
                   ->order('name ASC');
        });
        
        $arrCountry                         = array();
        foreach($objSelectCountry as $row){
            $row['name']                    = utf8_encode($row['name']);
            array_push($arrCountry, $row);
        }
        
        // Lädt alle Funktionen aus der Datenbank
        $objSelectFunction  = $this->getBehaviorProfileTable('function')->select(function (Select $select) {                                              
            $select->order('sort ASC')
                   ->order('name ASC');
        });
        
        $arrFunction        = array();
        foreach($objSelectFunction as $row){
            $row['name']    = utf8_encode($row['name']);
            array_push($arrFunction, $row);
        } 
        
        // Lädt alle Ergebnise aus der Datenbank
        $objSelectResults   = $this->getBehaviorProfileTable('result')->select(function (Select $select) {
            global $arrUser;
            $select->where(Array(
                'view'      => '1',
                'user_id'   => $arrUser->id
            ));
            $select->order('create_time DESC');
        });



        $arrResults         = array();
        foreach($objSelectResults as $row){
            // Lädt Antwort aus der Datenbank
            $objAnswer                      = $objMySQL->query('SELECT * FROM `behaviorprofile_answer` WHERE `identifier` = ?;', array($row['answer_identifier']));

            $arrSQLAnswer                   = Array();
            $arrAnswer                      = Array();

            foreach($objAnswer as $rowAnswer)
                $arrSQLAnswer[$rowAnswer->function_id]    = $rowAnswer;

            if(array_key_exists($row['function'], $arrSQLAnswer))
            {
                $arrAnswer                  = $arrSQLAnswer[$row['function']];
                $arrMainAnswer              = $arrSQLAnswer[0];
                foreach($arrAnswer as $key=>$value)
                {
                    if(empty($value))
                        $arrAnswer[$key]    = $arrMainAnswer[$key];
                }
            }
            else{
                $arrAnswer                  = $arrSQLAnswer[0];
            }
            $row['answer']                  = $arrAnswer;
            array_push($arrResults, $row);
        }

        return new ViewModel(array(
            'arrCountry'    => $arrCountry,
            'arrFunction'   => $arrFunction,
            'arrResults'    => $arrResults,
            'user'          => $arrUser
        ));
    }

    public function behaviorprofilesadminAction()
    {
        // Überprüft ob der Nutzer diese Action nutzen darf


        // Get User
        global $arrUser, $objMySQL;
        $objMySQL           = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $result             = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser            = $result->current();

        // Lädt alle Länder aus der Datenbank
        $objSelectCountry                   = $this->getBehaviorProfileTable('country')->select(function (Select $select) {
            $select->order('sort ASC')
                ->order('name ASC');
        });

        $arrCountry                         = array();
        foreach($objSelectCountry as $row){
            $row['name']                    = utf8_encode($row['name']);
            array_push($arrCountry, $row);
        }

        // Lädt alle Funktionen aus der Datenbank
        $objSelectFunction  = $this->getBehaviorProfileTable('function')->select(function (Select $select) {
            $select->order('sort ASC')
                   ->order('name ASC');
        });

        $arrFunction        = array();
        foreach($objSelectFunction as $row){
            $row['name']    = utf8_encode($row['name']);
            array_push($arrFunction, $row);
        }

        // Lädt alle Ergebnise aus der Datenbank
        $objSelectResults   = $this->getBehaviorProfileTable('result')->select(function (Select $select) {
            global $arrUser;
            $select->where(Array(
                'view'      => '1',
                'user_id'   => $arrUser->id
            ));
            $select->order('create_time DESC');
        });



        $arrResults         = array();
        foreach($objSelectResults as $row){
            // Lädt Antwort aus der Datenbank
            $objAnswer                      = $objMySQL->query('SELECT * FROM `behaviorprofile_answer` WHERE `identifier` = ?;', array($row['answer_identifier']));

            $arrSQLAnswer                   = Array();
            $arrAnswer                      = Array();

            foreach($objAnswer as $rowAnswer)
                $arrSQLAnswer[$rowAnswer->function_id]    = $rowAnswer;

            if(array_key_exists($row['function'], $arrSQLAnswer))
            {
                $arrAnswer                  = $arrSQLAnswer[$row['function']];
                $arrMainAnswer              = $arrSQLAnswer[0];
                foreach($arrAnswer as $key=>$value)
                {
                    if(empty($value))
                        $arrAnswer[$key]    = $arrMainAnswer[$key];
                }
            }
            else{
                $arrAnswer                  = $arrSQLAnswer[0];
            }
            $row['answer']                  = $arrAnswer;
            array_push($arrResults, $row);
        }

        return new ViewModel(array(
            'arrCountry'    => $arrCountry,
            'arrFunction'   => $arrFunction,
            'arrResults'    => $arrResults,
            'user'          => $arrUser
        ));
    }

    public function ajaxGetTextAction()
    {
        if(!$this->accessCheck())
            exit;
        header('Content-Type: application/json');
        global $intId, $intFunction, $intSelect, $arrTextTable;
        $intId                          = (isset($_POST['id'])          ? $_POST['id']          : null);
        $intFunction                    = (isset($_POST['function'])    ? $_POST['function']    : null);
        $intSelect                      = (isset($_POST['select'])      ? $_POST['select']      : null);
        $intSessionKey                  = (isset($_POST['session'])     ? $_POST['session']     : null);

        $boolEnd                        = false;

        if(is_null($intId) || is_null($intFunction))
            exit;

        $objSessionManager              = new SessionManager();
        Container::setDefaultManager($objSessionManager);
        $objSession                     = null;


        if(is_null($intSelect) || is_null($intSessionKey))
        {
            $intSessionKey              = rand(1, 10000);
            $objSession                 = new Container('behaviorProfiles'.$intSessionKey);
            $objSession->arrTextTable   = Array(
                'Z1'		            => Array(
                    'S1'	            => 'Dh1',
                    'S2'	            => 'Sh1',
                    'S3'	            => 0,
                    'S4'	            => 'Sh'
                ),
                'Z2'		            => Array(
                    'S1'	            => 'Ih1',
                    'S2'	            => 'Ch1',
                    'S3'	            => 0,
                    'S4'	            => 'Ch'
                ),
                'Z3'		            => Array(
                    'S1'	            => 'Dh2',
                    'S2'	            => 'Ih2',
                    'S3'	            => 0,
                    'S4'	            => 'Ih'
                ),
                'Z4'		            => Array(
                    'S1'	            => 'St1',
                    'S2'	            => 'Sh3',
                    'S3'	            => 0,
                    'S4'	            => 'Sh'
                ),
                'Z5'		            => Array(
                    'S1'	            => 'Ct1',
                    'S2'	            => 'Ct3',
                    'S3'	            => 0,
                    'S4'	            => 'Ch'
                ),
                'Z6'		            => Array(
                    'S1'	            => '',
                    'S2'	            => '',
                    'S3'	            => 0,
                    'S4'	            => ''
                ),
            );
        }
        else
        {
            $objSession                 = new Container('behaviorProfiles'.$intSessionKey);
        }

        $arrTextTable                   = $objSession->arrTextTable;

        if(!is_null($intSelect))
        {
            $intSelectId                                = $intId-1;
            $arrTextTable["Z$intSelectId"]['S3']		= intval($intSelect);

            if($arrTextTable['Z1']['S3'] == 1)
                $arrTextTable['Z3']['S1']	= substr($arrTextTable['Z1']['S2'], 0, 2).'2';
            else
                $arrTextTable['Z3']['S1']	= substr($arrTextTable['Z1']['S1'], 0, 2).'2';

            if($arrTextTable['Z2']['S3'] == 1)
                $arrTextTable['Z3']['S2']	= substr($arrTextTable['Z2']['S2'], 0, 2).'2';
            else
                $arrTextTable['Z3']['S2']	= substr($arrTextTable['Z2']['S1'], 0, 2).'2';

            for($i = 1; $i <= 3; $i++)
            {
                if($arrTextTable["Z$i"]['S3'] == 1)
                    $arrTextTable["Z$i"]['S4']	= substr($arrTextTable["Z$i"]['S1'], 0, 2);
                else
                    $arrTextTable["Z$i"]['S4']	= substr($arrTextTable["Z$i"]['S2'], 0, 2);
            }

            $arrTextTable['Z4']['S1']	= substr($arrTextTable['Z1']['S4'], 0, 1).'t1';
            $arrTextTable['Z4']['S2']	= substr($arrTextTable['Z1']['S4'], 0, 1).'h3';

            $arrTextTable['Z5']['S1']	= substr($arrTextTable['Z2']['S4'], 0, 1).'t1';
            $arrTextTable['Z5']['S2']	= substr($arrTextTable['Z2']['S4'], 0, 1).'h3';

            if($arrTextTable['Z4']['S3']+$arrTextTable['Z5']['S3'] > 2)
            {
                $arrTextTable['Z6']['S1']		= substr($arrTextTable['Z3']['S4'], 0, 1).'t1';
                $arrTextTable['Z6']['S2']		= substr($arrTextTable['Z3']['S4'], 0, 1).'h3';
            }

            for($i = 3; $i <= 6; $i++)
            {
                if($arrTextTable["Z$i"]['S3'] == 1)
                    $arrTextTable["Z$i"]['S4']	= substr($arrTextTable["Z$i"]['S1'], 0, 2);
                else
                    $arrTextTable["Z$i"]['S4']	= substr($arrTextTable["Z$i"]['S2'], 0, 2);
            }
        }
        $objSession->arrTextTable       = $arrTextTable;

        if($arrTextTable['Z4']['S3'] == 1 && $arrTextTable['Z5']['S3'] == 1)
        {
            // Output End as JSON
            echo json_encode(Array('session' => $intSessionKey, 'end' => true));
            exit;
        }
        elseif($arrTextTable['Z4']['S3']+$arrTextTable['Z5']['S3'] > 2 && $arrTextTable['Z6']['S3'] > 0)
        {
            // Output End as JSON
            echo json_encode(Array('session' => $intSessionKey, 'end' => true));
            exit;
        }

        $objMySQL                       = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $objResultTexte                 = $objMySQL->query('SELECT `textcode`, `text` FROM `behaviorprofile_text` WHERE `function_id` = ? AND (`textcode` = ? OR `textcode` = ?)',
                                                            array($intFunction, $arrTextTable["Z$intId"]['S1'], $arrTextTable["Z$intId"]['S2'])
        );

        if(count($objResultTexte) == 0)
        {
            $objResultTexte             = $objMySQL->query('SELECT `textcode`, `text` FROM `behaviorprofile_text` WHERE `function_id` = 0 AND (`textcode` = ? OR `textcode` = ?)',
                                                            array($arrTextTable["Z$intId"]['S1'], $arrTextTable["Z$intId"]['S2'])
            );
        }

        $arrCardTemp        = array();
        foreach($objResultTexte as $row){
            $row['text']                    = utf8_encode($row['text']);
            $arrCardTemp[$row->textcode]    = $row;
            //$row->text                      = "<b>DEBUG: ".$row->textcode."</b></br>".$row->text;
        }


        $arrCard            = array();
        array_push($arrCard, (array)$arrCardTemp[$arrTextTable["Z$intId"]['S1']]);
        array_push($arrCard, (array)$arrCardTemp[$arrTextTable["Z$intId"]['S2']]);



        // Output Card content as JSON
        header('Content-Type: application/json');
        echo json_encode(Array('session' => $intSessionKey, 'cards' => $arrCard, 'end' => $boolEnd));
        exit;
    }

    public function ajaxGetResultAction()
    {
        if(!$this->accessCheck())
            exit;

        $arrCardData        = (isset($_POST['cardData'])    ? $_POST['cardData']    : null);
        $intSessionKey      = (isset($_POST['session'])     ? $_POST['session']     : null);

        if(is_null($arrCardData) || is_null($intSessionKey))
            exit;

        $arrRes				= Array(
            1				=> '',
            2				=> '',
            3				=> '',

            4				=> '',
            5				=> '',
            6				=> '',
            7				=> ''
        );

        $objSession         = new Container('behaviorProfiles'.$intSessionKey);
        $arrTextTable       = $objSession->arrTextTable;

        if($arrTextTable["Z4"]['S3'] == 2)
            $arrRes[1]		= substr($arrTextTable["Z4"]['S1'], 0, 1);

        if($arrTextTable["Z5"]['S3'] == 2)
            $arrRes[2]		= substr($arrTextTable["Z5"]['S1'], 0, 1);

        if($arrTextTable['Z4']['S3']+$arrTextTable['Z5']['S3'] == 2)
            $arrRes[3]		= substr($arrTextTable["Z3"]['S4'], 0, 1);
        else
            if($arrTextTable["Z6"]['S3'] == 2)
                $arrRes[3]	= substr($arrTextTable["Z6"]['S1'], 0, 1);




        if($arrTextTable["Z3"]['S3'] == 1)
            $arrRes[4]		= substr($arrTextTable["Z3"]['S2'], 0, 1);
        else
            $arrRes[4]		= substr($arrTextTable["Z3"]['S1'], 0, 1);

        if($arrTextTable["Z4"]['S3'] == 1)
            $arrRes[5]		= substr($arrTextTable["Z4"]['S1'], 0, 1);

        if($arrTextTable["Z5"]['S3'] == 1)
            $arrRes[6]		= substr($arrTextTable["Z5"]['S1'], 0, 1);

        if($arrTextTable["Z6"]['S3'] == 1)
            $arrRes[7]		= substr($arrTextTable["Z6"]['S1'], 0, 1);

        $strRes				= "";

        foreach($arrRes as $key=>$value)
        {
            $strRes			.= $value;

            if($key == 3)
                $strRes		.= '-';
        }


        $objMySQL                       = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $objAnswer                      = $objMySQL->query('SELECT * FROM `behaviorprofile_answer` WHERE `identifier` = ?;', array($strRes));

        $arrSQLAnswer                   = Array();
        $arrAnswer                      = Array();

        foreach($objAnswer as $row)
            $arrSQLAnswer[$row->function_id]    = $row;

        if(array_key_exists($arrCardData['function'], $arrSQLAnswer))
        {
            $arrAnswer                  = $arrSQLAnswer[$arrCardData['function']];
            $arrMainAnswer              = $arrSQLAnswer[0];
            foreach($arrAnswer as $key=>$value)
            {
                if(empty($value))
                    $arrAnswer[$key]    = $arrMainAnswer[$key];
            }
        }
        else{
            $arrAnswer                  = $arrSQLAnswer[0];
        }
        unset($arrAnswer['name']);
        unset($objSession->arrTextTable);

        $result             = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser            = $result->current();

        $objMySQL->query('INSERT INTO
                              `behaviorprofile_result`
                          SET
                              `user_id`           = ?,
                              `postion`           = ?,
                              `department`        = ?,
                              `country`           = ?,
                              `zip`               = ?,
                              `location`          = ?,
                              `function`          = ?,
                              `answer_identifier` = ?,
                              `create_time`       = ?;',
                          array($arrUser->id,
                                $arrCardData['postion'],
                                $arrCardData['department'],
                                $arrCardData['country'],
                                $arrCardData['zip'],
                                $arrCardData['location'],
                                $arrCardData['function'],
                                $strRes,
                                date('Y-m-d H:i:s')
                          )
        );

        // Output Card content as JSON
        echo json_encode(Array('identifier' => $strRes, 'answer' => $arrAnswer, 'end' => true, 'saveId' => $objMySQL->getDriver()->getLastGeneratedValue()));
        exit;
    }

    public function ajaxKillSessionAction()
    {
        if(!$this->accessCheck())
            exit;

        $intSessionKey                  = (isset($_POST['session'])     ? $_POST['session']     : null);
        if(is_null($intSessionKey))
            exit;

        $objSession                 = new Container('behaviorProfiles'.$intSessionKey);

        if($objSession->arrTextTable != NULL);
        unset($objSession->arrTextTable);

        echo "ok";
        exit;
    }

    public function ajaxSaveResultAction()
    {
        if(!$this->accessCheck())
            exit;

        $intSaveId                  = (isset($_POST['saveId'])     ? $_POST['saveId']     : null);


        if(is_null($intSaveId))
            exit;

        $result             = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser            = $result->current();

        $objMySQL           = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $objMySQL->query("UPDATE IGNORE
                              `behaviorprofile_result`
                          SET
                              `view`              = 1
                          WHERE
                              `id`                = ? AND
                              `user_id`           = ?",
                          array(
                              $intSaveId,
                              $arrUser->id
                          )
        );
        echo "ok";
        exit;
    }

    public function ajaxSendFormAction()
    {
        if(!$this->accessCheck())
            exit;

        global $arrForm;
        global $strAnswerId;
        $strAnswerId        = '';
        $arrForm            = (count($_POST) > 0 ? $_POST : array());
        
        if(count($arrForm) <= 0)
            exit;
        
        foreach ($arrForm as $key=>$value){
            if(strpos($key, 'card') === 0)
                $strAnswerId .= $value;
        }
        //echo "Select ID: $strAnswerId\n";        
        // Load Answer by $strAnswerId       
        $objSelectAnswer    = $this->getBehaviorProfileTable('answer')->select(array('identifier' => $strAnswerId, 'function_id' => $arrForm['function']));
        $arrAnswer          = $objSelectAnswer->current();
        
        if(is_null($arrAnswer['identifier'])){
            $objSelectAnswer    = $this->getBehaviorProfileTable('answer')->select(array('identifier' => $strAnswerId, 'function_id' => 0));
            $arrAnswer          = $objSelectAnswer->current();
        }



        header('Content-Type: application/json');
        echo json_encode($arrAnswer);
        exit;
    }
    
    public function ajaxSaveFormAction()
    {
        if(!$this->accessCheck())
            exit;

        global $arrForm;
        global $strAnswerId;
        $strAnswerId                    = '';
        $arrForm                        = (count($_POST) > 0 ? $_POST : array());

        if(is_null($this->identity()))
            exit;
        
        if(count($arrForm) <= 0)
            exit;
        
        foreach ($arrForm as $key=>$value) {
            if (strpos($key, 'card') === 0) {
                $strAnswerId .= $value;
                unset($arrForm[$key]);
            }
        }
        
        $objSelectUser                  = $this->getBehaviorProfileTable('users', false)->select(array('user_email' => $this->identity()));
        $arrUser                        = $objSelectUser->current();

        $arrForm['user_id']             = $arrUser['id'];
        $arrForm['answer_identifier']   = $strAnswerId;
        
        $this->getBehaviorProfileTable('result')->insert($arrForm);

        header('Content-Type: application/json');
        echo json_encode(array('status' => 'ok'));
        exit;
    }
    
    public function ajaxDeleteResultAction()
    {
        if(!$this->accessCheck())
            exit;

        global $intResultId;
        $intResultId                    = (!is_null($_POST['id']) ? $_POST['id'] : null);
    
        if(is_null($this->identity()))
            exit;
    
        if(is_null($intResultId))
            exit;
    
        $objSelectUser                  = $this->getBehaviorProfileTable('users', false)->select(array('user_email' => $this->identity()));
        $arrUser                        = $objSelectUser->current();
    
        
        $this->getBehaviorProfileTable('result')->delete(array('id' => $intResultId, 'user_id' => $arrUser->id));

        header('Content-Type: application/json');
        echo json_encode(array('status' => 'ok'));
        exit;
    }
    
    public function ajaxDownloadResultAction()
    {
        if(!$this->accessCheck())
            exit;

        global $intResultId, $arrUser;
        $intResultId                    = (count($_GET) > 0 && !is_null($_GET['id']) ? $_GET['id'] : null);
    
        if(is_null($this->identity()))
            die('Kein aktiver Login gefunden');
    
        if(is_null($intResultId))
            die('Parameter ID nicht gefunden');

        $objSelectUser                  = $this->getBehaviorProfileTable('users', false)->select(array('user_email' => $this->identity()));
        $arrUser                        = $objSelectUser->current();

        if(count($arrUser) <= 0)
            die('Benutzer nicht gefunden');

        // Lädt alle Ergebnise aus der Datenbank
        $objMySQL           = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $objSelectResult    = $this->getBehaviorProfileTable('result')->select(function (Select $select) {
            global $arrUser, $intResultId;
            $select->where(Array(
                'id'        => $intResultId,
                'view'      => '1',
                'user_id'   => $arrUser->id
            ));
        });

        $arrResult          = $objSelectResult->current();

        // Lädt Antwort aus der Datenbank
        $objAnswer                      = $objMySQL->query('SELECT * FROM `behaviorprofile_answer` WHERE `identifier` = ?;', array($arrResult['answer_identifier']));

        // Lädt Funktions aus Namen
        $objFunction                    = $objMySQL->query('SELECT * FROM `behaviorprofile_function` WHERE `id` = ?;', array($arrResult['function']));
        $arrFucntion                    = $objFunction->current();

        $arrSQLAnswer                   = Array();
        $arrAnswer                      = Array();

        foreach($objAnswer as $rowAnswer)
            $arrSQLAnswer[$rowAnswer->function_id]    = $rowAnswer;

        if(array_key_exists($arrResult['function'], $arrSQLAnswer))
        {
            $arrAnswer                  = $arrSQLAnswer[$arrResult['function']];
            $arrMainAnswer              = $arrSQLAnswer[0];
            foreach($arrAnswer as $key=>$value)
            {
                if(empty($value))
                    $arrAnswer[$key]    = $arrMainAnswer[$key];
            }
        }
        else{
            $arrAnswer                  = $arrSQLAnswer[0];
        }
        $arrResult['answer']            = $arrAnswer;


        if(count($arrResult) <= 0)
            die('Keine Verhaltensprofil-Antwort Daten gefunden');

        try {
            $pdf = new FPDFBehaviorProfiles();
            $pdf->AddPage();

            $pdf->setIdentifier($arrResult['answer_identifier']);
            $pdf->setName(utf8_encode($arrUser['user_vorname'].' '.$arrUser['user_nachname']));
            $pdf->setPostion(utf8_encode($arrResult['postion']));
            $pdf->setLogo('public/img/logo-toolbox-300dpi.png', 'http://stellenanzeigen-texten.de/');
            $pdf->setDepartment(utf8_encode($arrResult['department']));
            $pdf->setCreateDate(utf8_encode($arrResult['create_time']));
            
            $pdf->setAddressText(utf8_encode($arrResult['answer']->address));
            $pdf->setJobText(utf8_encode($arrResult['answer']->job));
            $pdf->setPropertiesText(utf8_encode($arrResult['answer']->properties));
            $pdf->setDemotivatedText(utf8_encode($arrResult['answer']->demotivated));
            $pdf->setPositiveText(utf8_encode($arrResult['answer']->positive));
            
            $pdf->setLocation(utf8_encode($arrResult['zip'].' '.$arrResult['location']));
            $pdf->setFunction(utf8_encode($arrFucntion['name']));
            
            $pdf->buildBehaviorProfiles();

            header('Set-Cookie: fileDownload=true; path=/');
            $pdf->Output("Verhaltensprofil-".$arrResult['postion']."-$intResultId.pdf", 'D');
        }   
        catch (Exception $e) {
            die($e->getMessage());
        }   
        exit;
    }

    private function accessCheck()
    {
        if($this->identity() == NULL)
            return false;

        $result             = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser            = $result->current();

        return (isset($arrUser) && $arrUser != false);
    }
    
    private function getBehaviorProfileTable($tableName, $behaviorProfileTable = true) {
        $tableName = strtolower($tableName);
        // Überprüft ob die Tablelle bereits geladen wurde
        if (!isset($this->arrBehaviorProfileTable[$tableName])) {
            // Ladet die Tabelle aus der Datenbank ins Array
            $this->arrBehaviorProfileTable[$tableName] = new TableGateway(
                    ($behaviorProfileTable ? 'behaviorprofile_' : '').$tableName,
                    $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
            );
        }
        // Gibt die Tabelle zurück
        return $this->arrBehaviorProfileTable[$tableName];
    }
    /**
     *   Selects all Data from the User-Table from the Database
     */
    private function getUsersTable() {
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
