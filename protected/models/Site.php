<?php
class Site extends CFormModel
{
    public $sQuery;
    public $iProjectsCount;
    
    public function init() {
        parent::init();
        if(Yii::app()->user->checkAccess("Administrator"))
            $this->iProjectsCount = Yii::app()->db->createCommand("SELECT COUNT(*) FROM `tbl_projects` `t` WHERE `t`.`is_archived` = '0'")->queryScalar();
        else
            $this->iProjectsCount = Yii::app()->db->createCommand("SELECT COUNT(*) FROM `tbl_projects` `t`,`tbl_rel_project_users` `t3` WHERE `t`.`id` = `t3`.`project_id` AND `t3`.`user_id` = ".Yii::app()->user->id." AND `t`.`is_archived` = '0' AND `t`.`is_completed` = '0'")->queryScalar();

        
    }
    public function searchProjects($sParam)
    {
        if(Yii::app()->user->checkAccess("Administrator"))
            {
                $sQuery="Select `tbl_projects`.`id`,`tbl_projects`.`project_name`, `tbl_projects`.`date`, 
                            `tbl_project_types`.`name` as `type`
                        FROM `tbl_projects`
                        LEFT OUTER JOIN (`tbl_project_types`) 
                        ON (`tbl_project_types`.`id` = `tbl_projects`.`project_type` )
                        WHERE `tbl_projects`.`project_name` LIKE '%".$sParam."%'
                        ORDER BY `tbl_projects`.`date` DESC ";
                        
            }
            else
            {
                $sQuery="Select `tbl_projects`.`id`,`tbl_projects`.`project_name`, `tbl_projects`.`date`, 
                            `tbl_project_types`.`name` as `type`
                        FROM `tbl_rel_project_users`,`tbl_projects`
                        LEFT OUTER JOIN (`tbl_project_types`) 
                        ON (`tbl_project_types`.`id` = `tbl_projects`.`project_type` )
                        WHERE `tbl_projects`.`is_archived` = '0'
                        AND `tbl_projects`.`is_completed` = '0'
                        AND `tbl_rel_project_users`.`user_id` = ".Yii::app()->user->id."
                        AND `tbl_projects`.`id` = `tbl_rel_project_users`.`project_id`
                        AND  `tbl_projects`.`project_name` LIKE '%".$sParam."%'
                        ORDER BY `tbl_projects`.`date` DESC "; 
            }
                $dbAdapter = new CSqlDataProvider($sQuery);
                $dbAdapter->setTotalItemCount($this->iProjectsCount);
                $dbAdapter->setPagination(array(
                    'pageSize' => 10,
                    'currentPage' => 0
                ) );
                
                return $dbAdapter->getData();
    }

    public function searchProjectsInput($sParam)
    {
        if(Yii::app()->user->checkAccess("Administrator"))
            {
                $this->sQuery =   "SELECT `t`.`project_name` as 'option', `t`.`id`
                                 FROM `tbl_projects` `t`
                                 WHERE  `t`.`project_name` LIKE '%".$sParam."%'
                                 ORDER BY `t`.`date` DESC";
            }
            else
            {
                $this->sQuery = "SELECT `t`.`project_name` as 'option', `t`.`id` 
                             FROM `tbl_projects` `t`,`tbl_rel_project_users` `t2`
                             WHERE `t`.`id` = `t2`.`project_id` 
                                AND `t2`.`user_id` = ".Yii::app()->user->id."
                                AND `t`.`is_archived` = '0'
                                AND `t`.`project_name` LIKE '%".$sParam."%'
                            ORDER BY `t`.`date` DESC";
            }
            $dataProvider = new CSqlDataProvider($this->sQuery);
            return $dataProvider->getData();
    }
    /**
     * 
     * @param int $iCurrentPage
     * @param int $iPageSize
     * @param string $sSortBy
     * @param string $sSortDirection
     * @return array[]
     */
    public function getProjects($iCurrentPage,$iPageSize,$sSortBy, $sSortDirection)
    {
         if(Yii::app()->user->checkAccess("Administrator"))
                {           
                $sQuery="Select `tbl_projects`.`id`,`tbl_projects`.`project_name`, `tbl_projects`.`date`, 
                            `tbl_project_types`.`name` as `type`
                        FROM `tbl_projects`
                        LEFT OUTER JOIN (`tbl_project_types`) 
                        ON (`tbl_project_types`.`id` = `tbl_projects`.`project_type` )
                        WHERE `tbl_projects`.`is_archived` = '0'
                        AND `tbl_projects`.`is_completed` = '0'";     
                switch($sSortBy)
                    {
                    case "name":
                            $sQuery .= " ORDER BY project_name ".$sSortDirection;
                        break;
                    case "created":
                            $sQuery .= " ORDER BY date ".$sSortDirection;
                        break;
                    case "type":
                            $sQuery .= " ORDER BY project_type ".$sSortDirection;
                        break;
                    case "priority":
                        /**
                         * @TODO perrasyti pagal priority
                         * 
                         */
                        $sQuery .= " ORDER BY project_name ".$sSortDirection;
                        break;
                    }          
                }
                else
                {                     
                   $sQuery="Select `tbl_projects`.`id`,`tbl_projects`.`project_name`, `tbl_projects`.`date`, 
                            `tbl_project_types`.`name` as `type`
                        FROM `tbl_rel_project_users`,`tbl_projects`
                        LEFT OUTER JOIN (`tbl_project_types`) 
                        ON (`tbl_project_types`.`id` = `tbl_projects`.`project_type` )
                        WHERE `tbl_projects`.`is_archived` = '0'
                        AND `tbl_projects`.`is_completed` = '0'
                        AND `tbl_rel_project_users`.`user_id` = ".Yii::app()->user->id."
                        AND `tbl_projects`.`id` = `tbl_rel_project_users`.`project_id`"; 
                   
                    switch($sSortBy)
                        {
                        case "name":
                                $sQuery .= " ORDER BY project_name ".$sSortDirection;
                            break;
                        case "created":
                                $sQuery .= " ORDER BY date ".$sSortDirection;
                            break;
                        case "type":
                                $sQuery .= " ORDER BY project_type ".$sSortDirection;
                            break;
                        case "priority":
                            /**
                             * @TODO perrasyti pagal priority
                             * 
                             */
                            $sQuery .= " ORDER BY project_name ".$sSortDirection;
                            break;
                        }
                }
                $sQuery .= " LIMIT ".$iCurrentPage*$iPageSize.", ".$iPageSize;
               /*$dbAdapter = new CSqlDataProvider($sQuery);
                $dbAdapter->setTotalItemCount($this->iProjectsCount);
                $dbAdapter->setPagination(array(
                    'pageSize' => $iPageSize,
                    'currentPage' => $iCurrentPage
                ) );*/
        $dependency = new CDbCacheDependency('SELECT count(*) FROM tbl_projects');
        return Yii::app()->db->cache(1000, $dependency)->createCommand($sQuery)->queryAll();
                //return $dbAdapter->getData();
    }


}