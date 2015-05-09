<?php
namespace terabyte\forum\widgets;

use Yii;
use yii\base\InvalidConfigException;
use terabyte\forum\models\Post as PostModel;
use terabyte\forum\models\Topic;
use terabyte\forum\models\User;
use terabyte\forum\assets\PostAsset;

class Post extends \yii\base\Widget
{
    /**
     * @var PostModel
     */
    public $model;
    /**
     * @var Topic
     */
    public $topic;
    /**
     * @var integer
     */
    public $count;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->model instanceof PostModel) {
            throw new InvalidConfigException('The "model" property must be set.');
        }

        if ($this->topic instanceof Topic && $this->model->user instanceof User) {
            if ($this->topic->first_post_user_id == $this->model->user->id) {
                $this->model->setIsTopicAuthor(true);
            }
        }

        if (!isset($this->count)) {
            $this->count = 1;
        }

        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->render('/post/post', [
            'model' => $this->model,
            'count' => $this->count,
        ]);
    }

    /**
     * Register widget client scripts.
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        PostAsset::register($view);
        $view->registerJs("jQuery('#p" . $this->model->id . "').post();");
    }
}