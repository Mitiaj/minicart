<?php

/**
 * This is the model class for table "tbl_files".
 *
 * The followings are the available columns in table 'tbl_files':
 * @property string $id
 * @property string $post_id
 * @property string $file_path
 * @property string $Date
 *
 * The followings are the available model relations:
 * @property TblPosts $post
 */
class TblFiles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array( 'file_path', 'required'),
			array('file_path', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, post_id, file_path, Date', 'safe', 'on'=>'search'),
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
			'post' => array(self::BELONGS_TO, 'TblPosts', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'post_id' => 'Post id',
			'file_path' => 'Path to file',
			'Date' => 'Upladed date',
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
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('Date',$this->Date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public function getAllFiles($iProjectId){
        $sQuery = "SELECT `f`.`id`,`f`.`file_path`, `f`.`date`
                    FROM `tbl_files` `f`, `tbl_posts`, `tbl_projects`
                    WHERE`f`.`post_id` = `tbl_posts`.`id` AND `tbl_posts`.`project_id` = `tbl_projects`.`id` AND `tbl_projects`.`id` = '".$iProjectId."'
                    ORDER BY `f`.`date` DESC";
        $dataAdapter = new CSqlDataProvider($sQuery);
        return $dataAdapter->getData();
    }
}