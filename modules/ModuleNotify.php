<?php

namespace terabyte\forum\modules;

use Yii;
use terabyte\forum\helpers\MentionHelper;
use terabyte\forum\models\UserMention;
use terabyte\forum\models\Post;
use terabyte\forum\models\User;

class ModuleNotify extends \yii\base\Module
{
    public $controllerNamespace = 'terabyte\forum\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @param Post $post
     * @return boolean
     */
    public function mentionHandler($post)
    {
        $usernames = MentionHelper::find($post->message);
        if (!empty($usernames)) {
            foreach ($usernames as $username) {
                /** @var User $mentioned */
                $mentioned = User::findByUsername($username);
                if (!$mentioned instanceof User) {
                    continue;
                }

                $exist = UserMention::find()
                    ->where([
                        'post_id' => $post->id,
                        'mention_user_id' => $mentioned->id,
                        'status' => UserMention::MENTION_SATUS_UNVIEWED,
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
                $model->status = UserMention::MENTION_SATUS_UNVIEWED;

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
}
