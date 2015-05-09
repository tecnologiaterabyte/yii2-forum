<?php

namespace terabyte\forum\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use terabyte\forum\models\Forum;
use terabyte\forum\models\Post;
use terabyte\forum\models\PostForm;
use terabyte\forum\models\TopicForm;
use terabyte\forum\models\Topic;
use terabyte\forum\models\UserMention;


/**
 * Class DefaultController
 */
class TopicController extends \yii\web\Controller
{
    /**
     * @param $id topic identificator.
     * @return string
     */
    public function actionView($id)
    {
        /** @var Topic $topic */
        $topic = Topic::find()
            ->where(['id' => $id])
            ->with('forum')
            ->one();

        if (!$topic) {
            throw new NotFoundHttpException();
        }

        $topic->updateCounters(['number_views' => 1]);
        $topic->save();

        $dataProvider = Post::getDataProviderByTopic($topic->id);
        $posts = $dataProvider->getModels();

        if (!Yii::$app->getUser()->getIsGuest()) {
            $userMentions = UserMention::findAll([
                'topic_id' => $id,
                'mention_user_id' => Yii::$app->getUser()->getId(),
                'status' => UserMention::MENTION_SATUS_UNVIEWED,
            ]);

            // user mention update
            foreach ($userMentions as $userMention) {
                $userMention->status = UserMention::MENTION_SATUS_VIEWED;
                $userMention->save();
            }

            $model = new PostForm();
            if ($model->load(Yii::$app->getRequest()->post()) && $model->create($topic)) {
                $this->redirect(['/forum/topic/view', 'id' => $model->getPost()->id, '#' => 'p' . $model->getPost()->id]);
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
     * @param $id
     * @return string
     */
    public function actionCreate($id)
    {
        /** @var Forum $forum */
        $forum = Forum::find()
            ->where(['id' => $id])
            ->one();

        if (!$forum || Yii::$app->getUser()->getIsGuest()) {
            throw new NotFoundHttpException();
        }

        $model = new TopicForm();

        if ($model->load(Yii::$app->getRequest()->post()) && $model->create($forum)) {
            $this->redirect(['/forum/topic/view', 'id' => $model->topic->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'forum' => $forum,
        ]);
    }
}