<?php

use cebe\gravatar\Gravatar;
use yii\helpers\Html;
use yii\helpers\Url;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\components\View;
use terabyte\forum\models\User;
use terabyte\forum\models\PostForm;
use terabyte\forum\widgets\ActiveForm;

/* @var View $this
 * @var User $user
 * @var PostForm $model
 * @var string $titleAttribute
 * @var string $messageAttribute
 * @var array $activeFormOptions */

ForumAsset::register($this);

?>

<div class="post-form-box">
    <div class="post-avatar">
        <?php if ($user->email): ?>
            <?= Gravatar::widget([
                'email' => $user->email,
                'options' => [
                    'alt' => $user->username,
                    'class' => 'avatar',
                    'width' => 48,
                    'height' => 48,
                ],
                'defaultImage' => 'retro',
                'size' => 48
            ]); ?>
        <?php endif; ?>
        <div style = "text-align: center">
            <?= Html::a($user->username, Url::toRoute(['/user/default/view', 'id' => $user->id])); ?>
        </div>
    </div>
    <div class="post-form-box-content clearfix">
        <?php $form = ActiveForm::begin($activeFormOptions) ?>
        <?= $form->errorSummary($model, [
            'header' => '',
        ]) ?>
        <?php if ($titleAttribute):?>
            <?= $form->field($model, $titleAttribute, [
                'template' => "{input}",
            ])->textInput([
                'placeholder' => Yii::t('forum', 'Напишите сообщение'),
            ])
                ->label(\Yii::t('forum', 'Subject'))
            ?>
        <?php endif; ?>
        <div class="editor-btn-panel">
            <div class="btn-group">
                <?= Html::button('<span class="fa fa-bold"></span>', ['title' => Yii::t('forum', 'Полужирный текст'), 'class' => 'btn btn-sm js-btn-texticon-bold']) ?>
                <?= Html::button('<span class="fa fa-italic"></span>', ['title' => Yii::t('forum', 'Курсивный текст'), 'class' => 'btn btn-sm js-btn-texticon-italic']) ?>
                <?= Html::button('<span class="fa fa-strikethrough"></span>', ['title' => Yii::t('forum', 'Зачеркнутый текст'), 'class' => 'btn btn-sm js-btn-texticon-strike']) ?>
                <?= Html::button('<span class="fa fa-indent"></span>', ['title' => Yii::t('forum', 'Отступ вправо'), 'class' => 'btn btn-sm js-btn-texticon-indent']) ?>
                <?= Html::button('<span class="fa fa-outdent"></span>', ['title' => Yii::t('forum', 'Отступ влево'), 'class' => 'btn btn-sm js-btn-texticon-unindent']) ?>
            </div>
            <div class="btn-group">
                <?= Html::button('<span class="fa fa-link"></span>', ['title' => Yii::t('forum', 'Вставка гиперссылки (URL)'), 'class' => 'btn btn-sm js-btn-texticon-link']) ?>
                <?= Html::button('<span class="fa fa-picture-o"></span>', ['title' => Yii::t('forum', 'Вставка картинки'), 'class' => 'btn btn-sm js-btn-texticon-img']) ?>
            </div>
            <div class="btn-group">
                <?= Html::button('<span class="fa fa-list"></span>', ['title' => Yii::t('forum', 'Список'), 'class' => 'btn btn-sm js-btn-texticon-bulleted']) ?>
                <?= Html::button('<span class="fa fa-list-ol"></span>', ['title' => Yii::t('forum', 'Нумерованный список'), 'class' => 'btn btn-sm js-btn-texticon-numbered']) ?>
            </div>
            <div class="btn-group">
                <?= Html::button('<span class="fa fa-comment"></span>', ['title' => Yii::t('forum', 'Цитата'), 'class' => 'btn btn-sm js-btn-texticon-quote']) ?>
                <?= Html::button('<span class="fa fa-code"></span>', ['title' => Yii::t('forum', 'Код'), 'class' => 'btn btn-sm js-btn-texticon-blockcode']) ?>
            </div>
            <div class="right btn-group">
                <?= Html::button('<span class="fa fa-eye"></span>', ['title' => Yii::t('forum', 'Предпросмотр сообщения'), 'class' => 'btn btn-sm js-editor-preview']) ?>
            </div>
        </div>
        <?= $form->field($model, $messageAttribute, [
            'template' => "{input}",
        ])->textarea([
            'placeholder' => Yii::t('forum', 'Напишите сообщение'),
            'id' => 'postform-message',
        ]) ?>
        <div class="editor-preview markdown-body"></div>
        <div class="editor-tips left">
            <span class="fa fa-hand-o-right"></span> При оформлении сообщения Вы можете использовать разметку <strong><a target="_blank" class="muted-link" href="<?= Url::toRoute('forum/markdown') ?>">markdown</a></strong>.<br />
            <span class="fa fa-hand-o-right"></span> Для обращения к участнику дискуссии текущей темы введите <strong>@</strong> и выберите пользователя.
        </div>
        <div class="form-actions right">
            <?= Html::submitButton(Yii::t('forum', 'Отправить'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>