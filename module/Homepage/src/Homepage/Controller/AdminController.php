<?php
namespace Homepage\Controller;

use Homepage\Form\AdminForm;
use Homepage\Form\SearchFilter;
use Homepage\Form\SearchForm;
use Homepage\Form\SearchWordFilter;
use Homepage\Form\SearchWordForm;
use Homepage\Form\UserFilter;
use Homepage\Form\UserForm;
use Homepage\Form\WordAddFilter;
use Homepage\Form\WordAddForm;
use Homepage\Form\WordFilter;
use Homepage\Form\WordForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

use Zend\Mvc\Controller\AbstractActionController;

use Application\Util\MailUtil;

use Zend\View\Model\ViewModel;

/**
 *   Handles every action the Admin does
 */
include_once('public/includes/Utils.php');

class AdminController extends AbstractActionController
{
    //  Variable to save the selection from the 'users' table from the Database
    protected $usersTable = NULL;
    protected $standardtextTable = NULL;
    //  Variable to save the selection from the text_check tables from the Database
    protected $textCheckTable = NULL;

    protected $arrUser = array();

    /**
     *  To prevent Users from getting Admin rights bei using the Admin-URL you get the Users Identity and see if he is an Admin
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        //      Get the Users Identity bei selecting his Email from the Database

        $result = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        //      Checks is the User hat Admin rights
        $resultobj = $result->current();
        if (!$this->identity())
        {
            return $this->redirect()->toRoute('auth/default', array(
                'controller' => 'index',
                'action'     => 'login'
            ));
        }
        if ($resultobj->rolle != \Homepage\Model\Auth::ROLE_ADMIN)
        {
            //          If not, he gets redirected to the Index Page
            return $this->redirect()->toRoute('homepage/default', array(
                'controller' => 'index',
                'action'     => 'index'
            ));
        }
    }

    /**
     *   Shows the Startpage for the Admin
     */
    public function indexAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        //echo "<pre> \$result = ";
        //var_dump($result);
        //die();


