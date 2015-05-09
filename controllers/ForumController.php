<?php

namespace terabyte\forum\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use terabyte\forum\models\Category;
use terabyte\forum\models\FeedbackForm;
use terabyte\forum\models\Forum;
use terabyte\forum\models\Post;
use terabyte\forum\models\Topic;

/**
 * Class ForumController
 */
class ForumController extends \yii\web\Controller
{

    /**
     * This action render the index page.
     * @return string
     */
    public function actionIndex()
    {
        $categories = Category::find()
            ->with(['forums'])
            ->orderBy('display_position')
            ->all();

        return $this->render('index', ['categories' => $categories]);
    }

    /**
     * This action render the feedback page.
     * @return string
     */
    public function actionFeedback()
    {
        $model = new FeedbackForm();
        if ($model->load(Yii::$app->request->post()) && $model->feedback()) {
            return $this->goBack();
        } else {
            return $this->render('feedback', ['model' => $model]);
        }
    }

    /**
     * This action render the rule page.
     * @return string
     */
    public function actionTerms()
    {
        return $this->render('terms');
    }

    /**
     * This action render the search page.
     * @return string
     */
    public function actionSearch()
    {
        return $this->render('search');
    }

    /**
     * This action render the markdown helper page.
     * @return string
     */
    public function actionMarkdown()
    {
        $post = Post::findOne(['id' => 450994]);

        return $this->render('markdown', ['post' => $post]);
    }

    /**
     * @return string
     */
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }

        $name = 'Ошибка';
        if ($code == 404) {
            $message = 'Страницы с таким адресом не существует.';
        } else {
            $message = 'Неверный запрос. Ссылка ошибочная или устарела.';
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('info', ['params' => [
                'name' => $name,
                'message' => $message,
            ]]);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        /** @var Forum $forum */
        $forum = Forum::findOne(['id' => $id]);

        $query = Topic::find()
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