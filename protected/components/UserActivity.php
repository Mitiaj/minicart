<?php
class UserActivity
{
    /**
     * @var array
     */
    private static $aActivities = array(
        0 => 'Viewed a project ',
        1 => 'Uploaded files on ',
        2 => 'Downloaded files from ',
        3 => 'Posted a comment on ',
        4 => 'Got a task assigned on ',
        5 => 'Deleted a file from ',
        6 => 'Created a project ',
        7 => 'Completed a task on ',
        8 => 'Read the project discussion board on '
    );
    /**
     * @var array
     */
    private static $aActivityClasses= array(
        0 => 'icon-eye-open',
        1 => 'icon-upload',
        2 => 'icon-download-alt',
        3 => 'icon-comment',
        4 => 'icon-tag',
        5 => 'icon-trash',
        6 => 'icon-folder-open',
        7 => 'icon-ok',
        8 => 'icon-eye-open'
    );

    /**
     * @param $iUserId
     * @param $iProjectId
     * @param $type
     */
    public static function addActivity($iUserId,$iProjectId, $type)
    {
        $sRecord = TblUsers::model()->findByPk($iUserId)->first_name.' '.TblUsers::model()->findByPk($iUserId)->last_name.' '.self::$aActivities[$type].'<a href="'.Yii::app()->createUrl('project/dashboard/'.$iProjectId).'">'.TblProjects::model()->findByPk($iProjectId)->project_name.'</a>';
        $oActivity = new TblUserActivity();
        $oActivity->user_id = $iUserId;
        $oActivity->project_id = $iProjectId;
        $oActivity->activity_type = $type;
        $oActivity->activity_text = $sRecord;
        $oActivity->update_time = date('Y-m-d H:i:s');
        $oActivity->save();
    }

    /**
     * @param $iUserId
     * @return CActiveRecord[]
     */
    public static function getAllActivities($iUserId,$iLimit = 20)
    {
        return TblUserActivity::model()->findAll(array('condition'=>'user_id=:uid','order'=>'update_time DESC','limit'=> $iLimit,'params'=>array(':uid' => $iUserId)));
    }

    /**
     * @param $iActivityId
     * @return string
     */
    public static function getActivityClass($iActivityId)
    {
        return self::$aActivityClasses[$iActivityId];
    }
}