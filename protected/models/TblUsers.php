<?php

/**
 * This is the model class for table "tbl_users".
 *
 * The followings are the available columns in table 'tbl_users':
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $profile_picture
 * @property string $user_type
 * @property string $registration_date
 *
 * The followings are the available model relations:
 * @property TblRelProjectUsers[] $tblRelProjectUsers
 * @property TblTasks[] $tblTasks
 */
class TblUsers extends CActiveRecord
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

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password', 'required'),
			array('password', 'length', 'max'=>150),
			array('email', 'length', 'max'=>60),
			array('password', 'safe'),
            array('password', 'authenticate', 'on' => 'login'),
            //array('password', 'compare', 'compareAttribute'=>'passwd2', 'on'=>'registration'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, first_name, last_name, email, registration_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tblRelProjectUsers' => array(self::HAS_MANY, 'TblRelProjectUsers', 'user_id'),
			'tblTasks' => array(self::HAS_MANY, 'TblTasks', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'User`s id',
			'first_name' => 'First name',
			'last_name' => 'Last name',
			'email' => 'E-mail',
			'password' => 'Pasword',
			'salt' => 'Salt of password',
			'profile_picture' => 'Profile Picture',
			'user_type' => 'User Type',
			'registration_date' => 'Registration Date',
            'last_login' => 'Last login'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('profile_picture',$this->profile_picture,true);
		$criteria->compare('user_type',$this->user_type,true);
		$criteria->compare('registration_date',$this->registration_date,true);
        $criteria->compare('last_login',$this->last_login,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function authenticate($attribute,$params)
    {
         if(!$this->hasErrors())
        {
            $identity= new UserIdentity($this->email, $this->password);

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
                        $this->addError('email','User not found!');
                        break;
                    }
                     case UserIdentity::ERROR_PASSWORD_INVALID: {
                        $this->addError('password','Password incorrect!');
                         break;
                    }
                }
        }

    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public function getProjectCount($iUserId)
    {
        return Yii::app()->db->createCommand("SELECT count(`tbl_rel_project_users`.`id`) from `tbl_rel_project_users`,`tbl_projects` WHERE `user_id` =:uid AND `is_archived` = '0' AND `tbl_rel_project_users`.`id` = `tbl_projects`.`id`")->queryScalar(array(':uid' => $iUserId));
    }
    public function getTaskCount($iUserId)
    {
        return Yii::app()->db->createCommand("SELECT count(`id`) from `tbl_tasks` WHERE `user_id` = :uid AND `completed` = '0'")->queryScalar(array(':uid' => $iUserId));
    }
}
