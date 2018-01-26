<?php
namespace Homepage\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Auth implements InputFilterAwareInterface
{
    public $user_passwort;
    public $user_email;
    public $user_firmenname;
    public $user_anrede;
    public $user_titel;
    public $user_vorname;
    public $user_nachname;
    public $user_land;
    public $user_strasse;
    public $user_hausnummer;
    public $user_plz;
    public $user_ort;
    public $user_laendervorwahl;
    public $user_vorwahl;
    public $user_telefon;
    
    const ROLE_ADMIN = 100;
    
	// Hydration
	// ArrayObject, or at least implement exchangeArray. For Zend\Db\ResultSet\ResultSet to work
    public function exchangeArray($data) 
    {
        $this->user_passwort     = (!empty($data['user_passwort'])) ? $data['user_passwort'] : null;
        $this->user_email = (!empty($data['user_email'])) ? $data['user_email'] : null;
        $this->user_firmenname = (!empty($data['user_firmenname'])) ? $data['user_firmenname'] : null;
        $this->user_anrede = (!empty($data['user_anrede'])) ? $data['user_anrede'] : null;
        $this->user_titel = (!empty($data['user_titel'])) ? $data['user_titel'] : null;
        $this->user_vorname = (!empty($data['user_vorname'])) ? $data['user_vorname'] : null;
        $this->user_nachname = (!empty($data['user_nachname'])) ? $data['user_nachname'] : null;
        $this->user_land = (isset($data['user_land'])) ? $data['user_land'] : null;
        $this->user_strasse = (!empty($data['user_strasse'])) ? $data['user_strasse'] : null;
        $this->user_hausnummer = (!empty($data['user_hausnummer'])) ? $data['user_hausnummer'] : null;
        $this->user_plz = (!empty($data['user_plz'])) ? $data['user_plz'] : null;
        $this->user_ort = (!empty($data['user_ort'])) ? $data['user_ort'] : null;
        $this->user_laendervorwahl = (!empty($data['user_laendervorwahl'])) ? $data['user_laendervorwahl'] : null;
        $this->user_vorwahl = (!empty($data['user_vorwahl'])) ? $data['user_vorwahl'] : null;
        $this->user_telefon = (isset($data['user_telefon'])) ? $data['user_telefon'] : null;
    }	

	// Extraction. The Registration from the tutorial works even without it.
	// The standard Hydrator of the Form expects getArrayCopy to be able to bind
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
	
	
	protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_passwort',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }	
}