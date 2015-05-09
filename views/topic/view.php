<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use terabyte\forum\widgets\LinkPager;
use terabyte\forum\widgets\Editor;
use terabyte\forum\widgets\Post;
use terabyte\forum\assets\ForumAsset;

/* @var \terabyte\forum\components\View $this */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \yii\db\ActiveRecord[] $posts */
/* @var \terabyte\forum\models\Topic $topic */
/* @var \terabyte\forum\models\Post $post */
/* @var \terabyte\forum\models\PostForm $model */

$users = ArrayHelper::getColumn($posts, 'user');
$usernames = ArrayHelper::getColumn($users, 'username');
$author = implode(', ', array_unique($usernames));

$this->title = $topic->subject;
$this->subtitle = 'вернуться в раздел <a href="' . Url::to(['/forum/view', 'id' => $topic->forum->id]) . '">' . $topic->forum->name . '</a>';
$this->description = $topic->subject;
$this->author = $author;

$item['post_count'] = $dataProvider->pagination->offset;

ForumAsset::register($this);

?>
<div class="page-viewtopic">
    <div id="t<?= $topic->id ?>" class="topic-discussion">
        <?php foreach($posts as $post): ?>
            <?php $item['post_count']++ ?>
            <?= Post::widget([
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