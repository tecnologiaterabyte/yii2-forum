<?php
namespace terabyte\forum\widgets;

use Yii;
use yii\base\InvalidConfigException;
use terabyte\forum\models\PostModels as PostModel;
use terabyte\forum\models\TopicModels;
use terabyte\forum\models\UserModels;
use terabyte\forum\assets\PostAsset;

class PostWidget extends \yii\base\Widget
{
    /**
     * @var PostModel
     */
    public $model;
    /**
     * @var TopicModels
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

        if ($this->topic instanceof TopicModels && $this->model->user instanceof UserModels) {
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
        echo $this->render('post', [
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