<?php

namespace terabyte\forum\models;

use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $mention_user_id
 * @property integer $post_id
 * @property integer $topic_id
 * @property boolean $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property User $mentionUser
 */

class UserMention extends \yii\db\ActiveRecord
{
    const MENTION_STATUS_UNVIEWED = 0;
    const MENTION_STATUS_VIEWED = 1;

    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */

    public static function tableName()
    {

        return 'user_mention';
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {

        return $this->hasOne(UserModels::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMentionUser()
    {

        return $this->hasOne(UserModels::className(), ['id' => 'mention_user_id']);
    }

    /**
     * @return ActiveQuery
     */

    public function getTopic()
    {

        return $this->hasone(TopicModels::className(), ['id' => 'topic_id']);
    }

    /**
     * @return ActiveQuery
     */

    public function getPost()
    {

        return $this->hasone(PostModels::className(), ['id' => 'post_id']);
    }

    public static function countByUser($id)
    {

        return static::find()
            ->where(['mention_user_id' => $id, 'status' => self::MENTION_STATUS_UNVIEWED])
            ->count();
    }
}
