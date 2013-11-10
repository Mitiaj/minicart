<?php

class SiteController extends Controller
{


    public function actionIndex()
	{
        $oProducts = Products::model()->findAll();
        Yii::app()->clientScript->registerCssFile(Yii::app()->getBaseUrl()."/css/main.css?003");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl()."/js/jquery.min.js?001");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl()."/js/main.min.js?001");

        $this->render('index',array(
            'oProducts' => $oProducts,
        ));


	}
    public function actionAjaxGetProducts(){
        if(Yii::app()->request->isAjaxRequest && isset($_GET['searchQuery'])){
            $aProducts = Products::model()->xGetProduxts($_GET['searchQuery']);
            //echo json_encode($aProducts);
            $this->renderPartial('_xProducts',array(
                'oProducts' => (object)$aProducts
            ));
        }
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