<?php

use yii\helpers\Url;
use terabyte\forum\models\Forum;
use terabyte\forum\widgets\Editor;


/** @var \terabyte\forum\components\View $this */
/** @var Forum $forum */

$this->title = Yii::t('app/topic', 'Title') . ' в разделе ' . $forum->name;
$this->subtitle = 'вернуться в раздел <a href="' . Url::to(['/forum/default/view', 'id' => $forum->id]) . '">' . $forum->name . '</a>';

?>
<div class="page-create-topic">
    <?= Editor::widget([
        'model' => $model,
        'titleAttribute' => 'subject',
        'messageAttribute' => 'message',
    ]) ?>
</div>
