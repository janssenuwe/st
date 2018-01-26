<?php
    
namespace Homepage\Form;

use Zend\Form\Form;

class IndexForm extends Form{
    
    public function __construct($name = null){
        
        parent::__construct('homepage');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
                'label' => 'Willkommen auf der Seite des Aicovo Editors! Zögern Sie nicht lange, probieren Sie ihn aus und machen Sie Ihren Text dadurch professionell'
            )
        );
    }
}    
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
