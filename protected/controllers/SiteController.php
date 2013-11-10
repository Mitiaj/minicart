<?php

class SiteController extends Controller
{


    public function actionIndex()
	{
        $oProducts = Products::model()->findAll();
        Yii::app()->clientScript->registerCssFile(Yii::app()->getBaseUrl()."/css/main.css?001");
        $this->render('index',array(
            'oProducts' => $oProducts,
        ));
	}

    public function actionAbout()
    {
        $this->render('about');
    }
    public function filters() {
        parent::accessRules();
        return array('accessControl',
            );
    }
    public function accessRules() {
        parent::filters();
        
        return array(
        array('allow',
            'actions'=>array('index','handleajax'),
            'roles'=>array('Head Admin', 'Administrator','Account manager','Developer', 'Designer','Client'),
        ),
        array('deny',
            'actions'=>array('index'),
            'roles'=>array(''),
        ),
    );
    }
     public function actionError()
    {
        echo 'error occured';
    }
  
}