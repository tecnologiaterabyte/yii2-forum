<?php

namespace terabyte\forum\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use terabyte\forum\models\SiteModels;
use terabyte\forum\models\PostForm;
use terabyte\forum\models\TopicForm;
use terabyte\forum\models\TopicModels;
use terabyte\forum\models\UserMention;


/**
 * Class DefaultController
 */

class TopicController extends \yii\web\Controller
{
    /*
     * @param $id
     * @return string
     */

    public function actionCreate($id)
    {
        /* @var SiteModels $forum */

        $forum = SiteModels::find()
            ->where(['id' => $id])
            ->one();

        if (!$forum || Yii::$app->getUser()->getIsGuest()) {

            throw new NotFoundHttpException();
        }

        $model = new TopicForm();

        if ($model->load(Yii::$app->getRequest()->post()) && $model->create($forum)) {
            $this->redirect(['post/view', 'id' => $model->topic->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'forum' => $forum,
        ]);
    }

    /**
     * @param $id
     * @return string
     */

    public function actionView($id)
    {
        /* @var SiteModels $forum */

        $forum = SiteModels::findOne(['id' => $id]);

        $query = TopicModels::find()
            ->where(['forum_id' => $id])
            ->orderBy(['sticked' => SORT_DESC])
            ->addOrderBy(['last_post_created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeLimit' => false,
                'defaultPageSize' => Yii::$app->config->get('display_topics_count'),
            ],
        ]);

        $topics = $dataProvider->getModels();

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'forum' => $forum,
            'topics' => $topics,
        ]);
    }
}