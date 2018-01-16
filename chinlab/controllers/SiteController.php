<?php
namespace app\controllers;
use app\models\ExcelImage;
use Yii;
use app\models\LoginForm;
use app\models\SignupForm;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use app\common\data\Response as UResponse;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends  Controller
{
    public $enableCsrfValidation = false;
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
				'access' => [
					 'class' => AccessControl::className(),
					 'rules' => [
					 		[
					 				'actions' => ['login', 'error','signup'],
					 				'allow' => true,
					 		],
					 		[
					 				'actions' => ['logout', 'index', 'upload'],
					 				'allow' => true,
					 				'roles' => ['@'],
					 		],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'logout' => ['get'],
						],
				],
		];
	}	
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
				'error' => [
						'class' => 'yii\web\ErrorAction',
				],
		];
	}
	
    public function actionIndex()
    {   
    	return $this->render('/chunfeng/home');
    }

   
    /**
     * 测试上传文件
     */
    public function actionUpload() {

        $file = UploadedFile::getInstanceByName('upload');
        if ($file) {
            move_uploaded_file($file->tempName, '/tmp/'.$file->name);
            $pathInfo = pathinfo('/tmp/'.$file->name);
            $model = new ExcelImage();
            $result = $model->uploadImages('/tmp/'.$file->name, $pathInfo);
            unlink('/tmp/'.$file->name);
            if ($result) {
                return $result['url'];
            }
        }
        return "error|图片上传失败，请稍后重试";
    }
    
  
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    

	public function actionLogin()
	{
		$model = new LoginForm();
		if($userInfo = Yii::$app->user->identity){
			$usrInfo = $userInfo->toArray();
			if($userInfo['username']!='admin' && $userInfo['sys_type'] !='1'){
				Yii::$app->user->logout();
				return $this->renderPartial('login', [
				  'model' => $model, 'message'=>'当前用户不属于该系统',
			    ]);
			}
		}
		if (!Yii::$app->user->isGuest) {
			return $this->redirect('/chunfeng_home.php');
		}
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect('/chunfeng_home.php');
		} else {
			$messgae = current($model->getFirstErrors());
			return $this->renderPartial('login', [
				'model' => $model, 'message'=> $messgae,
			]);
		}
	}

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
}