        // render
        return new ViewModel(array(
            'user'            => $this->arrUser,
            'currSite'        => 'index',
            'arrRegistration' => $this->calc7DayRegistration(),
            'arrEditorUse'    => $this->calcEditorUse(),
        ));
    }

    public function calc7DayRegistration()
    {
        $objMySQL    = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $arrWeek     = array(
            date("Y-m-d", strtotime("-7 day")),
            date("Y-m-d"),
        );
        $arrWeekData = array();
        $strSQL      = "SELECT
                                               DATE(`created_at`) AS `date`,
                                               count(`id`) AS `count`
                                           FROM
                                               `users`
                                           WHERE
                                               created_at > '" . $arrWeek[0] . "' AND
                                               created_at < '" . $arrWeek[1] . "' AND
                                               user_confirmed = 1
                                           GROUP BY
                                               DATE(`created_at`)
                                           ORDER BY
                                               `created_at` DESC;";
        $objResult   = $objMySQL->query($strSQL, array());

        // fill array with days
        for ($i = 0; $i <= 6; $i++)
        {
            $strCurrDate               = date('d.m.Y', strtotime("+$i days", strtotime($arrWeek[0])));
            $arrWeekData[$strCurrDate] = 0;
        }

        // fill days with values
        foreach ($objResult as $row)
        {
            $strCurrDate = date('d.m.Y', strtotime($row->date));
            if (array_key_exists($strCurrDate, $arrWeekData))
                $arrWeekData[$strCurrDate] = intval($row->count);
        }

        return $arrWeekData;
    }

    public function calcEditorUse()
    {
        $objMySQL   = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $arrUseData = array();
        $strSQL     = "SELECT
                               COUNT(es.`id`) AS `count`,
                               es.`function`,
                               esc.color
                           FROM
                               editor_statistics es
                           LEFT JOIN
                               editor_statistics_color esc ON (es.`function` = esc.`function`)
                           GROUP BY
                               es.`function`
                           ORDER BY
                               COUNT(es.`id`);";

        $objResult = $objMySQL->query($strSQL, array());

        foreach ($objResult as $row)
        {
            $arrUseData[] = array(
                'value' => $row->count,
                'color' => '#' . $row->color,
                'label' => utf8_encode($row->function)
            );
        }

        return $arrUseData;
    }

    public function besonderesameditorAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();
        return new ViewModel(array(
            'user'     => $this->arrUser,
            'currSite' => 'besonderesameditor'
        ));
    }

    /**
     *   Handles the Action, which creates new Users
     */
    public function createAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        //      Gets the Form, where you type in the User Data
        $form = new UserForm();
        //      Changes the value of the Submit Button
        $form->get('submit')->setValue('Benutzer anlegen');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            //          Sets The InputFilter for the Form
            $form->setInputFilter(new UserFilter());
            //          Sets The Data for the Filter
            $form->setData($request->getPost());
            //          Checks if the form passed the Filter, or if the User typed something he shouldn't
            if ($form->isValid())
            {
                //              Gets the data, the User typed into the Form
                $data = $form->getData();
                //              Disabled the submit Button, so the Tablegateway will not search for a collumn called 'submit'
                unset($data['submit']);
                //              Inserts the typed data into the Database
                $this->getUsersTable()->insert($data);
                //              Redirects the User to the View, that shows the User-Table

                return $this->redirect()->toUrl('http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/1?user_name=&user_adresse=&user_email=&user_firma=');
            }
        }

        // Shows the empty form, before the Users typed anything
        return new ViewModel(array(
            'form'     => $form,
            'user'     => $this->arrUser,
            'currSite' => 'create'
        ));
    }

    /**
     *   Handles the Action, which updates Userdata
     */
    public function updateAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        //      Gets the ID from the route, which defines the data to change
        $id = $this->params()->fromRoute('id');
        //      Gets a new Form to change the Userdata from the Database
        $form = new UserForm();
        $form->get('submit')->setValue('Aktualisieren');
        $request = $this->getRequest();

        if ($request->isPost())
        {
            //          Sets the InoutFilter for the Form
            $form->setInputFilter(new UserFilter());
            //          Sets the Data for the Filter
            $form->setData($request->getPost());
            //          Checks if the form passed the Filter, or the User typed something he shouldn't
            if ($form->isValid())
            {
                //              Gets the data, the User typed into the Form
                $data = $form->getData();

                foreach ($data as $key => $value)
                    $data[$key] = utf8_decode($value);

                if (strlen($data['user_passwort'] == 0))
                {
                    $data['user_passwort'] = $this->arrUser->user_passwort;
                }
                else
                {
                    $data['user_passwort'] = sha1($data['user_passwort']);
                }
                //              Disabled the submit Button, so the TableGateway will not search for a collumn called 'submit'
                unset($data['submit']);
                //              Updates the Userdata at the given ID
                $this->getUsersTable()->update($data, array('id' => $id));
                //              Redirects the User to the View, that shows the User-Table
                return $this->redirect()->toUrl('http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/1?user_name=&user_adresse=&user_email=&user_firma=');
            }
        }
        else
        {
            //          Before the User changes the Data he can see the old Data in the Form, which is selected from the Database
            $form->setData($this->getUsersTable()->select(array('id' => $id)));
        }
        //      Shows the Form with the old Userdata from the Database
        return new ViewModel(array(
            'form'     => $form,
            'rowset'   => $this->getUsersTable()->select(),
            'id'       => $id,
            'user'     => $this->arrUser,
            'currSite' => 'update'
        ));
    }

    /**
     *   Handles the Action, which deletes a User from the Database
     */
    public function deleteAction()
    {
        //      Gets the Id from the route, which defines the data to delete
        $id = $this->params()->fromRoute('id');
        //      Checks if a ID is given
        if ($id)
        {
            //          Deletes the data with the given ID from the Database
            $this->getUsersTable()->delete(array('id' => $id));
        }
        //      Redirects the User to the View, that shows the User-Table
        if (isset($_SERVER['HTTP_REFERER']))
            $strLastPage = $_SERVER['HTTP_REFERER'];
        else
            $strLastPage = 'http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/1?user_name=&user_adresse=&user_email=&user_firma=';
        return $this->redirect()->toUrl($strLastPage);
    }

    /**
     *   Selects all Data from the User-Table from the Database
     */
    public function getUsersTable()
    {
        //      Checks if usersTable has got a selection
        if (!$this->usersTable)
        {
            //          Creates a Tablegateway for the 'users' table in the Database
            $this->usersTable = new TableGateway('users', $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        }
        return $this->usersTable;
    }

    /**
     *   Selects all Data from the User-Table from the Database
     */
    public function getStandardTextTable()
    {
        if (!$this->standardtextTable)
        {
            $this->standardtextTable = new TableGateway('editor_standardtext', $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        }
        return $this->standardtextTable;
    }

    /**
     *   Handles the Action, which shows the Table with all the User data in it
     */
    public function showusersAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        //      Saves the current page number
        global $page_number;
        //      Saves the number of rows that should be shown on 1 page
        global $intItemsPerPage;

        global $entry_field;

        //      Saves the typed data from the user_name form element
        global $user_name_field;
        //      Saves the typed data from the user_adresse form element
        global $user_adresse_field;
        //      Saves the typed data from the user_email form element
        global $user_email_field;
        //      Saves the typed data from the user_firma form element
        global $user_firma_field;
        // user type (Standard/confirmed=1, not confirmed = 2, locked = 3
        global $intType;
        //      Defines the number of rows shown on the page
        $intItemsPerPage = 50;
        //      Defines the maximum page number that will be shown
        $max_page_number = 0;
        //      If a Value is found in the database this Variable will be set to 1
        $foundInDatabase = 0;
        //      If the form is sent empty, this variable will be set to true
        $nothing_typed = false;
        //      Gets the Id of the URL which contains the next page number
        $page_number = $this->params()->fromRoute('id');
        //      Array, which saves 50 Datarows that will be shown on the page
        $arrPage = array();
        //      Saves the Rows with the hits of the search form
        $arrSearch = array();

        $intType = request_var('typ', 1);

        //      (First time select all from table 'users')
        $rowset = $this->getUsersTable()->select(function (Select $select) use ($intType)
        {
            global $page_number;
            global $intItemsPerPage;
            //global $intType;
            // Orders the data alphabetically
            $select->order('id DESC') // Selects only the given number of rows
                ->limit($intItemsPerPage)// Sets the Startpoint for the selection
                ->offset(($page_number - 1) * $intItemsPerPage);
            // create a first select statement restricted to intType
            $this->createWhereClauseFromIntType($intType,$select);
        });

        // form fields empty && no data in table
        if (strlen(request_var('user_name', '')) == 0 && strlen(request_var('user_firma', '')) == 0 && $rowset->count() == 0 && $page_number != 1)
        {
            $page_number = $page_number - 1;
            $strLastPage = "http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/$page_number?user_name=&user_adresse=&user_email=&user_firma=";
            return $this->redirect()->toUrl($strLastPage);
        }

        //      Counts all rows from the database restricted (user_confirmed and or freigeschaltet) only
        $max_page_number = $this->getUsersTable()->select(function (Select $select) use ($intType)
        {
            $this->createWhereClauseFromIntType($intType,$select);
        })->count();

        //      Iterates through the selected rows
        foreach ($rowset as $index => $row)
        {
            //          pushes the row onto
            array_push($arrPage, $row);
        }

        $objMySQL     = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $arrAdminMain = array();
        $strSQL       = "SELECT * FROM `admin_email`;";

        $objResult = $objMySQL->query($strSQL, array());

        foreach ($objResult as $row)
        {
            $arrAdminMain[$row->id] = array(
                'name'    => utf8_encode($row->name),
                'subject' => utf8_encode($row->subject),
                'content' => utf8_encode($row->content)
            );
        }

        $form    = new SearchForm();
        $request = $this->getRequest();
        // Handle page setup for NOT EMPTY filter (at least one form field set
        if (strlen(request_var('entry', '')) !== 0 || strlen(request_var('user_name', '')) !== 0 || strlen(request_var('user_email', '')) !== 0 || strlen(request_var('user_firma', '')) !== 0)
        {
            $form->setInputFilter(new SearchFilter());
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                //              Gets the typed value and splits it, to get the single words
                $entry_field     = @$_GET['entry'];
                $user_name_field = request_var('user_name', '');
                $user_name_field = explode(' ', $user_name_field);
                //              Gets the typed value
                $user_email_field = request_var('user_email', '');
                //              Gets the typed value
                $user_firma_field = request_var('user_firma', '');
                $resultingWhereClause = null;
                //              Selects the words from the database
                $rowset     = $this->getUsersTable()->select(function (Select $select) use ($intType, & $resultingWhereClause)
                {
                    global $page_number;
                    global $intItemsPerPage;

                    global $entry_field;
                    global $user_name_field;
                    global $user_adresse_field;
                    global $user_email_field;
                    global $user_firma_field;

                    // Checks if the user_name form element is empty
                    if (strlen(request_var('user_name', '')) !== 0)
                    {
                        // Do not remove the NEST and UNNEST segments!
                        // Those ones create opening and closing brackets

                        // If the User typed more than one word into the form element, all of them are searched in the database
                        // foreach item in the form's NAME field compare to nachname, anrede, titel, vorname
                        // Always bracket those complex user WHERE clauses via NEST/UNNEST
                        $where = $select->where->NEST;
                        foreach ($user_name_field as $index => $user_name)
                        {
                                $where = $where->or->where->
                                like('user_vorname', $user_name . '%')
                                    ->or->where->like('user_nachname', $user_name . '%')
                                    ->or->where->like('user_titel', $user_name . '%')
                                    ->or->where->like('user_anrede', $user_name . '%');
                                // combine intType value with current where clause
                                $this->createWhereClauseFromIntType($intType,$where);
                        }
                        $where = $where->UNNEST;
                    }

                    if (strlen(@$_GET['id']) !== 0)
                    {
                        $where = $where->or->where->like('id', $entry_field);
                    }
                    // Commented out and put to end of page:
                    // [Checks if the user_email form element is empty ]

                    // Checks if the user_firma form element is empty
                    if (strlen($_GET['user_firma']) !== 0)
                    {
                        $likeCondition = '%' . $user_firma_field . '%';
                        // A sql statement cannot start with '->or' or 'and', that's why you need to check if a statement was executed before
                        if (strlen(request_var('user_name', '')) !== 0 && strlen(request_var('user_firma', '')) !== 0)
                        {
                            // where is not empty, you may give it another where clause
                            // U. Janssen 6.12.2017 BEGIN
                            $where = $where->where->and->like('user_firmenname', $likeCondition);
//  Originally:             $where = $select->where->or->like('user_firmenname', $user_firma_field . '%');
                            // U. Janssen 6.12.2017 END
                        }
                        else
                        {
                            // This might be the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                            $where = $select->where->like('user_firmenname', $likeCondition);
                        }
                    }
                    // Selects the rows with the above given options
                    $select->where($where);
                    // Store where clause for later use
                    $resultingWhereClause = $where;
                    // Orders the rows alphabetically
                    $select->order('user_nachname ASC') // Selects only the given number of rows
                        ->limit($intItemsPerPage)// Sets the Startpoint for the selection
                        ->offset(($page_number - 1) * $intItemsPerPage);
                });

                // MOVED to end of file: $rowset_all = $this->getUsersTable()->select(function (Select $select) { .../*lengthy method*/...
                // if long for that duplicate code back again:
                // look for [$rowset_all = $this->getUsersTable()->select(function
                // at the end of the file
                //
// originally:                $max_page_number = $rowset_all->count();
                // Gets the maximum number of rows that match the search pattern
                $number_of_rows = $this->getUsersTable()->select($resultingWhereClause)->count();


                // U. Janssen, 6.12.2017 BEGIN
                $userNameField = request_var('user_name', '');
                $userFirmaField = request_var('user_firma', '');
                // U. Janssen, 6.12.2017 END
                //              Redirects the User to the TextCheckTable with the pagenumber
                $view = new ViewModel(array(
                    'form'      => $form,
                    'rowset'    => $rowset,
                    'index'     => $page_number,
                    'max'       => ceil($number_of_rows / 15),
                    'get_array' => $_GET,
                    'user'      => $this->arrUser,
                    'currSite'  => 'showuser',
                    // U. Janssen, 6.12.2017 BEGIN
                    // Bugfix of problem: DETAILED View not working when Search Form Field is not empty
                    // inserted:
                    'get_array'    => array(
                        'user_name'    => $userNameField,
                        'user_adresse' => '',
                        'user_email'   => '',
                        'user_firma'   => $userFirmaField
                    ),
                    'arrAdminMain' => $arrAdminMain
                    // U. Janssen, 6.12.2017 END
                ));

                return $view;
            }
        }
        else
        {

            //      Return a ResultSet Object with all the Userdata from the Database
            return new ViewModel(array(
                'form'         => $form,
                'rowset'       => $arrPage,
                'index'        => $page_number,
                'max'          => ceil($max_page_number / 15),
                'user'         => $this->arrUser,
                'get_array'    => array(
                    'user_name'    => '',
                    'user_adresse' => '',
                    'user_email'   => '',
                    'user_firma'   => ''
                ),
                'arrAdminMain' => $arrAdminMain
            ));
        }
    }

    /**
     *   Handles the Action, which activates Users, so they can login
     */
    public function freischaltenAction()
    {
        //      Gets the Id from the route, which defines the User who will be activated
        $id = $this->params()->fromRoute('id');
        //      Checks if the ID is given
        if ($id)
        {
            //          Changes the data in the Database, so the User can login
            $this->getUsersTable()->update(array('freigeschaltet' => \Auth\Model\Auth::USER_UNBLOCKED), array('id' => $id));

            $user_row = $this->getUsersTable()->select(array('id' => $id));
            $user     = $user_row->current();


            /** @var $strEmail string */
            include_once("public/mails/activateUser.php"); // Datei aus /check/public/mails/ um den E-Mail Text nicht in der Funktion zu haben.

            MailUtil::sendMail($this->getServiceLocator(), $user->user_email, $strEmail, 'Ihr Account wurde freigeschaltet');


            echo "<script>window.history.back();</script>";
            exit;
            if (isset($_SERVER['HTTP_REFERER']))
                $strLastPage = $_SERVER['HTTP_REFERER'];
            else
                $strLastPage = 'http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/1?user_name=&user_adresse=&user_email=&user_firma=';
            return $this->redirect()->toUrl($strLastPage);
        }
    }

    /**
     *   Handles the Action, which blocks Users, so they cannot login
     */
    public function sperrenAction()
    {
        //      Gets the Id from the route, which defines the User who will be blocked
        $id = $this->params()->fromRoute('id');
        //      Checks if the ID is given
        if ($id)
        {
            //          Changes the data in the Database, so the User cannot login anymore
            $this->getUsersTable()->update(array('freigeschaltet' => \Auth\Model\Auth::USER_BLOCKED), array('id' => $id));
            //          Redirects the USer to the View, that shows the User-Table

            if (isset($_SERVER['HTTP_REFERER']))
                $strLastPage = $_SERVER['HTTP_REFERER'];
            else
                $strLastPage = 'http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/1?user_name=&user_adresse=&user_email=&user_firma=';
            return $this->redirect()->toUrl($strLastPage);
        }
    }


    public function changeRoleAction()
    {
        $id = $this->params()->fromRoute('id');
        if ($id)
        {

            $intNewRole = 0;
            $objUser    = $this->getUsersTable()->select(array('id' => $id));
            $objUser    = $objUser->current();

            switch ($objUser->rolle)
            {
            case 0:
            case 1:
                $intNewRole = 7;
                break;

            case 7:
                $intNewRole = 10;
                break;

            case 10:
                $intNewRole = 1;
                break;
            }

            $this->getUsersTable()->update(array('rolle' => $intNewRole), array('id' => $id));


            if (isset($_SERVER['HTTP_REFERER']))
                $strLastPage = $_SERVER['HTTP_REFERER'];
            else
                $strLastPage = 'http://www.stellenanzeigen-texten.de/check/public/homepage/admin/showusers/1?user_name=&user_adresse=&user_email=&user_firma=';
            return $this->redirect()->toUrl($strLastPage);
        }
    }


    /**
     *  Selects all Data from the TextCheck-Tables from the Database
     */
    public function getTextCheckTable($function_name)
    {
        //      Checks if usersTable has got a selection
        if (!$this->textCheckTable)
        {
            //          Creates a Tablegateway for the 'users' table in the Database
            $this->textCheckTable = new TableGateway($function_name, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        }
        return $this->textCheckTable;
    }

    /**
     *  Shows which tables from the Database the User can change
     */
    public function showtextchecktablesAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();
        return new ViewModel(array('user' => $this->arrUser));
    }

    /**
     *   Handles the Action, which shows all Data from a TextCheck Table
     */
    public function showtexttableAction()
    {
        global $intItemsPerPage;
        global $count;
        global $wort_name;
        global $wort_ersatz;
        global $wort_bearbeitet;
        $rowsetToArray       = array();
        $rowsetToArraySearch = array();
        //      Variable, which saves value 1, if any of the typed data in the search-form as found in the database
        $foundInDatabase = 0;
        //      Variable, which switches to true, if any of the search form elements are empty
        $nothing_typed = false;
        //      Saves the maximum page number the database data is split into
        $max_page_number = 0;
        //      Gets the Id from the route, which defines the Words to show
        $function_name = $this->params()->fromRoute('id');
        //      Searches an underscore inside the ID, which seperates the Word-ID from the rest
        $position_under = strpos($function_name, '_');
        //      Searches a hyphen inside the ID, which seperates the function_name from the rest
        $position_binde = strpos($function_name, '-');
        //      Cuts the Word-ID from the ID
        $word_id = substr($function_name, 0, $position_binde - 1);
        //      Cuts the function_name from the ID
        $function = substr($function_name, $position_binde, $position_under);
        //      Cuts the page_number from the ID
        $count    = substr($function_name, -(strlen($function_name) - $position_under - 1));
        $position = strpos($function_name, '_');
        $count    = substr($function_name, -(strlen($function_name) - $position - 1));
        //      Array, which saves 15 Datarows that will be shown on the page
        //      Array, which saves all data that was found in the database
        $arrSearch = array();
        //      Array, which saves all selected data
        $allRows = array();
        //      Items to show per page
        $intItemsPerPage = 50;

        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        //      The Database Tables have got an 'editor_' at the beginning, so you place it before the function_name
        if ($function !== 'passivverben')
        {
            //          Selects all Data from the Table with the name 'editor_' + function_name (Which is defined when the User choses the Table he wants to change
            $rowset          = $this->getTextCheckTable('editor_' . $function)->select(function (Select $select)
            {
                global $count;
                global $intItemsPerPage;

                $select->order('name ASC')->limit($intItemsPerPage)->offset(($count - 1) * $intItemsPerPage);
            });

            $allRows         = $this->getTextCheckTable($function)->select();
            $max_page_number = $allRows->count();
        }
        else
        {
            //          Passivverben doesn't have an 'editor_' at the beginning so it needs an extra Select
            $rowset          = $this->getTextCheckTable($function)->select(function (Select $select)
            {
                global $count;
                global $intItemsPerPage;

                $select->order('name ASC')->limit($intItemsPerPage)->offset(($count - 1) * $intItemsPerPage);
            });
            $allRows         = $this->getTextCheckTable($function)->select();
            $max_page_number = $allRows->count();
        }

        foreach ($rowset as $row)
        {
            array_push($rowsetToArray, $row);
        }
        //      Creates the SearchWordForm
        $form = new SearchWordForm();
        //      Gets the request
        $request = $this->getRequest();
        //      Some tables in the database doesn't have the 'wort_ersatz' column, so you have to check if this parameter is set
        if (isset($_GET['wort_ersatz']))
        {
            $strlen_wort_ersatz = strlen($_GET['wort_ersatz']);
        }
        else
        {
            $strlen_wort_ersatz = false;
        }
        //      Checks if anything was typed into the search form
        if (strlen($_GET['wort_name']) !== 0 || $strlen_wort_ersatz || strlen($_GET['wort_bearbeitet']) !== 0)
        {
            //          Sets the InputFitler, for wrong typing
            $form->setInputFilter(new SearchWordFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                //              Gets the typed value from the 'wort_name' form element
                $wort_name = mb_strtolower(trim(utf8_decode($_GET['wort_name'])));

                //              Checks if the 'wort_ersatz' form element is set
                if (isset($_GET['wort_ersatz']))
                {
                    $wort_ersatz = mb_strtolower(trim(utf8_decode($_GET['wort_ersatz'])));
                }
                //              Gets the typed value from the 'wort_bearbeitet'
                $wort_bearbeitet  = $_GET['wort_bearbeitet'];
                $function_updated = '';
                //              If the used function is passivverben you need to prepend an 'editor' because the database has got a wrong name
                if ($function !== 'passivverben')
                {
                    $function_updated = 'editor_' . $function;
                }

                //              Selectes the form values from the database
                $rowset = $this->getTextCheckTable($function_updated)->select(function (Select $select)
                {
                    //                      Pageindex
                    global $count;
                    //                      Number of Items per Page (Default = 15)
                    global $intItemsPerPage;
                    //                      'wort_name' form element value
                    global $wort_name;
                    //                      'wort_ersatz' form element value
                    global $wort_ersatz;
                    //                      'wort_bearbeitet' form element value
                    global $wort_bearbeitet;
                    //                      Checks if the wort_name form element is empty

                    if (strlen($_GET['wort_name']) !== 0)
                    {
                        //                          'wort_name' is searched in the database
                        $where = $select->where->literal("LOWER(name) LIKE '$wort_name%'");
                    }
                    //                      Checks if the wort_ersatz form element is set, because not all tables have got an 'wort_ersatz' column
                    if (isset($_GET['wort_ersatz']))
                    {
                        //                          Checks if the 'wort_ersatz' form element is empty
                        if (strlen($_GET['wort_ersatz']) !== 0)
                        {
                            //                              A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
                            if (strlen($_GET['wort_name']) !== 0)
                            {
                                //                                  If a statement was executed, you give it another where clause
                                $where = $where->or->where->expression('LOWER(ersatz) LIKE ?', $wort_ersatz . '%');
                            }
                            else
                            {
                                //                              If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                                $where = $select->where->expression('LOWER(name) LIKE ?', $wort_ersatz . '%');
                            }
                        }
                    }
                    //                      Checks if the wort_bearbeitet form element is empty
                    if (strlen($_GET['wort_bearbeitet']) !== 0)
                    {
                        //                          Checks if the wort_ersatz form element is set, because not all tables have got an 'wort_ersatz' column
                        if (isset($_GET['wort_ersatz']))
                        {
                            //                              A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
                            if (strlen($_GET['wort_name']) !== 0 && strlen($_GET['wort_ersatz']) !== 0)
                            {
                                //                                  If a statement was executed, you give it another where clause
                                $where = $where->or->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                            else
                            {
                                //                                  If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                                $where = $select->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                            //                          If 'wort_ersatz' is not set, we just have to check if 'wort_name is empty
                        }
                        else
                        {
                            if (strlen($_GET['wort_name']) !== 0)
                            {
                                //                                  If a statement was executed, you give it another where clause
                                $where = $where->or->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                            else
                            {
                                //                                  If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                                $where = $select->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                        }
                    }
                    //                      Selects the rows with the above given options
                    $select->where($where);
                    //                      Oders the rows alphabetically
                    $select->order('name ASC') //                             Selects only the given number of rows
                        ->limit($intItemsPerPage)//                             Sets the Startpoint for the selection
                        ->offset(($count - 1) * $intItemsPerPage);
                });
                //              Iterates through the ResultSet and makes an Array
                foreach ($rowset as $row)
                {
                    array_push($rowsetToArraySearch, $row);
                }

                //              The following Select is used to count the number of rows
                $rowset_all = $this->getTextCheckTable($function_updated)->select(function (Select $select)
                {
                    //                      Pageindex
                    global $count;
                    //                      Number of Items per Page (Default = 15)
                    global $intItemsPerPage;
                    //                      'wort_name' form element value
                    global $wort_name;
                    //                      'wort_ersatz' form element value
                    global $wort_ersatz;
                    //                      'wort_bearbeitet' form element value
                    global $wort_bearbeitet;
                    //                      Checks if the wort_name form element is empty
                    if (strlen($_GET['wort_name']) !== 0)
                    {
                        //                          'wort_name' is searched in the database
                        $where = $select->where->like('name', $wort_name . '%');
                    }
                    //                      Checks if the wort_ersatz form element is set, because not all tables have got an 'wort_ersatz' column
                    if (isset($_GET['wort_ersatz']))
                    {
                        //                          Checks if the 'wort_ersatz' form element is empty
                        if (strlen($_GET['wort_ersatz']) !== 0)
                        {
                            //                              A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
                            if (strlen($_GET['wort_name']) !== 0)
                            {
                                //                                  If a statement was executed, you give it another where clause
                                $where = $where->or->where->like('ersatz', $wort_ersatz . '%');
                            }
                            else
                            {
                                //                              If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                                $where = $select->where->like('ersatz', $wort_ersatz . '%');
                            }
                        }
                    }
                    //                      Checks if the wort_bearbeitet form element is empty
                    if (strlen($_GET['wort_bearbeitet']) !== 0)
                    {
                        //                          Checks if the wort_ersatz form element is set, because not all tables have got an 'wort_ersatz' column
                        if (isset($_GET['wort_ersatz']))
                        {
                            //                              A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
                            if (strlen($_GET['wort_name']) !== 0 && strlen($_GET['wort_ersatz']) !== 0)
                            {
                                //                                  If a statement was executed, you give it another where clause
                                $where = $where->or->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                            else
                            {
                                //                                  If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                                $where = $select->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                            //                          If 'wort_ersatz' is not set, we just have to check if 'wort_name is empty
                        }
                        else
                        {
                            if (strlen($_GET['wort_name']) !== 0)
                            {
                                //                                  If a statement was executed, you give it another where clause
                                $where = $where->or->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                            else
                            {
                                //                                  If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                                $where = $select->where->like('zuletzt_bearbeitet_von', $wort_bearbeitet . '%');
                            }
                        }
                    }
                    //                      Selects the rows with the above given options
                    $select->where($where);
                });
                //              Gets the number of rows from the 2nd Select
                $max_page_number = $rowset_all->count();
                //              Redirects the User to the TextCheckTable with the pagenumber 1 and the right function_name
                return new ViewModel(array(
                    'form'          => $form,
                    'rowset'        => $rowsetToArraySearch,
                    'index'         => $count,
                    'max'           => ceil($max_page_number / $intItemsPerPage),
                    'function_name' => substr($function_name, 0, $position),
                    'get_array'     => $_GET,
                    'user'          => $this->arrUser
                ));
            }
        }
        //      If the Form is empty, all Rows from the given table will be shown
        else
        {
            //          Returns a new View with the selected Rows, the function name and the page-index
            return new ViewModel(array(
                'form'          => $form,
                'rowset'        => $rowsetToArray,
                'function_name' => substr($function_name, 0, $position),
                'index'         => $count,
                'max'           => ceil(($max_page_number / $intItemsPerPage)),
                'get_array'     => array(
                    'wort_name'       => '',
                    'wort_ersatz'     => '',
                    'wort_bearbeitet' => ''
                ),
                'user'          => $this->arrUser
            ));
        }
    }

    /**
     *   Handles the Action, which updates Word Data
     */
    public function updatewordAction()
    {
        //      Array, which defines the Database tables with a 'ersatz' row
        $ersatz_functions = array(
            'floskeln',
            'anglizismen',
            'textverstaendnis',
            'negativewoerter'
        );
        //      Defines if the 'ersatz' column exists
        $ersatz_exist = false;
        //      Gets the ID-Parameter from the route, which defines the Word that will be updated

        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        $arrPara = explode('_', $this->params()->fromRoute('id'));

        $id            = $arrPara[0];
        $function_name = $arrPara[1];
        $delete        = (!is_null(@$arrPara[2]));


        if (in_array($function_name, $ersatz_functions))
        {
            $ersatz_exist = true;
        }
        //      The Database Tables have got an 'editor_' at the beginning, so you place it before the function_name
        if ($function_name !== 'passivverben')
        {
            $function_name = 'editor_' . $function_name;
        }
        //      Gets the WordForm
        $form    = new WordForm();
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter(new WordFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                //              Selects the typed data
                $data = $form->getData();
                //              Checks if the Update Button was pressed
                if ($data['delete_submit'] === NULL)
                {
                    //                  Checks if the 'ersatz' column should be filled
                    if ($ersatz_exist === false)
                    {
                        unset($data['ersatz']);

                    }
                    else
                    {
                        $data['ersatz'] = utf8_decode($data['ersatz']);
                    }

                    $data['name'] = utf8_decode($data['name']);

                    unset($data['update_submit']);
                    unset($data['delete_submit']);
                    //                  Updates data inside the Database and saves the Identity, that changed the data

                    $arrSQLOldWord = $this->getTextCheckTable($function_name)->select(array('id' => $id));
                    $arrSQLCheck   = $this->getTextCheckTable($function_name)->select(array('name' => $data['name']));

                    $arrSQLOldWord = $arrSQLOldWord->current();

                    if ($function_name !== 'passivverben')
                    {
                        $function_name_ausgebessert = substr($function_name, 7);
                    }
                    else
                    {
                        $function_name_ausgebessert = $function_name;
                    }

                    if (count($arrSQLCheck) > 0 && $arrSQLOldWord['name'] != $data['name'])
                        return new ViewModel(array(
                            'form'          => $form,
                            'rowset'        => $this->getTextCheckTable($function_name)->select(array('id' => $id)),
                            'function_name' => $function_name_ausgebessert,
                            'ersatz_exist'  => $ersatz_exist,
                            'user'          => $this->arrUser,
                            'arrError'      => array('"' . utf8_encode($data['name']) . '" ist bereits in der Datenbank !')
                        ));


                    $this->getTextCheckTable($function_name)->update($data, array('id' => $id));
                    $this->getTextCheckTable($function_name)->update(array('zuletzt_bearbeitet_von' => $this->identity()), array('id' => $id));
                }
                //              Checks if the Delete Button was pressed
                else if ($data['update_submit'] === NULL)
                {
                    //                  Checks if the 'ersatz' column should be deleted
                    if ($ersatz_exist === false)
                    {
                        unset($data['ersatz']);
                    }
                    unset($data['update_submit']);
                    unset($data['delete_submit']);
                    //                  Deletes data inside the Database
                    $this->getTextCheckTable($function_name)->delete($data, array('id' => $id));

                }
                //              Deletes the 'editor_' at the beginning of the function_name to it can be passed on to the view again
                if ($function_name !== 'passivverben')
                {
                    $function_name_ausgebessert = substr($function_name, 7);
                }
                //              Redirects the User to the TextCheckTable with the pagenumber 1 and the right function_name
                return $this->redirect()->toUrl('http://www.stellenanzeigen-texten.de/check/public/homepage/Admin/showtexttable/' . $function_name_ausgebessert . '_1?wort_name=&wort_ersatz=&wort_bearbeitet=');
            }
        }
        else if ($delete)
        {
            header('Content-Type: application/json');
            if ($this->getTextCheckTable($function_name)->delete(array('id' => $id)))
            {
                echo json_encode(array('status' => 'ok'));
            }
            else
            {
                echo json_encode(array('status' => 'error'));
            }
            exit;
        }
        //      Before the User updates or deletes Words, he can see the old data inside the input-fields
        else
        {

            $form->setData($this->getTextCheckTable($function_name)->select(array('id' => $id)));

        }
        //      Deletes the 'editor_' at the beginning of the function_name to it can be passed on to the view again
        if ($function_name !== 'passivverben')
        {
            $function_name_ausgebessert = substr($function_name, 7);
        }
        else
        {
            $function_name_ausgebessert = $function_name;
        }
        //      Checks if the function is inside the Array, with the tables that have the 'ersatz' column
        if (in_array($function_name_ausgebessert, $ersatz_functions))
        {
            $ersatz_exist = true;
        }
        else
        {
            $ersatz_exist = false;
        }
        //      Returns new View with the WordForm, the old data from the Database, the ID , the function_name and a variable that says is 'ersatz' colimn exists
        return new ViewModel(array(
            'form'          => $form,
            'rowset'        => $this->getTextCheckTable($function_name)->select(array('id' => $id)),
            'function_name' => $function_name_ausgebessert,
            'ersatz_exist'  => $ersatz_exist,
            'user'          => $this->arrUser
        ));

        //  $this->getTextCheckTable()->update($data, array('id' => $id));
    }

    /**
     *   Handles the Action, which creates new Words
     */
    public function createwordAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        //      Array, which defines the Database tables with a 'ersatz' row
        $ersatz_functions = array(
            'floskeln',
            'anglizismen',
            'textverstaendnis',
            'negativewoerter'
        );
        //      Gets the ID-function from the route, which defines the Word that will be updated
        $function_name = $this->params()->fromRoute('id');
        //      Defines if the 'ersatz' column exists
        $ersatz_exist = false;
        //      Checks if the function is inside the Array, with the tables that have the 'ersatz' column
        if (in_array($function_name, $ersatz_functions))
        {
            $ersatz_exist = true;
        }
        //      The Database Tables have got an 'editor_' at the beginning, so you place it before the function_name
        if ($function_name !== 'passivverben')
        {
            $function_name = 'editor_' . $function_name;
        }
        //      Gets the WordAddForm
        $form    = new WordAddForm();
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter(new WordAddFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                //              Selects the typed data
                $data = $form->getData();

                $arrSQLCheck = $this->getTextCheckTable($function_name)->select(array('name' => $data['name']));

                if ($function_name !== 'passivverben')
                {
                    $function_name_ausgebessert = substr($function_name, 7);
                }
                else
                {
                    $function_name_ausgebessert = $function_name;
                }

                if (count($arrSQLCheck) > 0)
                    return new ViewModel(array(
                        'form'          => $form,
                        'function_name' => $function_name_ausgebessert,
                        'user'          => $this->arrUser,
                        'arrError'      => array('Das Wort "' . utf8_encode($data['name']) . '" ist bereits in der Datenbank !')
                    ));

                //              Checks if the 'ersatz' column should be filled
                if ($ersatz_exist === false)
                {
                    unset($data['ersatz']);
                }
                else
                {
                    $data['ersatz'] = utf8_decode($data['ersatz']);
                }

                $data['name'] = utf8_decode($data['name']);

                unset($data['insert_submit']);
                //              Sets the Identity of the User, who creates the Word
                $data['zuletzt_bearbeitet_von'] = $this->identity();
                //              Inserts all data into the Database
                $this->getTextCheckTable($function_name)->insert($data);
                //              Deletes the 'editor_' at the beginning of the function_name to it can be passed on to the view again
                if ($function_name !== 'passivverben')
                {
                    $function_name = substr($function_name, 7);
                }
                //              Redirects the User to the TextCheckTable with the pagenumber 1 and the right function_name
                return $this->redirect()->toUrl("http://www.stellenanzeigen-texten.de/check/public/homepage/admin/createword/" . $function_name);
            }
        }
        //      Deletes the 'editor_' at the beginning of the function_name to it can be passed on to the view again
        if ($function_name !== 'passivverben')
        {
            $function_name_ausgebessert = substr($function_name, 7);
        }
        else
        {
            $function_name_ausgebessert = $function_name;
        }
        //      Checks if the function is inside the Array, with the tables that have the 'ersatz' column
        if (in_array($function_name_ausgebessert, $ersatz_functions))
        {
            $ersatz_exist = true;
        }
        else
        {
            $ersatz_exist = false;
        }
        //      Returns new View with the WordForm, the old data from the Database, the ID , the function_name and a variable that says is 'ersatz' colimn exists
        return new ViewModel(array(
            'form'          => $form,
            'function_name' => $function_name_ausgebessert,
            'user'          => $this->arrUser
        ));
    }

    public function editorAction()
    {
        $result             = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser      = $result->current();
        $objTextResult      = $this->getStandardTextTable()->select();
        $objReportResult    = $this->getBehaviorProfileTable('editor_reports', false)->select(function(Select $select){
            $select->order('id DESC');
        });

        $arrText = array();
        foreach ($objTextResult as $row)
            $arrText[$row->id] = array(
                'id'   => $row->id,
                'name' => $row->name
            );


        $arrReports         = array();
        foreach ($objReportResult as $row)
            $arrReports[$row->id] = array(
                'id'        => $row->id,
                'title'     => $row->title,
                'company'   => $row->company
            );

        return new ViewModel(array(
            'user'            => $this->arrUser,
            'arrStandardText' => $arrText,
            'arrReports'      => $arrReports
        ));
    }

    public function editbehaviorprofileAction()
    {
        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        // Lädt alle Länder aus der Datenbank
        $objSelectCountry = $this->getBehaviorProfileTable('country')->select(function (Select $select)
        {
            $select->order('sort ASC')->order('name ASC');
        });

        $arrCountry = array();
        foreach ($objSelectCountry as $row)
        {
            array_push($arrCountry, $row);
        }

        // Lädt alle Funktionen aus der Datenbank
        $objSelectFunction = $this->getBehaviorProfileTable('function')->select(function (Select $select)
        {
            $select->order('sort ASC')->order('name ASC');
        });

        $arrFunction = array(
            0 => 'Allgemein'
        );
        foreach ($objSelectFunction as $row)
        {
            $arrFunction[$row['id']] = $row['name'];
        }

        // Lädt alle Ergebnise aus der Datenbank
        $objSelectResults = $this->getBehaviorProfileTable('answer')->select(function (Select $select)
        {
            $select->order('identifier ASC')->order('function_id ASC');
        });

        $arrResults = array();
        foreach ($objSelectResults as $row)
        {
            $arrResults[$row->identifier][$row->function_id] = $row;
        }
        //echo "<pre>";
        //print_r($arrResults);
        //exit;
        return new ViewModel(array(
            'user'        => $this->arrUser,
            'arrCountry'  => $arrCountry,
            'arrFunction' => $arrFunction,
            'arrResults'  => $arrResults
        ));
    }

    public function expertcheckAction()
    {
        if (!$this->accessCheck())
            exit;

        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        $objMySQL        = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $objResult       = $objMySQL->query('SELECT ec.*, u.`user_vorname`, u.`user_nachname`, u.`user_email` FROM `expert_check` ec LEFT JOIN `users` u ON (ec.`user_id` = u.`id`) WHERE ec.`archiv` != 1 ORDER BY ec.`create_at` DESC;', array());
        $arrExpertChecks = array();

        foreach ($objResult as $row)
            $arrExpertChecks[$row->id] = $row;


        $objResult         = $objMySQL->query('SELECT ec.*, u.`user_vorname`, u.`user_nachname`, u.`user_email` FROM `expert_check` ec LEFT JOIN `users` u ON (ec.`user_id` = u.`id`) WHERE ec.`archiv` = 1 ORDER BY ec.`create_at` DESC;', array());
        $arrExpertChecksAR = array();

        foreach ($objResult as $row)
            $arrExpertChecksAR[$row->id] = $row;

        return new ViewModel(array(
            'user'              => $this->arrUser,
            'arrExpertChecks'   => $arrExpertChecks,
            'arrExpertChecksAR' => $arrExpertChecksAR
        ));
    }

    public function expertcheckactionAction()
    {
        if (!$this->accessCheck())
            exit;

        $objMySQL  = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $strAction = (isset($_GET['action']) ? $_GET['action'] : '');
        $intId     = (isset($_GET['id']) ? $_GET['id'] : '');


        $objResult = $objMySQL->query('SELECT * FROM `expert_check` WHERE `id` = ?', array($intId));
        $arrResult = $objResult->current();

        if ($arrResult == false)
            die('Invalid ID');

        unset($objResult);

        switch ($strAction)
        {
        case 'dl':
            $strPath = getcwd() . "/public/upload/expertcheck/" . $arrResult['filename'];

            if (!file_exists($strPath))
                die('File not found');

            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $arrResult['filename'] . "\"");
            echo readfile($strPath);
            break;
        case 'ar':
            $objMySQL->query('UPDATE `expert_check` SET `archiv` = 1 WHERE `id` = ?', array($arrResult['id']));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            break;
        }
        exit;
    }

    public function contactAction()
    {
        if (!$this->accessCheck())
            exit;

        $result        = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $this->arrUser = $result->current();

        $objMySQL    = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $objResult   = $objMySQL->query('SELECT c.*, u.`user_vorname`, u.`user_nachname`, u.`user_email` FROM `contact` c LEFT JOIN `users` u ON (c.`user_id` = u.`id`) ORDER BY c.`create_at` DESC;', array());
        $arrContacts = array();

        foreach ($objResult as $row)
            $arrContacts[$row->id] = $row;

        return new ViewModel(array(
            'user'        => $this->arrUser,
            'arrContacts' => $arrContacts
        ));
    }

    public function contactactionAction()
    {
        if (!$this->accessCheck())
            exit;

        $intId     = (isset($_GET['id']) ? $_GET['id'] : '');
        $strAction = (isset($_GET['action']) ? $_GET['action'] : '');

        if (is_numeric($intId))
        {
            switch ($strAction)
            {
            case 'rm':
                $objMySQL = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $objMySQL->query('DELETE FROM `contact` WHERE `id` = ?', array($intId));
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            }
        }
        else
        {
            echo "Invalid Id";
        }
        exit;
    }

    public function ajaxSendMailAction()
    {
        if (!$this->accessCheck() || count($_POST) != 3 || $_POST['id'] <= 0)
        {
            echo json_encode(array(
                'status' => 'error',
                'text'   => array('Es ist ein Server Fehler aufgetreten, bitte kontaktieren Sie einen Techniker.')
            ));
            exit;
        }

        $arrError = array();
        $user_row = $this->getUsersTable()->select(array('id' => $_POST['id']));
        $user     = $user_row->current();

        if ($user == false)
            $arrError[] = "Der ausgewählte Nutzer konnte nicht gefunden werden.";

        if (empty($_POST['subject']))
            $arrError[] = "Die Nachricht muss ein Betreff besitzen.";

        if (empty($_POST['content']))
            $arrError[] = "Die Nachricht an den Nutzer darf nicht leer sein.";

        if (count($arrError) > 0)
        {
            echo json_encode(array(
                'status' => 'error',
                'text'   => $arrError
            ));
            exit;
        }

        $strKey     = $user->user_hashid;
        $strSubject = $_POST['subject'];
        $strContent = nl2br($_POST['content']);

        $strContent = str_replace("[FIRMA]", $user->user_firmenname, $strContent);
        $strContent = str_replace("[ANREDE]", (empty($user->user_titel) ? $user->user_anrede : $user->user_anrede . " " . $user->user_titel), $strContent);
        $strContent = str_replace("[VORNAME]", $user->user_vorname, $strContent);
        $strContent = str_replace("[NACHNAME]", $user->user_nachname, $strContent);
        $strContent = str_replace("[EMAIL]", $user->user_email, $strContent);
        $strContent = str_replace("[CONFIRM_LINK]", "<a href='http://www.stellenanzeigen-texten.de/check/public/auth/registration/confirm-email/$strKey' style='color: #F00;'>http://www.stellenanzeigen-texten.de/check/public/auth/registration/confirm-email/$strKey</a>", $strContent);

        $strContent = $strContent;

        /** @var $strEmail string */
        // dieses include darf nicht woanders hin verschoben werden, da evtl. in der include Datei Variablen
        // aus diesem scope hier verwendet werden
        include_once("public/mails/customAdminMail.php"); // Datei aus /public/mails/ um den E-Mail Text nicht in der Funktion zu haben.

        MailUtil::sendMail($this->getServiceLocator(), $user->user_email, $strEmail, $_POST['subject']);

        echo json_encode(array('status' => 'ok'));
        exit;
    }

    public function ajaxEditAnswerTextAction()
    {
        if (!$this->accessCheck())
            exit;

        global $arrForm;
        $arrForm = (count($_POST) > 0 ? $_POST : array());

        if (count($arrForm) <= 0)
            exit;

        $this->getBehaviorProfileTable('answer')->update(array(
            $arrForm['type'] => utf8_decode($arrForm['text'])
        ), array(
            'identifier'  => $arrForm['identifier'],
            'function_id' => $arrForm['function']
        ));

        echo json_encode(array('status' => 'ok'));
        exit;
    }

    public function ajaxStandardTextAction()
    {
        if (!$this->accessCheck())
            exit;

        $arrForm = (count($_POST) > 0 ? $_POST : array());

        if (count($arrForm) <= 0)
            exit;

        $objText = $this->getStandardTextTable()->select(array('id' => $arrForm['id']));
        $arrText = $objText->current();

        echo utf8_encode($arrText->content);

        exit;
    }

    public function ajaxSaveReportAction()
    {
        if (!$this->accessCheck())
            exit;

        $arrForm = (count($_POST) > 0 ? $_POST : array());

        if (count($arrForm) <= 0)
            exit;

        if($arrForm['id'] == 0)
            unset($arrForm['id']);

        foreach($arrForm as $intKey => $strValue)
            $arrForm[$intKey] = utf8_decode($strValue);

        $objReportTable = $this->getBehaviorProfileTable('editor_reports', false);

        if(isset($arrForm['id']))
        {
            $intId = $arrForm['id'];
            unset($arrForm['id']);

            $objReportTable->update($arrForm, array('id' => $intId));
            echo json_encode(array('status' => 'ok', 'id' => $intId));
        }
        else
        {
            $objReportTable->insert($arrForm);
            echo json_encode(array('status' => 'ok', 'id' => $objReportTable->lastInsertValue));
        }

        exit;
    }

    public function ajaxGetReportAction()
    {
        if (!$this->accessCheck())
            exit;

        $arrForm = (count($_POST) > 0 ? $_POST : array());

        if (count($arrForm) <= 0)
            exit;

        $objReportResult    = $this->getBehaviorProfileTable('editor_reports', false)->select(array('id' => $_POST['id']));
        $row                = $objReportResult->current();

        echo json_encode(array(
            'id'        => $row->id,
            'deckpage'  => $row->deckpage,
            'lastpage'  => $row->lastpage,
            'title'     => utf8_encode($row->title),
            'company'   => utf8_encode($row->company),
            'contact'   => utf8_encode($row->contact),
            'content'   => utf8_encode($row->content),
            'footer'    => utf8_encode($row->footer),
        ));
        exit;
    }

    public function ajaxDeleteReportAction()
    {
        if (!$this->accessCheck())
            exit;

        $arrForm = (count($_POST) > 0 ? $_POST : array());

        if (count($arrForm) <= 0)
            exit;

        $this->getBehaviorProfileTable('editor_reports', false)->delete(array('id' => $_POST['id']));
        echo json_encode(array('status' => 'ok'));
        exit;
    }

    private function accessCheck()
    {
        if ($this->identity() == NULL)
            return false;

        $result  = $this->getUsersTable()->select(array('user_email' => $this->identity()));
        $arrUser = $result->current();

        return (isset($arrUser) && $arrUser != false && $arrUser->rolle > 99);
    }

    /**
     * @param $tableName
     * @param bool $behaviorProfileTable
     * @return mixed
     */
    private function getBehaviorProfileTable($tableName, $behaviorProfileTable = true)
    {
        $tableName = mb_strtolower($tableName);
        // Überprüft ob die Tablelle bereits geladen wurde
        if (!isset($this->arrBehaviorProfileTable[$tableName]))
        {
            // Ladet die Tabelle aus der Datenbank ins Array
            $this->arrBehaviorProfileTable[$tableName] = new TableGateway(($behaviorProfileTable ? 'behaviorprofile_' : '') . $tableName, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        }
        // Gibt die Tabelle zurück
        return $this->arrBehaviorProfileTable[$tableName];
    }

    /**
     * create where clause for user name and user_firma
     * @param $column
     * @param null $pWhere
     * @param null $pSelect
     * @return mixed
     */
//    private function createWhereClauseForUserFirma($pUser_firma_field, $pWhere = null, $pSelect = null)
//    {
//        $localWhere = $pWhere;
//        $localSelect = $pSelect;
//        if (strlen($_GET['user_firma']) !== 0) {
//            //  A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
//            if (strlen(request_var('user_name', '')) !== 0 && strlen(request_var('user_email', '')) !== 0) {
//                // If a statement was executed, you give it another where clause
//                $where = $localWhere->or->where->like('user_firmenname', $pUser_firma_field . '%');
//            } else {
//                // If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
//                $where = $pSelect->where->like('user_firmenname', $pUser_firma_field . '%');
//            }
//            return $localWhere;
//        }
//    }
//    private function createWhereClauseForUserEMail($pUser_firma_field, $pWhere = null, $pSelect = null)
//    {
//        $localWhere = $pWhere;
//        $localSelect = $pSelect;
//        /*                    // Checks if the user_email form element is empty
//                           if (strlen(request_var('user_email', '')) !== 0)
//                           {
//                               // A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
//                               if (strlen(request_var('user_name', '')) !== 0)
//                               {
//                                   // If a statement was executed, you give it another where clause
//                                   $where = $where->or->where->like('user_email', $user_email_field . '%');
//                               }
//                               // If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
//                               else
//                               {
//                                   $where = $select->where->like('user_email', $user_email_field . '%');
//                               }
//                           }*/
//    }
    // [Checks if the user_email form element is empty]
    /*                    if (strlen(request_var('user_email', '')) !== 0)
                        {
                            //  A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
                            if (strlen(request_var('user_name', '')) !== 0)
                            {
                                // If a statement was executed, you give it another where clause
                                $where = $where->or->where->like('user_email', $user_email_field . '%');
                            }
                            // If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
                            else
                            {
                                $where = $select->where->like('user_email', $user_email_field . '%');
                            }
                        }*/
    // the subsequent call is ONLY required for calculation of number of pages
//                $rowset_all = $this->getUsersTable()->select(function (Select $select)
//                {
//                    global $page_number;
//                    global $intItemsPerPage;
//
//                    global $entry_field;
//                    global $user_name_field;
//                    global $user_adresse_field;
//                    global $user_email_field;
//                    global $user_firma_field;
//                    // Checks if the user_name form element is empty
//                    if (strlen(request_var('user_name', '')) !== 0)
//                    {
//                        // construct where clause from multiple words typed in by the user in form field 'Name'
//                        $where = $select->where->like('user_vorname', $user_name_field[0] . '%')->or->where->like('user_nachname', $user_name_field[0] . '%')->or->where->like('user_titel', $user_name_field[0] . '%')->or->where->like('user_anrede', $user_name_field[0] . '%');
//                        // (A sql statement cannot start with a 'or->', so the first word is searched in the database)
//
//                        // create where clause from intType (user blocked etc.)
//                        global $intType;
//                        $this->createWhereClauseFromIntType($intType,$where);
///*                        if ($intType == 1)
//                            $where->where->equalTo('user_confirmed', '1');
//                        else if ($intType == 2)
//                            $where->where->equalTo('user_confirmed', '0')->equalTo('freigeschaltet', \Auth\Model\Auth::USER_LOCKED);
//                        else if ($intType == 3)
//                            $where->where->equalTo('freigeschaltet', \Auth\Model\Auth::USER_BLOCKED);*/
//                        // If the User typed more than one word into the form element, all of them are searched in the database
//                        foreach ($user_name_field as $index => $user_name)
//                        {
//                            if ($index !== 0)
//                            {
//                                $where = $where->or->where->like('user_vorname', $user_name . '%')->or->where->like('user_nachname', $user_name . '%')->or->where->like('user_titel', $user_name . '%')->or->where->like('user_anrede', $user_name . '%');
//                            }
//                        }
//                    }
//
//                    if (strlen(@$_GET['id']) !== 0)
//                    {
//                        $where = $where->or->where->like('id', $entry_field);
//                    }
//
//
//                    /**************** check for email has been removed to the bottom (unused),
//                    * please see private function createWhereClauseForUserEMail() at the bottom
//                    ************/
//                    // Uwe Janssen 6.12.2017 END
//                    //  Checks if the user_firma form element is empty
//                    if (strlen($_GET['user_firma']) !== 0)
//                    {
//                        // A sql statement cannot start with '->or', that's why you need to check if a statement was executed before
//                        // Uwe Janssen 6.12.2017 BEGIN
//// Originally:                        if (strlen(request_var('user_name', '')) !== 0 && strlen(request_var('user_email', '')) !== 0)
//                        if (strlen(request_var('user_name', '')) !== 0 && strlen(request_var('user_firma', '')) !== 0)
//                            // Uwe Janssen 6.12.2017 END
//                        {
//                            // TODO: put user at the end of the where clause (we want: where firma = 'firma' and userName like xx or xx or xy or xz)
//
//                            $where = $where->where->like('user_firmenname', $user_firma_field . '%');
//                            // If a statement was executed, you give it another where clause
//                            // original: $where = $where->where->like('user_firmenname', $user_firma_field . '%');
//                        }
//
//                        else
//                        {
//                            // If this is the first sql statement, we use '$select->where' not '$where->or->where' and search for the first word from the form element
//                            $where = $select->where->like('user_firmenname', $user_firma_field . '%');
//                        }
//                    }
//                    // Selects the rows with the above given options
//                    $select->where($where);
//  //                  $mySQL = $select->getSqlString();
//  //                  var_dump($mySQL);
//  //                  die;
//               });
    private function createWhereClauseFromIntType($pIntType, /*where or select */$pWhereOrSelect ) {
        if ($pIntType == 1)
            $pWhereOrSelect->where->equalTo('user_confirmed', '1');
// org:                 $select->where->equalTo('user_confirmed', '1')->notEqualTo('freigeschaltet', \Auth\Model\Auth::USER_BLOCKED);
        else if ($pIntType == 2)
            $pWhereOrSelect->where->equalTo('user_confirmed', '0')->equalTo('freigeschaltet', \Auth\Model\Auth::USER_LOCKED);
        else if ($pIntType == 3)
            $pWhereOrSelect->where->equalTo('freigeschaltet', \Auth\Model\Auth::USER_BLOCKED);

    }
}