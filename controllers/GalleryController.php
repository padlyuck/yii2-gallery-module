<?php

namespace sadovojav\gallery\controllers;

use sadovojav\gallery\models\Gallery;
use sadovojav\gallery\models\GalleryFile;
use sadovojav\gallery\models\GallerySearch;
use sadovojav\gallery\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class GalleryController
 * @package sadovojav\gallery\controllers
 */
class GalleryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'create', 'view', 'delete', 'update', 'upload', 'remove', 'caption'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'  => ['post'],
                    'upload'  => ['post'],
                    'remove'  => ['post'],
                    'caption' => ['post'],
                ],
            ],
        ];
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $files = UploadedFile::getInstancesByName('files');

        $dir = Yii::getAlias(Module::getInstance()->uploadPath . DIRECTORY_SEPARATOR . $_POST['galleryId']);

        FileHelper::createDirectory($dir);

        $response = [];

        foreach ($files as $key => $file) {
            if (Module::getInstance()->uniqueName) {
                $name = $this->getUniqueName($file);
            } else {
                $name = $file->name;
            }

            $file->saveAs($dir . DIRECTORY_SEPARATOR . $name);

            $model = new GalleryFile();
            $model->galleryId = $_POST['galleryId'];
            $model->file = $name;

            if ($model->save()) {
                $response = [
                    'status'  => true,
                    'message' => 'Success',
                    'html'    => $this->renderAjax('_image', [
                        'model' => $model,
                    ]),
                ];
            }

            break;
        }

        return $response;
    }

    /**
     * Deletes an existing gallery fle model.
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \Exception
     * @internal param int $id
     */
    public function actionRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = false;

        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        /** @var GalleryFile $model */
        $model = GalleryFile::findOne(Yii::$app->request->post('id'));

        if (file_exists($model->path)) {
            unlink($model->path);
        }

        if ($model->delete()) {
            $response = [
                'status'  => true,
                'message' => 'Success',
            ];
        }

        return $response;
    }

    public function actionCaption()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $response = false;

        $model = GalleryFile::findOne(Yii::$app->request->post('id'));
        $model->caption = Yii::$app->request->post('caption');
        $model->url = Yii::$app->request->post('url');

        if ($model->save()) {
            $response = [
                'status'  => true,
                'message' => 'Success',
            ];
        }

        return $response;
    }

    /**
     * Get unique name
     *
     * @param $file
     *
     * @return string
     */
    private function getUniqueName($file)
    {
        $explodeName = explode('.', $file->name);

        $ext = end($explodeName);

        return Yii::$app->security->generateRandomString(16) . ".{$ext}";
    }

    /**
     * Lists all gallery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GallerySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single gallery model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new gallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gallery();

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing gallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing gallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}