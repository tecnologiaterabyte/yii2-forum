<?php

namespace terabyte\forum\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use terabyte\forum\models\PostModels;
use terabyte\forum\models\PostForm;
use terabyte\forum\models\TopicModels;
use terabyte\forum\models\UserMention;


/**
 * Class PostController
 */

class PostController extends \yii\web\Controller
{
    /*
     * @param $id topic identificator.
     * @return string
     */

    public function actionView($id)
    {
        /* @var TopicModels $topic */

        $topic = TopicModels::find()
            ->where(['id' => $id])
            ->with('forum')
            ->one();

        if (!$topic) {
            throw new NotFoundHttpException();
        }

        $topic->updateCounters(['number_views' => 1]);
        $topic->save();
        $dataProvider = PostModels::getDataProviderByTopic($topic->id);
        $posts = $dataProvider->getModels();

        if (!Yii::$app->getUser()->getIsGuest()) {
            $userMentions = UserMention::findAll([
                'topic_id' => $id,
                'mention_user_id' => Yii::$app->getUser()->getId(),
                'status' => UserMention::MENTION_STATUS_UNVIEWED,
            ]);

            /* user mention update */
            foreach ($userMentions as $userMention) {
                $userMention->status = UserMention::MENTION_STATUS_VIEWED;
                $userMention->save();
            }

            $model = new PostForm();

            if ($model->load(Yii::$app->getRequest()->post()) && $model->create($topic)) {
                $page = $model->post->getPostPage($model->post);
                if ($page > 1) {
                    $this->redirect([
                        'view',
                        'id' => $model->getPost()->topic->id,
                        'page' => $page,
                        '#' => 'p' . $model->getPost()->id
                    ]);
                } else {
                        $this->redirect(['view', 'id' => $model->getPost()->topic->id, '#' => 'p' . $model->getPost()->id]);
                }
            }

            return $this->render('view', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'topic' => $topic,
                'posts' => $posts,
            ]);

        } else {

            return $this->render('view', [
                'dataProvider' => $dataProvider,
                'topic' => $topic,
                'posts' => $posts,
            ]);
        }
    }

    /**
     * @return string
     */

    public function actionUpdate()
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $text = Yii::$app->getRequest()->post('text');
            $id = substr(Yii::$app->getRequest()->post('id'), 1);

            /* @var PostModels $post */

            $post = PostModels::findOne(['id' => $id]);

            if (!$post || Yii::$app->getUser()->can('updatePost', ['post' => $post])) {
                throw new NotFoundHttpException();
            }

            $model = new PostForm();
            $model->message = $text;

            if ($model->validate()) {
                $post->message = $text;
                $post->edited_at = time();
                $post->edited_by = Yii::$app->getUser()->getIdentity()->getId();
                $post->save();
            }

            return $post->displayMessage;
        }

        throw new NotFoundHttpException();
    }
}