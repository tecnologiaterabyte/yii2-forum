<?php

/*
 * This file is part of the Yii2-Forum Project.
 *
 * (c) Yii2-Forum Project <http://github.com/tecnologiaterabyte/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace terabyte\forum;

use yii;
use yii\base\Module as BaseModule;
use terabyte\forum\helpers\MentionHelper;
use terabyte\forum\models\UserMention;
use terabyte\forum\models\UserModels;
use terabyte\forum\models\UserOnline;

/**
 * This is the main module class for the Yii2-Forum.
 *
 * @property array $modelMap
 *
 * @author Wilmer Arambula <tecnologiaterabyte@gmail.com>
 */
class Module extends BaseModule
{
    const VERSION = '0.1.0-dev';

    /** @var array Mailer configuration */
    public $mailer = [];

    /** @var array Model map */
    public $modelMap = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = '';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
    ];

    /**
     * @inheritdoc
     */

    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['forum'])) {
            Yii::$app->i18n->translations['forum'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'ru',
                'basePath' => '@terabyte/forum/messages'
            ];
        }
    }

    /**
     * @inheritdoc
     */

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $ip = Yii::$app->getRequest()->getUserIP();

            $userOnline = UserOnline::find()
                ->where(['user_ip' => $ip])
                ->one();

            if (!$userOnline instanceof UserOnline) {
                $userOnline = new UserOnline();
            }

            $userOnline->vizited_at = time();
            $userOnline->user_ip = $ip;

            if (!Yii::$app->getUser()->getIsGuest()) {
                $userOnline->user_id = Yii::$app->getUser()->getIdentity()->getId();
            } else {
                $userOnline->user_id = 0;
            }

            $userOnline->save();
            UserOnline::deleteInactiveUsers();

            return true;
        } else {
            return false;
        }
    }

    /*
     * @param PostModels $post
     * @return boolean
     */

    public function mentionHandler($post)
    {

        $usernames = MentionHelper::find($post->message);


        if (!empty($usernames)) {

            foreach ($usernames as $username) {

                /** @var UserModels $mentioned */

                $mentioned = UserModels::findByUsername($username);


                if (!$mentioned instanceof UserModels) {
                    continue;
                }

                $exist = UserMention::find()
                    ->where([
                        'post_id' => $post->id,
                        'mention_user_id' => $mentioned->id,
                        'status' => UserMention::MENTION_STATUS_UNVIEWED,
                    ])
                    ->exists();
                if ($exist) {
                    continue;
                }

                $currentUser = Yii::$app->getUser()->getIdentity();
                $model = new UserMention();
                $model->user_id = $currentUser->id;
                $model->mention_user_id = $mentioned->id;
                $model->post_id = $post->id;
                $model->topic_id = $post->topic->id;
                $model->status = UserMention::MENTION_STATUS_UNVIEWED;

                if ($mentioned->notify_mention_web == 1) {
                    $model->save();
                }

                if ($mentioned->notify_mention_email == 1) {
                    \Yii::$app->mailer->compose(['text' => 'mention'], [
                        'model' => $model,
                        'topic' => $post->topic,
                    ])
                        ->setFrom([Yii::$app->config->get('support_email') => Yii::$app->config->get('site_title')])
                        ->setTo([$model->mentionUser->email => $model->mentionUser->username])
                        ->setSubject('#' . $post->id . ' ' . $post->topic->subject)
                        ->send();
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */

    public static function notifications($user)
    {
        $userMentions = UserMention::find()
            ->with([
                'post'=> function ($query) {
                    $query->andWhere(['status' => UserMention::MENTION_STATUS_UNVIEWED]);
                },
                'topic',
            ])
            ->where(['mention_user_id' => $user->id])
            ->andWhere(['status' => UserMention::MENTION_STATUS_UNVIEWED])
            ->all();
        return $userMentions;
    }

}
