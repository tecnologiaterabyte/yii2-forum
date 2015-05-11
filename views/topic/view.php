<?php

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use terabyte\forum\components\View;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\controllers\ForumController;
use terabyte\forum\models\Forum;
use terabyte\forum\models\Post;
use terabyte\forum\models\PostForm ;
use terabyte\forum\models\Topic;
use terabyte\forum\widgets\EditorWidget;
use terabyte\forum\widgets\LinkPager;
use terabyte\forum\widgets\PostWidget;


/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var ActiveRecord $posts
 * @var Forum $forum
 * @var Topic $topic
 * @var Post $post
 * @var PostForm $model
 */

$users = ArrayHelper::getColumn($posts, 'user');
$usernames = ArrayHelper::getColumn($users, 'username');
$author = implode(', ', array_unique($usernames));

$this->title = $topic->subject;
$this->subtitle = Yii::t('forum', 'вернуться в раздел') . Html::a($topic->forum->name, Url::to(['forum/view', 'id' => $topic->forum->id]));
$this->description = $topic->subject;
$this->author = $author;

$item['post_count'] = $dataProvider->pagination->offset;

ForumAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', 'Main Board'), 'url' => ['forum/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', $topic->forum->name), 'url' => ['forum/view', 'id' => $topic->forum->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= terabyte\forum\widgets\pageHead::widget(['title' => $this->title, 'subtitle' => $this->subtitle]) ?>

    <div class="topic-view">
        <div id="t<?= $topic->id ?>" class="topic-discussion">
            <?php foreach($posts as $post): ?>
                <?php $item['post_count']++ ?>
                <?= PostWidget::widget([
                    'model' => $post,
                    'topic' => $topic,
                    'count' => $item['post_count'],
                ]) ?>
            <?php endforeach; ?>
        </div>
        <?php if (!Yii::$app->getUser()->getIsGuest()): ?>
            <?= EditorWidget::widget([
                'activeFormOptions' => [
                    'action' => Url::to(['topic/view', 'id' => $topic->id, '#' => 'postform']),
                ],
                'model' => $model,
                'messageAttribute' => 'message',
            ]) ?>
        <?php endif; ?>
        <div class="pagination-center">
            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
        </div>
    </div>

<?php $this->registerJs("jQuery(document).post();") ?>