<?php
class UserIdentity extends CUserIdentity
 {
     private $_id;
     
     public function authenticate()
     {

         $record=TblUsers::model()->findByAttributes(array('email'=>$this->username));
         if($record===null)
             $this->errorCode=self::ERROR_USERNAME_INVALID;
         else if($record->password !== crypt($this->password,$record->password))
             $this->errorCode=self::ERROR_PASSWORD_INVALID;
         else
         {
             $this->_id=$record->id;
             switch ($record->user_type) {
             case 1:
                 $this->setState('roles','Head Admin');
                 $this->setState('roleId',1);
                 $this->setState('team',1);
                 $this->setState('canEdit',1);
                 break;
             case 2:
                 $this->setState('roles','Administrator');
                 $this->setState('roleId',2);
                 $this->setState('team',1);
                 $this->setState('canEdit',1);
                 break;
             case 3:
                 $this->setState('roles','Account manager');
                 $this->setState('roleId',3);
                 $this->setState('team',1);
                 break;
             case 4:
                 $this->setState('roles','Developer');
                 $this->setState('roleId',4);
                 $this->setState('team',1);
                 break;
             case 5:
                 $this->setState('roles','Client');
                 $this->setState('roleId',5);
                 break;
             case 6:
                 $this->setState('roles','Designer');
                 $this->setState('roleId',6);
                 $this->setState('team',1);
                 break;
             }
             $this->errorCode=self::ERROR_NONE;
             $this->addLastLoginDate($this->_id);

         }
         return !$this->errorCode;
         
     }
    public function addLastLoginDate($iUserId)
    {
        $oCurrentUser = TblUsers::model()->findByPk($iUserId);
        $oCurrentUser->last_login = date('Y-m-d H:i:s');
        $oCurrentUser->save();
    }
  
     public function getId()
     {
         return $this->_id;
     }
 }