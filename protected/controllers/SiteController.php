<?php

class SiteController extends Controller
{
	
    public function actionHandleAjax()
    {
        if(Yii::app()->user->isGuest)
        {
            $this->redirect(Yii::app()->createUrl('user/login'));
        }
        else
        {
            if( (Yii::app()->request->isAjaxRequest) && (isset($_POST["sortBy"]) || isset($_POST["currentPage"])) )
            {
              if(isset($_POST['currentPage']) && is_numeric($_POST['currentPage']))
              {
                   $iCurrentPage=  Yii::app()->input->post('currentPage') + 1;
              } 
                      else
                   $iCurrentPage=0;  
                      
                $model = new Site();
                $model->setScenario("ajaxSort");   
                $iProjectCount = $model->iProjectsCount;
                $iPages = (($iProjectCount % 10) > 0) ? ($iProjectCount-($iProjectCount % 10))/10  : ($iProjectCount-($iProjectCount % 10))/10 - 1; 
               
              if($iCurrentPage > $iPages)
              {
                  echo " ";    
              }
              else
              {      
                 $arrProjectList = $model->getProjects($iCurrentPage,10,  Yii::app()->input->post('sortBy'),"DESC");
                 $this->renderPartial('_projectList',array('prItems'=>$arrProjectList,
                    'page'=>$iCurrentPage,
                    ));
              }
            }
            
            if( (Yii::app()->request->isAjaxRequest) && isset($_GET["query"]) )
            {
                $sParam = $_GET['query'];

                 $model = new Site(); 
                 print json_encode($model->searchProjectsInput($sParam));
            }
            if( (Yii::app()->request->isAjaxRequest) && isset($_POST["search"]) )
            {
                $sParam = Yii::app()->input->post('search');
                $model = new Site();
                $model->setScenario("search");
                $arrProjectList = $model->searchProjects($sParam);
                 $this->renderPartial('_projectList',array('prItems'=>$arrProjectList,
                    
                    ));
            }
        }
    }
    public function actionIndex()
	{
       
        if(Yii::app()->user->isGuest)
        {
            $this->redirect(Yii::app()->createUrl('user/login'));
        }
        else if(!Yii::app()->request->isAjaxRequest)
        {
                $this->setPageTitle("Project Overview | myFroggo");
                Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap/css/bootstrap.min.css?0001");
                Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/basic/style-min.css?0001");
                Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/dashboard.min.css?0001");
                Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/font-awesome/css/font-awesome.min.css?0001");
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl. "/js/jquery.min.js");
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl. "/css/bootstrap/js/bootstrap.min.js");
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl. "/js/dashboard.min.js");
                
                $iCurrentPage = 0;
                $model = new Site();
                $model->setScenario("index");
                $oProjects = $model->getProjects($iCurrentPage,10,"created","DESC");

                $this->render('_projectList',array('prItems'=>$oProjects,
                    'page'=>$iCurrentPage,
                    ));
         }  
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