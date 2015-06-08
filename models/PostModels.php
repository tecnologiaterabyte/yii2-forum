<?php

namespace terabyte\forum\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use terabyte\forum\helpers\MarkdownParser;
use terabyte\forum\Module as NotifyModule;


/**
 * @property integer $id
 * @property integer $topic_id
 * @property integer $user_id
 * @property integer $user_ip
 * @property string $message
 * @property integer $created_at
 * @property integer $edited_at
 * @property integer $edited_by
 *
 * @property UserModels $user
 * @property TopicModels $topic
 * @property string $displayMessage
 * @property ActiveDataProvider $dataProvider
 * @property boolean $isTopicAuthor
 */

class PostModels extends \yii\db\ActiveRecord
{
    private $_isTopicAuthor;

    private $pageSize;

    /**
     * @inheritdoc
     */

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = time();
            $this->user_ip = Yii::$app->getRequest()->getUserIP();
            $this->user_id = Yii::$app->getUser()->getIdentity()->getId();

            $currentUser = Yii::$app->getUser()->getIdentity();
            $currentUser->updateCounters(['number_posts' => 1]);
            $currentUser->last_posted_at = time();
            $currentUser->save();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->topic_id > 0) {

            /** @var NotifyModule $notify */

            $notify = Yii::$app->getModule('forum');
            $notify->mentionHandler($this);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */

    public static function tableName()
    {

        return '{{%post}}';
    }

    /**
     * @return ActiveQuery
     */

    public function getUser()
    {

        return $this->hasOne(UserModels::className(), ['id' => 'user_id'])
            ->inverseOf('posts');
    }

    /**
     * @return ActiveQuery
     */

    public function getTopic()
    {

        return $this->hasOne(TopicModels::className(), ['id' => 'topic_id']);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     */

    public static function getDataProviderByTopic($id)
    {
        $query = static::find()
            ->where(['topic_id' => $id])
            ->with('user')
            ->orderBy(['created_at' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeLimit' => false,
                'defaultPageSize' => Yii::$app->config->get('display_posts_count'),
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */

    public function getIsTopicAuthor()
    {
        if (isset($this->_isTopicAuthor)) {
            return $this->_isTopicAuthor;
        }

        return false;
    }

    /**
     * @inheritdoc
     */

    public function setIsTopicAuthor($value)
    {
        $this->_isTopicAuthor = (bool) $value;

        return $this;
    }

    /**
     * @return string
     */

    public function getDisplayMessage()
    {
        $parsedown = new MarkdownParser();

        return $parsedown->parse($this->message);
    }

    /*
    * Returns page number in topic by post.
    * @param Post $post post model.
    * @return integer
    */

    public function getPostPage($post)
    {
        $rows = PostModels::find()
            ->select('id')
            ->where(['topic_id' => $post->topic_id])
            ->asArray()
            ->all();

        $index = 1;

        foreach ($rows as $row) {
            if ($row['id'] == $post->id) {
                break;
            }
            $index++;
        }

        $page = ceil($index / Yii::$app->config->get('display_posts_count'));

        return $page;
    }

}
