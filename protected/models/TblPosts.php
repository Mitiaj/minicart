<?php

class TblPosts extends CActiveRecord
{
    public function getTotalPostsCount($iProjectId)
    {
        return  Yii::app()->db->createCommand("SELECT COUNT(*) FROM `tbl_posts` `t` WHERE `t`.`project_id` = '".$iProjectId."'")->queryScalar();
    }

	public function tableName()
	{
		return 'tbl_posts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, project_id, date, type, content', 'safe', 'on'=>'search'),
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
			'tblFiles' => array(self::HAS_MANY, 'TblFiles', 'post_id'),
			'project' => array(self::BELONGS_TO, 'TblProjects', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'project_id' => 'Project id',
			'date' => 'Date',
			'type' => 'Type',
			'content' => 'Content',
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
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * @param $iCurrentPage
     * @param $iPageSize
     * @param $iProjectId
     * @return array
     */
    public function getPosts($iCurrentPage, $iPageSize, $iProjectId)
    {
        $iTotalPosts = $this->getTotalPostsCount($iProjectId);
        $bContinue = false;
        $minus = $iTotalPosts-($iCurrentPage*$iPageSize);
        if($minus > 0){
            $bContinue = true;
        }else if( -1 * $minus < $iPageSize){
            $bContinue = true;
        }
        //$bContinue = $minus > 0 ? true : -$minus < $iPageSize ? true : false;
        if($bContinue || $iCurrentPage == 0){
        $sQuery = "SELECT `p`.`id` as `post_id`, `p`.`date`, `p`.`content`, `p`.`type`,`u`.`id` as `user_id`,`u`.`first_name`,`u`.`last_name`,`u`.`profile_picture`, group_concat(`f`.`file_path` SEPARATOR '|') `files`, `f`.`id` `file_id`
                    FROM `tbl_posts` `p`
                    INNER JOIN `tbl_users` `u`
                    ON `u`.`id` = `p`.`user_id`
                    LEFT JOIN `tbl_files` `f`
                    ON `f`.`post_id` = `p`.`id`
                    WHERE `p`.`project_id` ='".$iProjectId."'
                    GROUP BY `p`.`id`
                    ORDER BY `p`.`date` DESC";
        $dbAdapter = new CSqlDataProvider($sQuery);
        $dbAdapter->setTotalItemCount($iTotalPosts);
        $dbAdapter->setPagination(array(
            'pageSize' => $iPageSize,
            'currentPage' => $iCurrentPage
        ) );
        return $dbAdapter->getData();
        }else{
                return NULL;
             }
    }
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
