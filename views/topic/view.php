<?php

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use terabyte\forum\assets\ForumAsset;
use terabyte\forum\models\Post;
use terabyte\forum\models\PostForm ;
use terabyte\forum\models\Topic;
use terabyte\forum\widgets\Editor;
use terabyte\forum\widgets\LinkPager;
use terabyte\forum\widgets\PostWidget;


/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var ActiveRecord $posts
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
                    'action' => Url::toRoute(['/forum/topic/view', 'id' => $topic->id, '#' => 'postform']),
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