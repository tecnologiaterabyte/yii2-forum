<?php

use \yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use terabyte\forum\models\Category;
use terabyte\forum\models\SiteModels;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\models\PostModels;
use terabyte\forum\models\TopicModels;
use terabyte\forum\models\UserOnline;
use terabyte\forum\models\UserModels;

/**
 * @var View $this
 * @var ActiveRecord $categories
 * @var Category $category
 * @var SiteModels $forum
 */

ForumAsset::register($this);

$item = [
    'forum_count' => 0,
    'category_count' => 0,
];

$formatter = Yii::$app->formatter;

$this->title = Yii::t('forum', 'Main Board');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="forum-index">
    <?php foreach($categories as $category): ?>
        <?php $item['category_count']++ ?>
        <div id="category<?= $item['category_count'] ?>" class="columns">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class=""><?= $formatter->asText($category->name) ?></th>
                    <th class="tens"><?= Yii::t('forum', 'Тем') ?></th>
                    <th class="tens"><?= Yii::t('forum', 'Сообщений') ?></th>
                    <th class="one-fourth"><?= Yii::t('forum', 'последнее Сообщений') ?></th>
                    <th class="tens"><?= Yii::t('forum', 'Автор') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($category->forums as $forum): ?>
                    <?php $item['forum_count']++ ?>
                    <tr class="<?= ($item['forum_count'] % 2 == 0) ? 'roweven' : 'rowodd' ?>">
                        <td class="table-column-title">
                            <?= Html::a($formatter->asText($forum->name), Url::to(['topic/view', 'id' => $forum->id])) ?>
                        </td>
                        <td><?= $formatter->asInteger($forum->number_topics) ?></td>
                        <td><?= $formatter->asInteger($forum->number_posts) ?></td>
                        <td>
                            <?php if ($forum->last_post_created_at): ?>
                                <?= Html::a($formatter->asDatetime($forum->last_post_created_at), Url::to(['post/view', 'id' => $forum->last_post_user_id, '#' => 'p' . $forum->last_post_user_id])) ?>
                            <?php else: ?>
                                <?= $formatter->asDatetime($forum->last_post_created_at) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="byuser"> <?= $forum->last_post_username ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
    <div class="statistic">
        <div class="clearfix">
            <ul class="right">
                <li><?= Yii::t('forum', 'Тем:') ?> <strong><?= $formatter->asInteger(TopicModels::countAll()) ?></strong></li>
                <li><?= Yii::t('forum', 'Сообщений:') ?> <strong><?= $formatter->asInteger(PostModels::find()->count()) ?></strong></li>
            </ul>
            <ul class="left">
                <li><?= Yii::t('forum', 'Количество пользователей:') ?> <strong><?= $formatter->asInteger(UserModels::find()->count()) ?></strong></li>
                <li><?= Yii::t('forum', 'Последним зарегистрировался:') ?> <?= Html::a('X','#') ?>
            </ul>
        </div>
        <div class="onlinelist">
            <span><strong><?= Yii::t('forum', 'Сейчас на форуме:') ?> </strong><?= Yii::t('forum', 'гостей:') ?> <?= UserOnline::countGuests() ?> , <?= UserOnline::countUsers() ?> <?= Yii::t('forum', 'пользователей:') ?> - <?= implode(', ', \yii\helpers\ArrayHelper::getColumn(UserOnline::getActiveUsers(), 'username')) ?></span>
        </div>
    </div>
</div>