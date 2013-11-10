<?php
class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe = false;
    
    public function rules() {
        parent::rules();
         return array(
            array('username, password', 'required', 'on'=>'login'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }
    /**
     * authenticates the user
     * @param type $atribute
     * @param type $params
     * 
     */
    public function authenticate($atribute, $params)
    {
        
        /*$this->_identity = new UserIdentity($this->username,  $this->password);
       if( !$this->_identity->authenticate())
       {
           $this->addError('password','Incorect username or password' );
       }*/
    }
}
?>
