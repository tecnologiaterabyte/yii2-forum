<?php

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use terabyte\forum\models\Forum;
use terabyte\forum\models\Topic;
use terabyte\forum\assets\ForumAsset;
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
    <?php if (!Yii::$app->getUser()->getIsGuest()): ?>
        <div class="topic-list-header clearfix">
            <span class="topic-create-link">
                <?= Html::a(Yii::t('forum', 'Create topic'),Url::to(['/forum/topic/create', 'id' => $forum->id])) ?>
            </span>
        </div>
    <?php endif; ?>
    <div class="topics-list">
        <?php foreach ($topics as $topic): ?>
            <div class="topic-row<?= ($topic->sticked) ? ' sticked' : '' ?><?= ($topic->closed) ? ' closed' : '' ?>">
                <div class="topic-row-icon">
                    <?= ($topic->sticked) ? '<span class="octicon octicon-pin"></span>' : '<span class="octicon octicon-primitive-dot"></span>' ?>
                </div>
                <div class="topic-row-cell left">
                    <?= Html::a($formatter->asText($topic->subject), Url::toRoute(['/forum/topic/view', 'id' => $topic->id]), $options = ['class' => 'topic-row-link'])  ?>
                    <?= TopicPager::widget(['topic' => $topic]) ?>
                    <div class="topic-row-meta">
                        <span><?= Yii::t('forum', 'Тему создал:') ?>
                            <?= Html::a($formatter->asText($topic->first_post_username), Url::toRoute(['/user/profile/show', 'id' => $topic->first_post_user_id]), $options = ['class' => 'muted-link'])  ?>
                        </span>
                        <span class="topic-last-post right">
                            <span class="tooltipped tooltipped-w" aria-label="Сообщение от <?= $topic->last_post_username ?>">
                                <?= Html::a(Yii::$app->formatter->asDatetime($topic->last_post_created_at), Url::toRoute(['/forum/topic/view', 'id' => $topic->last_post_id, '#' => 'p' . $topic->last_post_id ]), $options = ['class' => 'muted-link'])  ?>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="<?= ($topic->number_posts == 0) ? 'topic-row-post-null' : 'topic-row-post-count' ?>">
                    <?= Yii::$app->formatter->asInteger($topic->number_posts) ?>
                    <span class="octicon octicon-comment-discussion"></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination-center">
        <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
    </div>
</div>