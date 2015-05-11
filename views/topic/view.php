<?php

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\controllers\ForumController;
use terabyte\forum\models\Forum;
use terabyte\forum\models\Post;
use terabyte\forum\models\PostForm ;
use terabyte\forum\models\Topic;
use terabyte\forum\widgets\Editor;
use terabyte\forum\widgets\LinkPager;
use terabyte\forum\widgets\PostWidget;


/**
 * @var ForumController $control
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

$item['post_count'] = $dataProvider->pagination->offset;

ForumAsset::register($this);

$this->title = Yii::t('forum', 'Post List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', 'Main Board'), 'url' => ['forum/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', 'Topic List'), 'url' =>  Url::previous()];
$this->params['breadcrumbs'][] = $this->title;

?>
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
            <?= Editor::widget([
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