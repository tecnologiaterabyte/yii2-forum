<?php

use yii\helpers\Url;
use terabyte\forum\models\PostModels;
use terabyte\forum\models\UserModels;
use terabyte\forum\models\UserMention;


/* @var UserMention $userMentions */
/* @var UserModels $user */
/* @var PostModels $postmodels */

$this->title = Yii::t('forum', 'Menciones');
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', 'Main Board'), 'url' => ['site/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="columns">
    <div class="column one-fifth">
        <ul class="filter-list">
            <li>
                <a class="filter-item selected" href=""> <?= Yii::t('forum', 'вас есть:') ?> <span class="count"><?= count($userMentions) ?></span> <?= Yii::t('forum', 'Упоминания') ?> </a>
            </li>
        </ul>
    </div>

    <div class="column four-fifths">
        <?php if (!$userMentions): ?>
        <div class="blankslate spacious large-format">
            <h3><?= Yii::t('forum', 'Нет новых уведомлений') ?></h3>
        </div>
        <?php else: ?>
        <div class="notifications-list">
            <div class="boxed-group">
                <h3><?= Yii::t('forum', 'Вас упоминули в теме:') ?></h3>
                <ul class="boxed-group-inner list-group notifications">
                    <?php foreach($userMentions as $userMention): ?>
                    <li class="list-group-item">
                        <?php if ($userMention->post->getPostPage($userMention->post) > 1): ?>
                            <a href="<?= Url::toRoute(['post/view', 'id' => $userMention->topic_id]) ?>"><strong><?= $userMention->topic->subject ?></strong></a> <a href="<?= Url::toRoute(['post/view', 'id' => $userMention->topic_id, 'page' => ($userMention->post->getPostPage($userMention->post)), '#' => 'p' . $userMention->post_id]) ?>">#<?= $userMention->post_id ?></a>
                            <?php else: ?>
                                <a href="<?= Url::toRoute(['post/view', 'id' => $userMention->topic_id]) ?>"><strong><?= $userMention->topic->subject ?></strong></a> <a href="<?= Url::toRoute(['post/view', 'id' => $userMention->topic_id, '#' => 'p' . $userMention->post_id]) ?>">#<?= $userMention->post_id ?></a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>