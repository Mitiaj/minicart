<?php

/**
 * This is the model class for table "tbl_phases".
 *
 * The followings are the available columns in table 'tbl_phases':
 * @property string $id
 * @property string $name_id
 * @property string $project_id
 * @property integer $display_order
 *
 * The followings are the available model relations:
 * @property TblProjects $project
 * @property TblPhasesNames $name
 * @property TblTasks[] $tblTasks
 */
class TblPhases extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_phases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name_id, project_id', 'required'),
			array('display_order', 'numerical', 'integerOnly'=>true),
			array('name_id, project_id', 'length', 'max'=>9),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name_id, project_id, display_order', 'safe', 'on'=>'search'),
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
			'project' => array(self::BELONGS_TO, 'TblProjects', 'project_id'),
			'name' => array(self::BELONGS_TO, 'TblPhasesNames', 'name_id'),
			'tblTasks' => array(self::HAS_MANY, 'TblTasks', 'phase_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'id of table',
			'name_id' => 'id of tbl_phases_names',
			'project_id' => 'id of tbl_projects',
			'display_order' => 'Display Order',
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
		$criteria->compare('name_id',$this->name_id,true);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('display_order',$this->display_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblPhases the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
