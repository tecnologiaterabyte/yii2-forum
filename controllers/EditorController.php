<?php

namespace terabyte\forum\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use terabyte\forum\models\PostModels;

/**
 * Class EditorController
 */

class EditorController extends \yii\web\Controller
{
    /**
     * @return string
     */

    public function actionMention()
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $id = substr(Yii::$app->getRequest()->post('id'), 1);

            $posts = PostModels::find()
                ->with('user')
                ->where(['topic_id' => $id])
                ->asArray()
                ->all();

            $currentUser = Yii::$app->getUser()->getIdentity();
            $users = ArrayHelper::getColumn($posts, 'user');
            $usernames = array_unique(ArrayHelper::getColumn($users, 'username'));

            $key = array_search($currentUser->username, $usernames);
            if (is_array($usernames) && is_numeric($key) && array_key_exists($key, $usernames)) {
                unset($usernames[$key]);
            }

            $usernames = array_values($usernames);

            return $usernames;
        }

        throw new NotFoundHttpException();
    }
}