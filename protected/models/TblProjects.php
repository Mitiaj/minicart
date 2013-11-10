<?php

class TblProjects extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_projects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_name', 'length', 'max'=>100),
			array('project_type', 'length', 'max'=>9),
			array('is_archived', 'length', 'max'=>1),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, project_name, project_type, date, is_archived', 'safe', 'on'=>'search'),
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
			'tblPhases' => array(self::HAS_MANY, 'TblPhases', 'project_id'),
			'projectType' => array(self::BELONGS_TO, 'TblProjectTypes', 'project_type'),
			'tblRelProjectUsers' => array(self::HAS_MANY, 'TblRelProjectUsers', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Project`s ID',
			'project_name' => 'Project name',
			'project_type' => 'Id of project types',
			'date' => 'Project creation date',
			'is_archived' => 'Project archived',
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
		$criteria->compare('project_name',$this->project_name,true);
		$criteria->compare('project_type',$this->project_type,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('is_archived',$this->is_archived,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblProjects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function getUsersByProjectId($iProjectId)
    {
        $sQuery = "SELECT `t`.`id`, `t`.`first_name`, `t`.`last_name`, `t`.`profile_picture`
                    FROM `tbl_users` `t`, `tbl_rel_project_users` `t2`
                    WHERE `t`.`id` = `t2`.`user_id`
                    AND `t2`.`project_id` =".$iProjectId;
        $dataProvider = new CSqlDataProvider($sQuery);
        return $dataProvider->getData();
    }
    public function getPhasesByProjectId($iProjectId)
    {
        $sQuery = "SELECT `t`.`id`,`t2`.`name` as `name`
                    FROM `tbl_phases` `t`
                    LEFT OUTER JOIN(`tbl_phases_names` `t2`)
                    ON(`t`.`name_id` = `t2`.`id`)
                    WHERE `t`.`project_id` = ".$iProjectId."
                    ORDER BY `t`.`display_order`";
        $dataAdapter = new CSqlDataProvider($sQuery);
        return $dataAdapter->getData();
    }
    public function getTasksByPhase($iPhaseId, $iProjectId)
    {
        $sQuery = "SELECT `t`.`id`, `t`.`completed`,`t`.`completed_date`,`t`.`user_id`,`t`.`deadline`,
                        `t2`.`name`,
                        `t3`.`first_name`,`t3`.`last_name`
                   FROM `tbl_phases` `t4`,`tbl_tasks` `t`
                   LEFT OUTER JOIN(`tbl_tasks_names` `t2`)
                    ON(`t2`.`id` = `t`.`name_id`)
                   LEFT OUTER JOIN(`tbl_users` `t3`)
                    ON(`t3`.`id` = `t`.`user_id`)
                   WHERE `t`.`phase_id` = `t4`.`id`
                    AND `t4`.`project_id` = ".$iProjectId."
                    AND `t`.`phase_id` = ".$iPhaseId."
                   ORDER BY `t`.`display_order`";
        $dataAdapter = new CSqlDataProvider($sQuery);
        return $dataAdapter->getData();
    }
    /**
     * 
     * @param string $sRow in format 'id=position'
     */
    public function updateTaskOrder($sRow)
    {
         $arrVar = explode("=",$sRow);
        if(is_numeric($arrVar[0]) && is_numeric($arrVar[1]))
        {
            $sQuery = "UPDATE `tbl_tasks` SET `display_order` = ".$arrVar[1]." WHERE `id` = ".$arrVar[0]."";
            Yii::app()->db->createCommand($sQuery)->execute();
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * 
     * @param string $sCategory in format 'tableId=order'
     * @return boolean
     */
    public function updatePhaseOrder($sCategory)
    {
        $arrVar = explode("=",$sCategory);
        if(is_numeric($arrVar[0]) && is_numeric($arrVar[1]))
        {
            $sQuery = "UPDATE `tbl_phases` SET `display_order` = ".$arrVar[1]." WHERE `id` = ".$arrVar[0]."";
            Yii::app()->db->createCommand($sQuery)->execute();
            return true;
        }
        else
        {
            return false;
        }
    }
    public function deletePhaseById($iPhaseId)
    {
        $sQuery = "DELETE FROM `tbl_phases` WHERE `id`=".$iPhaseId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
    public function deleteTaskById($iTaskId)
    {
        $sQuery = "DELETE FROM `tbl_tasks` WHERE `id`=".$iTaskId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
    public function updateTaskUser($iTaskId, $iUserId)
    {
        $sQuery = "UPDATE `tbl_tasks` SET `user_id` = ".$iUserId." WHERE `id` = ".$iTaskId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
     /**
     * 
     * @param int $iTaskId
     * @param int $iUserId
     * @return boolean
     */
    public function updateTaskName($iTaskId, $sTaskName)
    {
        $sQuery = "UPDATE `tbl_tasks_names`,`tbl_tasks` SET `tbl_tasks_names`.`name` = '".$sTaskName."' WHERE `tbl_tasks_names`.`id` = `tbl_tasks`.`name_id` AND `tbl_tasks`.`id` = ".$iTaskId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
     /**
     * 
     * @param int $iProjectId
     * @param string $sProjectName
     * @return boolean
     */
    public function updateProjectName($iProjectId, $sProjectName)
    {
        $sQuery = "UPDATE `tbl_projects` SET `project_name` = '".$sProjectName."' WHERE `id` = ".$iProjectId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
     public function updatePhaseName($iPhaseId, $sPhaseName)
    {
        $sQuery = "UPDATE `tbl_phases_names`,`tbl_phases` SET `tbl_phases_names`.`name` = '".$sPhaseName."' WHERE `tbl_phases_names`.`id` = `tbl_phases`.`name_id` AND `tbl_phases`.`id` = ".$iPhaseId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
    public function updateTaskStatus($iTaskId, $iTaskStatus)
    {
        if($iTaskStatus == 1)
        {
            $date = date("Y-m-d");
            $sQuery = "UPDATE `tbl_tasks` SET `completed` ='1' , `completed_date`='".$date."' WHERE `id`=".$iTaskId;
             Yii::app()->db->createCommand($sQuery)->execute();
        }
        else if($iTaskStatus == 0)
        {
            
              $sQuery = "UPDATE `tbl_tasks` SET `completed` ='0' , `completed_date`=NULL WHERE `id`=".$iTaskId;
             Yii::app()->db->createCommand($sQuery)->execute();
        }
    }
    
}
