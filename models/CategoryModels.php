<?php

namespace terabyte\forum\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property string $name
 * @property integer $display_position
 *
 * @property SiteModels $forums
 */

class CategoryModels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(SiteModels::className(), ['category_id' => 'id'])
            ->inverseOf('category');
    }
}
