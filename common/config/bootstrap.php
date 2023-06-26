<?php

Yii::setAlias('@root', dirname(dirname(__DIR__)));
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@speedrunner', dirname(dirname(__DIR__)) . '/speedrunner');

switch (YII_ENV) {
    case 'dev':
        error_reporting(E_ALL);
        break;
    case 'prod':
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        break;
}



Yii::$container->set('yii\validators\ExistValidator', [
    'filter' => function($query) {
        \yii\helpers\ArrayHelper::remove($query->where, 1);
    },
]);

Yii::$container->set('yii\validators\UniqueValidator', [
    'filter' => function($query) {
        \yii\helpers\ArrayHelper::remove($query->where, 1);
    },
]);

//        Global functions

function avg(array $array) {
    return round(array_sum($array) / count($array));
}
