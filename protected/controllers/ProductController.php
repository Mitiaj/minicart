<?php
class ProductController extends Controller
{
    public function actionView()
    {
        if(!(isset($_GET['id']) && is_numeric($_GET['id']))){
            $this->redirect(Yii::app()->createUrl('site/index'));
        }else{
            $oProduct = Products::model()->findByPk($_GET['id']);
            $this->render('view',array(
                'oProduct' => $oProduct,
            ));
        }
    }
}