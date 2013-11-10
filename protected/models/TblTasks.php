<?php

/**
 * This is the model class for table "tbl_tasks".
 *
 * The followings are the available columns in table 'tbl_tasks':
 * @property string $id
 * @property string $phase_id
 * @property string $name_id
 * @property integer $display_order
 * @property string $completed
 * @property string $completed_date
 * @property string $user_id
 *
 * The followings are the available model relations:
 * @property TblUsers $user
 * @property TblPhases $phase
 * @property TblTasksNames $name
 */
class TblTasks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_tasks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phase_id, name_id', 'required'),
			array('display_order', 'numerical', 'integerOnly'=>true),
			array('phase_id, name_id, user_id', 'length', 'max'=>9),
			array('completed', 'length', 'max'=>1),
			array('completed_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, phase_id, name_id, display_order, completed, completed_date, user_id', 'safe', 'on'=>'search'),
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
			'phase' => array(self::BELONGS_TO, 'TblPhases', 'phase_id'),
			'name' => array(self::BELONGS_TO, 'TblTasksNames', 'name_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'id of table',
			'phase_id' => 'id of tbl_phases',
			'name_id' => 'id of tlb_tasks_names',
			'display_order' => 'Order displaying tasks',
			'completed' => 'is project completed flag',
			'completed_date' => 'Date when task comple',
			'user_id' => 'user for task',
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
		$criteria->compare('phase_id',$this->phase_id,true);
		$criteria->compare('name_id',$this->name_id,true);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('completed',$this->completed,true);
		$criteria->compare('completed_date',$this->completed_date,true);
		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblTasks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function isTaskUser($iTaskId)
    {
        $sQuery = "SELECT COUNT(*) FROM `tbl_tasks` WHERE `user_id` = ".Yii::app()->user->id." AND `id`=".$iTaskId;
        $dataProvider = new CSqlDataProvider($sQuery);
       $arrResult = $dataProvider->getData();
       if($arrResult[0]['COUNT(*)'] > 0)
           return true;
       else
           return false; 
    }
    public function updateDeadline($iTaskId,$dDeadline)
    {
        $sQuery = "UPDATE `tbl_tasks` SET `deadline` ='".$dDeadline."' WHERE `id`='".$iTaskId."'";
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
}
