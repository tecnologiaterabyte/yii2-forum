<?php

/*
 * This file is part of the Yii2-Forum Project.
 *
 * (c) Yii2-Forum Project <http://github.com/tecnologiaterabyte/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace terabyte\forum;

use yii;
use yii\base\Module as BaseModule;
use terabyte\forum\models\UserOnline;

/**
 * This is the main module class for the Yii2-Forum.
 *
 * @property array $modelMap
 *
 * @author Wilmer Arambula <tecnologiaterabyte@gmail.com>
 */
class Module extends BaseModule
{
    const VERSION = '0.1.0-dev';

    /** @var array Mailer configuration */
    public $mailer = [];

    /** @var array Model map */
    public $modelMap = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = '';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
    ];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $ip = Yii::$app->getRequest()->getUserIP();

            $userOnline = UserOnline::find()
                ->where(['user_ip' => $ip])
                ->one();

            if (!$userOnline instanceof UserOnline) {
                $userOnline = new UserOnline();
            }

            $userOnline->vizited_at = time();
            $userOnline->user_ip = $ip;

            if (!Yii::$app->getUser()->getIsGuest()) {
                $userOnline->user_id = Yii::$app->getUser()->getIdentity()->getId();
            } else {
                $userOnline->user_id = 0;
            }

            $userOnline->save();
            UserOnline::deleteInactiveUsers();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['forum'])) {
            Yii::$app->i18n->translations['forum'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'ru',
                'basePath' => '@terabyte/forum/messages'
            ];
        }
    }
}
