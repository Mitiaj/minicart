<?php

/**
 * This is the model class for table "project_user".
 *
 * The followings are the available columns in table 'project_user':
 * @property integer $Project_User_Id
 * @property integer $Project_Id
 * @property integer $User_Id
 * @property string $Added_Date
 * @property integer $User_Type
 */
class ProjectUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'project_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Project_Id, User_Id, User_Type', 'numerical', 'integerOnly'=>true),
			array('Added_Date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Project_User_Id, Project_Id, User_Id, Added_Date, User_Type', 'safe', 'on'=>'search'),
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
            'Project'=>array(self::BELONGS_TO, 'Project','Project_Id'),
            'User'=>array(self::BELONGS_TO,'User', 'User_Id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Project_User_Id' => 'Project User',
			'Project_Id' => 'Project',
			'User_Id' => 'User',
			'Added_Date' => 'Added Date',
			'User_Type' => 'User Type',
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

		$criteria->compare('Project_User_Id',$this->Project_User_Id);
		$criteria->compare('Project_Id',$this->Project_Id);
		$criteria->compare('User_Id',$this->User_Id);
		$criteria->compare('Added_Date',$this->Added_Date,true);
		$criteria->compare('User_Type',$this->User_Type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProjectUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
