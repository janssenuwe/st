<?php
namespace Homepage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Db\Adapter\Adapter as DbAdapter;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

use Homepage\Model\Auth;
use Homepage\Form\IndexForm;

use Application\Util\MailUtil;

class IndexController extends AbstractActionController
{
    protected $usersTable           = null;

    /**
    * To prevent Users from using the Editor without having an account, this function checks if someone is logged in
    */
    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        parent::onDispatch($e);
        
        if(!$this->identity()){
            return $this->redirect()->toRoute('auth/default',
                                        array('controller'=>'index',
                                              'action' => 'login'));
        }
    }
    
    public function indexAction()
    {
        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser                                  = $result->current();

        return new ViewModel(array(
            'user'       =>  $this->arrUser
        ));
    }

    public function expertcheckAction()
    {
        $arrError                                       = array();
        $arrSelect                                      = array();
        $boolFinish                                     = false;
        $objMySQL                                       = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser                                  = $result->current();

        $objSelect                                      = $objMySQL->query("SELECT `id` FROM `expert_check` WHERE `user_id` = ? LIMIT 1;", array($this->arrUser->id));
        $arrSelect                                      = $objSelect->current();

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST' && $arrSelect === false)
        {
            $arrFile        = (isset($_FILES['file']) ? $_FILES['file'] : array());

            if(count($arrFile) > 0)
            {
                if($arrFile['size'] == 0)
                    $arrError[]     = 'Bitte wählen Sie eine Datei aus.';
                else if($arrFile['size'] > 10485760)
                    $arrError[]     = 'Die Datei größe darf nicht größer als 10 MB sein.';

                if($arrFile['size'] > 0 && !in_array($arrFile['type'], array('image/png', 'application/pdf', 'image/jpeg')))
                    $arrError[]     = 'Die Ausgewählte Datei ist keine PDF, JPG oder PNG.';

                if(count($arrError) <= 0)
                {
                    $arrExp                 = array(
                        'image/png'         => '.png',
                        'image/jpeg'        => '.jpg',
                        'application/pdf'   => '.pdf',
                    );
                    $strFileName        = generateRandomString().$arrExp[$arrFile['type']];
                    rename($arrFile['tmp_name'], getcwd()."/public/upload/expertcheck/$strFileName");
                    $objMySQL->query("INSERT INTO `expert_check` (`user_id`, `filename`) VALUES (?, ?);", array($this->arrUser->id, $strFileName));

                    $arrSelect  = true;
                    $boolFinish = true;
                    $user       = $this->arrUser;

                    /** @var $strEmail string */
                    // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
                    // aus diesem scope hier verwendet werden
                    include_once("public/mails/expertenCheckUser.php");               // Datei aus /check/public/mails/ um den E-Mail Text nicht in der Funktion zu haben.

                    MailUtil::sendMail($this->getServiceLocator(), $user->user_email, $strEmail, 'Ihr Experten-Check');

                    /** @var $strEmail string */
                    // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
                    // aus diesem scope hier verwendet werden
                    include_once("public/mails/expertenCheckAdmin.php");              // Datei aus /check/public/mails/ um den E-Mail Text nicht in der Funktion zu haben.

                    MailUtil::sendMail(
                        $this->getServiceLocator(),
                        "axel.haitzer@aicovo.com",
                        $strEmail,
                        'Neue Experten-Check-Anfrage',
                        "js@stratmann.se",
                        "uwe.janssen@aicovo.com"
                    );
                }
            }
        }

        return new ViewModel(array(
            'arrError'          => $arrError,
            'user'              => $this->arrUser,
            'boolUsed'          => ($arrSelect !== false),
            'boolFinish'        => $boolFinish,
            'intFileId'         => (isset($arrSelect['id']) ? $arrSelect['id'] : 0)
        ));
    }

    public function expertcheckactionAction()
    {
        if(!$this->accessCheck())
        {
            echo '<meta http-equiv="refresh" content="0; url=http://www.stellenanzeigen-texten.de/check/public/auth/index/login">';
            exit;
        }


        $intId                                          = (isset($_GET['id']) ? $_GET['id'] : 0);
        $arrSelect                                      = array();
        $objMySQL                                       = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser                                  = $result->current();

        $objSelect                                      = $objMySQL->query("SELECT `filename` FROM `expert_check` WHERE `id` = ? AND `user_id` = ?;", array($intId, $this->arrUser->id));
        $objSelect                                      = $objSelect->current();

        if($objSelect == false || count($objSelect) <= 0)
        {
            echo '<meta http-equiv="refresh" content="0; url=http://www.stellenanzeigen-texten.de/check/public/homepage/Index/expertcheck">';
            exit;
        }

        $strPath                                        = getcwd()."/public/upload/expertcheck/".$objSelect['filename'];
        if(empty($objSelect['filename']) || !file_exists($strPath))
        {
            echo 'File not found';
            exit;
        }

        header('Set-Cookie: fileDownload=true; path=/');
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"".$objSelect['filename']."\"");
        echo readfile($strPath);
        exit;
    }

    public function contactAction()
    {
        $arrError                                       = array();
        $boolSuccess                                    = false;
        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser                                  = $result->current();

        function mysql_escape_mimic($inp) {
            if(is_array($inp))
                return array_map(__METHOD__, $inp);

            if(!empty($inp) && is_string($inp)) {
                return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
            }

            return $inp;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0)
        {
            $strText                                    = mysql_escape_mimic((isset($_POST['text']) ? $_POST['text'] : ''));
            $strAffected                                = mysql_escape_mimic((isset($_POST['affected']) ? $_POST['affected'] : ''));

            if(mb_strlen($strText) == 0)
                $arrError['textEmpty']                  = true;

            if(mb_strlen($strAffected) == 0)
                $arrError['affectedEmpty']              = true;

            if(mb_strlen($strAffected) > 255)
                $arrError['affectedLength']                 = true;

            if(count($arrError) <= 0)
            {
                $objMySQL                               = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $objMySQL->query("INSERT INTO `contact` (`user_id`, `text`, `affected`) VALUES (?, ?, ?);", array($this->arrUser->id, $strText, $strAffected));
                unset($_POST);
                $boolSuccess = true;
            }
        }

        return new ViewModel(array(
            'boolSuccess'       => $boolSuccess,
            'arrError'          => $arrError,
            'user'              => $this->arrUser
        ));
    }

    public function checklistAction()
    {
        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser                                  = $result->current();

        return new ViewModel(array(
            'user'       =>  $this->arrUser
        ));
    }

    public function editorAction(){
        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser                                  = $result->current();

        $objMySQL                                       = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $arrBugGroups                                   = array();

        $objBugGroup                                    = $objMySQL->query('SELECT * FROM `bug_type_group` ORDER BY `id` DESC;', array());
        foreach($objBugGroup as $row)
            $arrBugGroups[$row->id]                     = $row;

        $objBugType                                     = $objMySQL->query('SELECT * FROM `bug_type` ORDER BY `name` ASC;', array());
        foreach($objBugType as $row)
            $arrBugGroups[$row->groupId]['options'][]   = $row;



        // Filter empty group
        foreach($arrBugGroups as $row)
        {
            if(!isset($arrBugGroups[$row->id]['options']) || count($arrBugGroups[$row->id]['options']) == 0)
                unset($arrBugGroups[$row->id]);
        }



        return new ViewModel(array(
            'user'          => $this->arrUser,
            'arrBugGroups'  => $arrBugGroups
        ));
    }


    public function downloadAction()
    {
        if(!$this->accessCheck() || !isset($_GET['pdf']))
        {
            header("Location: http://www.stellenanzeigen-texten.de/check/public/auth/index/login");
            exit;
        }

        $arrAllowFiles  = array_diff(scandir('public/checklisten/'), array('..', '.'));

        if(!in_array($_GET['pdf'], $arrAllowFiles))
            exit;

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($_GET['pdf']) . "\"");
        readfile("public/checklisten/".$_GET['pdf']);

        exit;
    }
    public function ajaxReportAction(){
        if(!$this->accessCheck() || $_SERVER['REQUEST_METHOD'] != 'POST')
            exit;

        $result                                         = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser                                        = $result->current();

        $intType                                        = (!is_null($_POST['type']) ? $_POST['type'] : null);
        $strCommit                                      = (!is_null($_POST['commit']) ? $_POST['commit'] : null);
        $strContent                                     = (!is_null($_POST['content']) ? $_POST['content'] : null);

        if(is_null($intType) || is_null($strCommit) || is_null($strContent))
            exit;

        /** @var $strEmail string */
        // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
        // aus diesem scope hier verwendet werden
        include_once("public/mails/bugReportUser.php");                       // Datei aus /check/public/mails/ um den E-Mail Text nicht in der Funktion zu haben.

        MailUtil::sendMail(getServiceLocator(), $arrUser->user_email, $strEmail,'Ihre Anmerkung zum Text-Optimierer');

        // -------------------------------------------------------------------------------------------------------------

        $arrBugTypes                                    = array();
        $objMySQL                                       = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $objBugType                                     = $objMySQL->query('SELECT * FROM `bug_type` ORDER BY `id` DESC;', array());
        foreach($objBugType as $row)
            $arrBugTypes[$row->id]                      = $row->name;

        // send mail to admin team
        /** @var $strEmail string */
        // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
        // aus diesem scope hier verwendet werden
        include_once("public/mails/bugReportAdmin.php");                      // Datei aus /check/public/mails/ um den E-Mail Text nicht in der Funktion zu haben.
        MailUtil::sendMail(
            getServiceLocator(),
            "axel.haitzer@aicovo.com",
            $strEmail,
            'Anmerkung zum Text-Optimierer',
            "js@stratmann.se",
            "uwe.janssen@aicovo.com"
        );


        $objMySQL->query("INSERT INTO `bug_report` (`userid`, `type`, `content`, `commit`) VALUES (?, ?, ?, ?);",
                         array($arrUser->id, $intType, utf8_decode($strContent), utf8_decode($strCommit)));
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
}
