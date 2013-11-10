<?php

class Project extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'project_ln';
	}

	public function rules()
	{
		return array(
			array('Project_Id, Project_Name, Project_Type, Project_Description, Project_Ticket_Id, Date_Added, Status, Has_Web_Account, Is_Archived', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Project_Id' => 'Project',
			'Project_Name' => 'Project Name',
			'Project_Type' => 'Project Type',
			'Project_Description' => 'Project Description',
			'Project_Ticket_Id' => 'Project Ticket',
			'Date_Added' => 'Date Added',
			'Status' => 'Status',
			'Has_Web_Account' => 'Has Web Account',
			'Is_Archived' => 'Is Archived',
		);
	}

	
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Project_Id',$this->Project_Id);
		$criteria->compare('Project_Name',$this->Project_Name,true);
		$criteria->compare('Project_Type',$this->Project_Type);
		$criteria->compare('Project_Description',$this->Project_Description,true);
		$criteria->compare('Project_Ticket_Id',$this->Project_Ticket_Id,true);
		$criteria->compare('Date_Added',$this->Date_Added,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('Has_Web_Account',$this->Has_Web_Account);
		$criteria->compare('Is_Archived',$this->Is_Archived);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Method returns 20 projects
     * @return type array
     */
    
    public function getUsersByProjectId($iProjectId)
    {
        $sQuery = "SELECT `user_ln`.`User_Id`, `user_ln`.`First_name`, `user_ln`.`Last_Name`, `user_ln`.`Profile_Picture`
                FROM `project_user`, `user_ln`
                WHERE `project_user`.`Project_Id` =".$iProjectId."
                AND `project_user`.`User_Id` = `user_ln`.`User_Id` ";
        $dataProvider = new CSqlDataProvider($sQuery);
        return $dataProvider->getData();
    }

    public function getCategoriesByProjectId($iProjectId)
    {
        $sQuery = "SELECT  `project_categories_ln`.`Category_Id`, `project_categories_ln`.`Category_Name`,`project_category_order`.*
            FROM    `project_categories_ln`, `project_category_order`
            WHERE  `project_category_order`.`Project_Id` =".$iProjectId."
            AND `project_category_order`.`Category_Id` = `project_categories_ln`.`Category_Id`
            ORDER BY `project_category_order`.`Display_Order` ASC";
        $dataProvider = new CSqlDataProvider($sQuery);
        return $dataProvider->getData();
    }
    public function getTasksByCategory($iCategoryId,$iProjectId)
    {
        $sQuery = "SELECT  `services_ln`.`Service_Id`,`services_ln`.`Service_Name` ,`services_ln`.`Date_Added`,  `project_service`.`Due_Date`,
            `project_service`.`No_Of_Days`,`services_ln`.`Status`,`project_service`.`Is_Completed`,
                    `project_categories_ln`.`Project_Id`,`project_service`.`Display_Order`,`services_ln`.`User_Id`
                    FROM  `project_service` ,  `project_categories_ln` ,  `project_category_service` ,  `services_ln` 
                    WHERE  `project_service`.`Service_Id` =  `project_category_service`.`Service_Id` 
                    AND  `project_category_service`.`Category_Id` =  `project_categories_ln`.`Category_Id` 
                    AND  `services_ln`.`Service_Id` =  `project_service`.`Service_Id` 
                    AND  `project_categories_ln`.`Category_Id` =".$iCategoryId."
                    AND `project_service`.`Project_Id` = ".$iProjectId."
                    ORDER BY `project_service`.`Display_Order` ASC";
        $dataProvider = new CSqlDataProvider($sQuery);
            return $dataProvider->getData();
        
    }
    public function getTasksWithCategories($iProjectId)
    {
        $sQuery = "SELECT  `services_ln`.`Service_Name` ,  `project_service`.`Due_Date` ,  `project_service`.`Display_Order` ,  `project_service`.`Category_Id` , `project_categories_ln`.`Category_Id` AS  'Cat',  `project_categories_ln`.`Category_Name`,
            `services_ln`.`Status`,`project_service`.`Is_Completed`,`services_ln`.`Is_For_Project`
            FROM  `project_service` ,  `project_categories_ln` ,  `project_category_service` ,  `services_ln` 
            WHERE  `project_service`.`Service_Id` =  `project_category_service`.`Service_Id` 
            AND  `project_category_service`.`Category_Id` =  `project_categories_ln`.`Category_Id` 
            AND  `services_ln`.`Service_Id` =  `project_service`.`Service_Id` 
            AND  `project_service`.`Project_Id` =".$iProjectId;

        $dataProvider = new CSqlDataProvider($sQuery);
            return $dataProvider->getData();
        

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
            $sQuery = "UPDATE `project_service` SET `Display_Order` = ".$arrVar[1]." WHERE `Service_Id` = ".$arrVar[0]."";
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
    public function updateCategoryOrder($sCategory)
    {
        $arrVar = explode("=",$sCategory);
        if(is_numeric($arrVar[0]) && is_numeric($arrVar[1]))
        {
            $sQuery = "UPDATE `project_category_order` SET `Display_Order` = ".$arrVar[1]." WHERE `Category_Id` = ".$arrVar[0]."";
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
     * @param int $iCatId
     * @return boolean
     */
    public function deleteCategoryById($iCatId)
    {
        $sQuery = "DELETE FROM `project_categories_ln` WHERE `Category_Id`=".$iCatId;
        Yii::app()->db->createCommand($sQuery)->execute();
        $sQuery = "DELETE FROM `project_category_order` WHERE `Category_Id`=".$iCatId;
        Yii::app()->db->createCommand($sQuery)->execute();
        $sQuery = "DELETE FROM `project_category_service` WHERE `Category_Id`=".$iCatId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
    /**
     * 
     * @param int $iTaskId
     * @return boolean
     */
    public function deleteTaskById($iTaskId)
    {
        $sQuery = "DELETE FROM `services_ln` WHERE `Service_Id`=".$iTaskId;
        Yii::app()->db->createCommand($sQuery)->execute();
        $sQuery = "DELETE FROM `project_service` WHERE `Service_Id`=".$iTaskId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
    /**
     * 
     * @param int $iTaskId id of task
     * @return array with user first name, last name
     */
    public function selectUserOfTask($iTaskId)
    {
        $sQuery = "SELECT `user_ln`.`First_Name`, `user_ln`.`Last_Name`
                    FROM `user_ln`, `services_ln`
                    WHERE `user_ln`.`User_Id` = `services_ln`.`User_Id`
                        AND `services_ln`.`Service_Id` = ".$iTaskId;
        $dataProvider = new CSqlDataProvider($sQuery);
        $dataProvider->pagination->setItemCount(1);
            return $dataProvider->getData();
    }
    /**
     * 
     * @param int $iTaskId
     * @param int $iUserId
     * @return boolean
     */
    public function updateTaskUser($iTaskId, $iUserId)
    {
        $sQuery = "UPDATE `services_ln` SET `User_Id` = ".$iUserId." WHERE `Service_Id` = ".$iTaskId;
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
        $sQuery = "UPDATE `services_ln` SET `Service_Name` = '".$sTaskName."' WHERE `Service_Id` = ".$iTaskId;
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
        $sQuery = "UPDATE `project_ln` SET `Project_Name` = '".$sProjectName."' WHERE `Project_Id` = ".$iProjectId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
     public function updateCatName($iCatId, $sCatName)
    {
        $sQuery = "UPDATE `project_categories_ln` SET `Category_Name` = '".$sCatName."' WHERE `Category_Id` = ".$iCatId;
        Yii::app()->db->createCommand($sQuery)->execute();
        return true;
    }
    public function isTaskUser($iUserId,$iTaskId)
    {
        $sQuery = "SELECT COUNT(*) FROM `services_ln` WHERE `User_Id`='".$iUserId."' AND `Service_Id`='".$iTaskId."'";
        $dataProvider = new CSqlDataProvider($sQuery);
        $arrResult = $dataProvider->getData();
            if($arrResult[0]['COUNT(*)'] > 0)
                return true;
            else
                return false;
            
    }
    
    public function updateTaskStatus($iProjectId, $iTaskId, $iTaskStatus)
    {
        if($iTaskStatus == 1)
        {
            $date = date("Y-m-d");
            $sQuery = "UPDATE `project_service` SET Is_Completed =1 , `Completed_Date`='".$date."' WHERE `Service_Id`='".$iTaskId."' AND `Project_Id`='".$iProjectId."'";
             Yii::app()->db->createCommand($sQuery)->execute();
        }
        else if($iTaskStatus == 0)
        {
              $sQuery = "UPDATE `project_service` SET Is_Completed =0 WHERE `Service_Id`='".$iTaskId."' AND `Project_Id`='".$iProjectId."'";
             Yii::app()->db->createCommand($sQuery)->execute();
        }
    }
    public function relations()
    {
        return array(
			'Project' => array(self::HAS_MANY, 'ProjectUser', 'Project_Id'),
            'ProjectUser' => array(self::HAS_MANY,'ProjectUser', 'User_Id')
		);
    }
    
}
