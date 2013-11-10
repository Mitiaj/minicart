<?php
Class WebUser extends CWebUser
{
    public function checkAccess($operation, $params=array())
    {
        if (empty($this->id)) {
            return false;
        }
        $role = $this->getState("roles");
        if (($role === "Head Admin") || ($role === "Administrator")) {
            return true; 
        }
        if ((in_array($role, array('Account manager','Developer','Designer'))) && (in_array($operation, array('Account manager','Developer','Designer'))))
            return true;
        
           return ($operation === $role);
    }
}