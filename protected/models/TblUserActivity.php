<?php

/**
 * This is the model class for table "tbl_user_activity".
 *
 * The followings are the available columns in table 'tbl_user_activity':
 * @property string $id
 * @property string $user_id
 * @property string $activity_type
 * @property string $activity_text
 * @property string $project_id
 * @property string $update_time
 *
 * The followings are the available model relations:
 * @property TblUsers $user
 * @property TblProjects $project
 * @property TblUserNotifications[] $tblUserNotifications
 */
class TblUserActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, activity_type, activity_text, project_id, update_time', 'required'),
			array('user_id, project_id', 'length', 'max'=>9),
			array('activity_type', 'length', 'max'=>20),
			array('activity_text', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, activity_type, activity_text, project_id, update_time', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'TblUsers', 'user_id'),
			'project' => array(self::BELONGS_TO, 'TblProjects', 'project_id'),
			'tblUserNotifications' => array(self::HAS_MANY, 'TblUserNotifications', 'activity_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => 'id of user',
			'activity_type' => 'Activity Type',
			'activity_text' => 'Activity Text',
			'project_id' => 'Project',
			'update_time' => 'Update Time',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('activity_type',$this->activity_type,true);
		$criteria->compare('activity_text',$this->activity_text,true);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblUserActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
