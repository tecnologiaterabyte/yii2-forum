<?php

namespace terabyte\forum\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property integer $forum_id
 * @property string $subject
 * @property integer $first_post_id
 * @property integer $first_post_user_id
 * @property string $first_post_username
 * @property integer $first_post_created_at
 * @property integer $last_post_id
 * @property integer $last_post_user_id
 * @property string $last_post_username
 * @property integer $last_post_created_at
 * @property integer $number_views
 * @property integer $number_posts
 * @property integer $closed
 * @property integer $sticked
 *
 * @property Post[] $posts
 * @property Post $post
 * @property SiteModels $forum
 */

class TopicModels extends \yii\db\ActiveRecord
{
    /** @var \terabyte\forum\models\Post */

    private $_post;

    /**
     * @inheritdoc
     */

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $user = Yii::$app->getUser()->getIdentity();

            $this->first_post_created_at = time();
            $this->first_post_username = $user->username;
            $this->first_post_user_id = $user->id;
            $this->last_post_created_at = time();
            $this->last_post_username = $user->username;
            $this->last_post_user_id = $user->id;
            $this->number_posts = 0;
            $this->number_views = 0;
            $this->first_post_id = $this->_post->id;
            $this->last_post_id = $this->_post->id;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */

    public static function tableName()
    {

        return '{{%topic}}';
    }

    /**
     * Counts all topics.
     * @return integer
     */

    public static function countAll()
    {

        return static::find()->count();
    }

    /**
     * @return ActiveQuery
     */

    public function getForum()
    {

        return $this->hasOne(SiteModels::className(), ['id' => 'forum_id']);
    }

    /**
     * @return ActiveQuery
     */

    public function getPosts()
    {

        return $this->hasMany(PostModels::className(), ['topic_id' => 'id'])
            ->inverseOf('topic');
    }

    /**
     * @param Post $post
     * @return self
     */

    public function setPost($post)
    {
        $this->_post = $post;

        return $this;
    }
}
