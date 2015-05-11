<?php

use \yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\models\Forum;
use terabyte\forum\models\Topic;
use terabyte\forum\widgets\LinkPager;
use terabyte\forum\widgets\TopicPager;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var ActiveRecord $topics
 * @var Forum $forum
 * @var Topic $topic
 */

$this->title = $forum->name;
$formatter = Yii::$app->formatter;
$item['topic_count'] = 0;

ForumAsset::register($this);

$this->title = Yii::t('forum', 'Topic List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', 'Main Board'), 'url' => ['forum/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="forum-view">
    <div class="question-list-header">
        <div class="question-list-title">
            <h3><?= Yii::t('forum', 'Новости') ?></h3>
        </div>
        <?php if (!Yii::$app->getUser()->getIsGuest()): ?>
            <div class="question-list--topic-create">
                <?= Html::a(Yii::t('forum', 'Create topic'), Url::to(['/forum/topic/create', 'id' => $forum->id]),  $options = ['class' => 'btn btn-sm btn-outline']) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="question-list">
        <?php foreach ($topics as $topic): ?>
            <div class="question-row<?= ($topic->sticked) ? ' question-row-sticked' : '' ?><?= ($topic->closed) ? ' question-row-closed' : '' ?>">
                <div class="question-info">
                    <div class="views">
                        <div class="mini-counts">
                            <span title="41 views"><?= Yii::$app->formatter->asInteger($topic->number_views) ?></span>
                        </div>
                        <div><?= Yii::t('forum', 'просмотров') ?></div>
                    </div>
                    <div class="answers <?= ($topic->number_posts == 0) ? '' : ' answered' ?>">
                        <div class="mini-counts">
                            <span title="2 answers"><?= Yii::$app->formatter->asInteger($topic->number_posts) ?></span>
                        </div>
                        <div><?= Yii::t('forum', 'ответов') ?></div>
                    </div>
                </div>
                <div class="question-summary">
                    <h3>
                        <?= Html::a($formatter->asText($topic->subject), Url::toRoute(['topic/view', 'id' => $topic->id])) ?>
                        <?= TopicPager::widget(['topic' => $topic]) ?>
                    </h3>
                    <div class="question-tags">
                        <?= Html::a($forum->name, Url::toRoute(['forum/view', 'id' => $forum->id])) ?>
                    </div>
                    <div class="question-author">
                        <?= ($topic->number_posts == 0) ? Yii::t('forum', 'вопрос задал') : Yii::t('forum', 'последним ответил') ?>
                        <?= Html::a($formatter->asText($topic->last_post_username), '#') ?>
                        <?= Html::a(Yii::$app->formatter->asDatetime($topic->last_post_created_at), '#', $options = ['class' => 'muted-link']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination-center">
        <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
    </div>
</div>