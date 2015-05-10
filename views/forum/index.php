<?php

use yii\helpers\Html;
use yii\helpers\Url;
use terabyte\forum\models\Post;
use terabyte\forum\models\Topic;
use terabyte\forum\models\UserOnline;
use terabyte\forum\models\User;
use terabyte\forum\assets\ForumAsset;

/**
 * @var \terabyte\forum\components\View $this
 * @var \yii\db\ActiveRecord[] $categories
 * @var \terabyte\forum\models\Category $category
 * @var \terabyte\forum\models\Forum $forum
 */

ForumAsset::register($this);

$item = [
    'forum_count' => 0,
    'category_count' => 0,
];

$formatter = Yii::$app->formatter;

$this->subtitle =  Yii::t('forum', 'вернуться в раздел');

?>
<div class="page-index">
    <?php foreach($categories as $category): ?>
        <?php $item['category_count']++ ?>
        <div id="category<?= $item['category_count'] ?>" class="columns">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class=""><?= $formatter->asText($category->name) ?></th>
                        <th class="tens"><?= Yii::t('forum', 'Topics') ?></th>
                        <th class="tens"><?= Yii::t('forum', 'Posts') ?></th>
                        <th class="one-fourth"><?= Yii::t('forum', 'Last post') ?></th>
                        <th class="tens"><?= Yii::t('forum', 'Autor') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($category->forums as $forum): ?>
                        <?php $item['forum_count']++ ?>
                        <tr class="<?= ($item['forum_count'] % 2 == 0) ? 'roweven' : 'rowodd' ?>">
                            <td class="table-column-title">
                                <?= Html::a($formatter->asText($forum->name),Url::to(['/forum/forum/view', 'id' => $forum->id])) ?>
                            </td>
                            <td><?= $formatter->asInteger($forum->number_topics) ?></td>
                            <td><?= $formatter->asInteger($forum->number_posts) ?></td>
                            <td>
                                <?php if ($forum->last_post_created_at): ?>
                                    <?= Html::a($formatter->asDatetime($forum->last_post_created_at),Url::to(['/forum/post/view', 'id' => $forum->last_post_user_id, '#' => 'p' . $forum->last_post_user_id])) ?>
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
                <li><?= Yii::t('forum', 'Тем:') ?> <strong><?= $formatter->asInteger(Topic::countAll()) ?></strong></li>
                <li><?= Yii::t('forum', 'Сообщений:') ?> <strong><?= $formatter->asInteger(Post::find()->count()) ?></strong></li>
            </ul>
            <ul class="left">
                <li><?= Yii::t('forum', 'Количество пользователей:') ?> <strong><?= $formatter->asInteger(User::find()->count()) ?></strong></li>
                <li><?= Yii::t('forum', 'Последним зарегистрировался:') ?> <?= Html::a('X','#') ?>
            </ul>
        </div>
        <div class="onlinelist">
            <span><strong><?= Yii::t('forum', 'Сейчас на форуме:') ?> </strong> <?= UserOnline::countGuests() ?> <?= Yii::t('forum', 'гостей:') ?>, <?= UserOnline::countUsers() ?> <?= Yii::t('forum', 'пользователей:') ?>, <?= implode(', ', \yii\helpers\ArrayHelper::getColumn(UserOnline::getActiveUsers(), 'username')) ?></span>
        </div>
    </div>
</div>