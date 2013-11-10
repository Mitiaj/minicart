<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $User_Id
 * @property integer $User_Type
 * @property integer $Created_By
 * @property string $First_Name
 * @property string $Last_Name
 * @property string $Email
 * @property string $Password
 * @property string $Date_Added
 * @property integer $Status
 * @property string $Personal_Note
 * @property string $AllowOpenPost
 * @property string $Profile_Picture
 * @property integer $user_lang
 */
class User extends CActiveRecord
{
    public $verifyCode;
    public $staySignedIn;
    public $passwd2;
    public $roles;
    
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_users';
	}
    public function rules() {
         return array(
            // логин, пароль не должны быть больше 128-и символов, и меньше трёх
            array('Email, Password', 'length', 'max'=>128, 'min' => 3),
            // логин, пароль не должны быть пустыми
            array('Email, Password', 'required'),
            array('Password', 'authenticate', 'on' => 'login'),
            // для сценария registration поле passwd должно совпадать с полем passwd2
            array('Password', 'compare', 'compareAttribute'=>'passwd2', 'on'=>'registration'),
             array('Password, Email','safe'),
            // правило для проверки капчи что капча совпадает с тем что ввел пользователь
           // array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
            //array('Email', 'match', 'pattern' => '/^[A-Za-z0-9А-Яа-я\s,]+$/u','message' => 'Логин содержит недопустимые символы.'),
            
             );
    }
    public function authenticate($attribute,$params)
    {
         if(!$this->hasErrors())
        {
            $identity= new UserIdentity($this->Email, $this->Password);

             $identity->authenticate();   
                switch($identity->errorCode)
                {
                    // Если ошибки нету...
                     case UserIdentity::ERROR_NONE: {
                         if(!empty($this->staySignedIn))
                         {
                              Yii::app()->user->login($identity, 3600*24*30);
                              
                         }
                         else
                         {
                             Yii::app()->user->login($identity, 0);
                         }
                        break;
                    }
                    case UserIdentity::ERROR_USERNAME_INVALID: {
                        $this->addError('Email','User not found!');
                        break;
                    }
                     case UserIdentity::ERROR_PASSWORD_INVALID: {
                        $this->addError('Password','Password incorrect!');
                         break;
                    }
                }
        }

    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'User id',
            'first_name' => 'First name',
            'last_name'=> 'Last name',
            'email'=> 'E-mail',
            'password' => 'Password',
            'salt' => 'Password salt',
            'profile_picture' => 'Profile picture',
            'user_type' => 'User type',
            'registration_date' => 'Date of registration',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
        $criteria->compare('first_name',$this->first_name);
        $criteria->compare('last_name',$this->last_name);
        $criteria->compare('email',$this->email);
        $criteria->compare('password',$this->password);
        $criteria->compare('salt',$this->salt);
        $criteria->compare('profile_picture',$this->profile_picture);
        $criteria->compare('user_type',$this->user_type);
        $criteria->compare('registration_date',$this->registration_date);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
   public function relations()
	
    {
        parent::relations();
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
        return array(
            'User_Id'=>array(self::HAS_MANY,'ProjectUser','User_Id'),
            );
    }
}
