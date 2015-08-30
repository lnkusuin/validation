<?php
ini_set( 'display_errors', 1 );
/*
|--------------------------------------------------------------------------
| composerのautoloaderを登録
|--------------------------------------------------------------------------
| composerが自動生成するaudoloderを使用する。
|
*/
require __DIR__.'/vendor/autoload.php';

//デフォルトタイムゾーンの指定
date_default_timezone_set('asia/tokyo');