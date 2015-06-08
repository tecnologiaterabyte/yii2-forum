<?php

namespace terabyte\forum\models;

use Yii;
use terabyte\forum\helpers\MarkdownParser;

/**
 * Class TopicForm
 *
 * @property Topic $topic
 */

class TopicForm extends \yii\base\Model
{
    /**
     * @var string
     */

    public $subject;

    /**
     * @var string
     */

    public $message;

    /**
     * @var Forum
     */

    public $forum;
    /**
     * @var Forum
     */
    public $topic;

    /**
     * @inheritdoc
     */

    public function rules()
    {

        return [
            ['subject', 'trim'],
            ['subject', 'required', 'message' => Yii::t('forum', 'Required topic subject')],
            ['subject', 'string', 'min' => 6, 'tooShort' => Yii::t('forum', 'String short topic subject')],
            ['subject', 'string', 'max' => 255, 'tooLong' => Yii::t('forum', 'String long topic subject')],

            ['message', 'trim'],
            ['message', 'required', 'message' => Yii::t('forum', 'Required message')],
            ['message', 'string', 'min' => 6, 'tooShort' => Yii::t('forum', 'String short topic message')],
            ['message', 'string', 'max' => 65534, 'tooLong' => Yii::t('forum', 'String long topic message')],
        ];
    }

    /**
     * @param SiteModels $forum
     * @return boolean
     */

    public function create($forum)
    {
        // very, so much, stupid source code :)
        if ($this->validate()) {
            $user = Yii::$app->getUser()->getIdentity();

            // create post
            $post = new PostModels();
            $post->topic_id = 0;
            $post->message = $this->message;
            $post->save();

            if ($post->save()) {
                // create topic
                $topic = new TopicModels();
                $topic->forum_id = $forum->id;
                $topic->subject = $this->subject;
                $topic->post = $post;
                $topic->save();

                // update post.topic_id
                $post->link('topic', $topic);

                // update forum information
                $forum->updateCounters(['number_topics' => 1]);
                $forum->last_post_created_at = time();
                $forum->last_post_user_id = $post->id;
                $forum->last_post_username = $user->username;
                $forum->save();

                $this->topic = $topic;

                return true;
            }
        }
    }
}
