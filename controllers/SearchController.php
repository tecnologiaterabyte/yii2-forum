<?php

namespace terabyte\forum\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use terabyte\forum\models\PostModels;
use terabyte\forum\models\TopicModels;

class SearchController extends \yii\web\Controller
{
    public function actionViewActiveTopics()
    {
        // !!! need access check

        $query = TopicModels::find()
            ->where('forum_id NOT LIKE 0')
            ->with('forum')
            ->orderBy(['last_post_created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeLimit' => false,
                'defaultPageSize' => Yii::$app->config->get('display_topics_count'),
            ],
        ]);

        $topics = $dataProvider->getModels();

        return $this->render('topic_list', [
            'title' => 'Активные темы',
            'dataProvider' => $dataProvider,
            'topics' => $topics,
        ]);
    }

    public function actionViewUnansweredTopics()
    {
        // !!! need access check

        $query = TopicModels::find()
            ->where('number_posts = 0 AND forum_id NOT LIKE 0')
            ->with('forum')
            ->orderBy(['last_post_created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeLimit' => false,
                'defaultPageSize' => Yii::$app->config->get('display_topics_count'),
            ],
        ]);

        $topics = $dataProvider->getModels();

        return $this->render('topic_list', [
            'title' => 'Темы без ответов',
            'dataProvider' => $dataProvider,
            'topics' => $topics,
        ]);
    }

    public function actionViewOwnpostTopics()
    {
        // !!! need access check

        if (Yii::$app->getUser()->getIsGuest()) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app->getUser()->getIdentity();

        $posts = PostModels::find()
            ->select(['topic_id', 'user_id'])
            ->where('user_id = :user_id', [':user_id' => $user->id])
            ->asArray()
            ->all();

        $ids = ArrayHelper::getColumn($posts, 'topic_id');
        $uniqueIDs = array_unique($ids);

        $query = TopicModels::find()
            ->where(['IN', 'id', $uniqueIDs])
            ->andWhere('forum_id NOT LIKE 0')
            ->with('forum')
            ->orderBy(['last_post_created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeLimit' => false,
                'defaultPageSize' => Yii::$app->config->get('display_topics_count'),
            ],
        ]);

        $topics = $dataProvider->getModels();

        return $this->render('topic_list', [
            'title' => 'Темы с вашим участием',
            'dataProvider' => $dataProvider,
            'topics' => $topics,
        ]);
    }
}