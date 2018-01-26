<?php
namespace Auth\Form;

use Zend\Form\Form;

class ForgottenPasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
		
        $this->add(array(
            'name' => 'user_email',
            'attributes' => array(
                'type'  => 'email',
                'class' => 'uk-width-1-1'
            ),
            'options' => array(
                'label' => 'E-mail',
                
            ),
        ));	
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'rofl',
                'type'  => 'submit',
                'id'    => 'submitbutton',
                'class' => 'uk-width-1-1 uk-button',

            ),
        )); 
    }
}