<?php

use yii\helpers\Url;
use yii\web\View;
use cebe\gravatar\Gravatar;
use yii\helpers\Html;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\models\PostModels;
use terabyte\forum\models\TopicModels;

/* @var View $this */
/* @var TopicModels $topic */
/* @var PostModels $model */

ForumAsset::register($this);

$formatter = Yii::$app->formatter;

?>
<div class="post <?= ($count % 2 == 0) ? Yii::t('forum', 'roweven') : Yii::t('forum', 'rowodd') ?><?= ($count == 1) ? Yii::t('forum', 'firstpost') : '' ?>" id="p<?= $model->id ?>">
    <div class="post-avatar">
        <?php if (isset($model->user->email)): ?>
            <?= Gravatar::widget([
                'email' => $model->user->email,
                'options' => [
                    'alt' => $model->user->username,
                    'class' => 'avatar',
                    'width' => 48,
                    'height' => 48,
                ],
                'defaultImage' => 'retro',
                'size' => 48
            ]); ?>
        <?php endif; ?>
        <div style = "text-align: center">
            <?= Html::a($model->user->username, Url::toRoute(['/user/default/view', 'id' => $model->user->id])); ?>
        </div>
    </div>
    <div class="post-container">
        <div class="post-content">
            <div class="post-header">
                <span class="post-header-user"><?= Html::a((isset($model->user->username)) ? $model->user->username : '', Url::to(['user/view', 'id' => $model->user_id]), $options = ['class' => 'muted-link']) ?></span> <?= Yii::t('forum', 'написал') ?>
                <span class="post-header-time"><?= $formatter->asDatetime($model->created_at) ?></span>
                <?php if (Yii::$app->getRequest()->getQueryParam('page') > 1): ?>
                    <span class="post-header-count"><?= Html::a('#'. $count, Url::to(['post/view', 'id' => $model->topic->id, 'page' => Yii::$app->getRequest()->getQueryParam('page'),'#' => 'p' . $model->id]), $options = ['class' => 'muted-link']) ?></span>
                    <?php else : ?>
                        <span class="post-header-count"><?= Html::a('#'. $count, Url::to(['post/view', 'id' => $model->topic->id, '#' => 'p' . $model->id]), $options = ['class' => 'muted-link']) ?></span>
                <?php endif; ?>
                <?php if ($model->isTopicAuthor): ?>
                    <span class="post-header-owner"><?= Yii::t('forum', 'Автор') ?></span>
                <?php endif; ?>
                <?php if (!Yii::$app->getUser()->can('updatePost', ['post' => $model])): ?>
                    <div class="post-header-actions">
                        <a class="post-header-action js-post-update-pencil" href="#"><span class="octicon octicon-pencil octicon-btn"></span></a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="post-message markdown-body">
                <?= $model->displayMessage ?>
            </div>
            <?php if (!Yii::$app->getUser()->can('updatePost', ['post' => $model])): ?>
                <div class="post-update">
                    <?= Html::textarea('post-update-message', $model->message, ['class' => 'form-control post-update-message']) ?>
                    <div class="post-preview postmsg markdown-body"></div>
                    <div class="form-actions">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary js-post-update-button']) ?>
                        <?= Html::submitButton('Отменить', ['class' => 'btn btn-danger js-post-cancel-button']) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
