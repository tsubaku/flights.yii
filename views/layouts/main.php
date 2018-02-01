<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Учёт рейсов',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Справка',      'url' => ['/index/index']],
            //['label' => 'About',      'url' => ['/site/about']],
            //['label' => 'Contact',    'url' => ['/site/contact']],
            ['label' => 'Рейсы' ,       'url' => ['/manager/manager']],
            ['label' => 'Охранник',     'url' => ['/guard/guard']],
            ['label' => 'Клиенты',      'url' => ['/client/client']],
            ['label' => 'Охранники',    'url' => ['/signup/signup']],
            ['label' => 'Оружие',       'url' => ['/gun/gun']],
            ['label' => 'Постовая',     'url' => ['/sentry/sentry']],
            ['label' => 'Настройки',    'url' => ['/settings/settings']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login',    'url' => ['/login/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/login/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; --- <?= date('Y') ?>г.</p>

        <p class="pull-right"><?//= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
