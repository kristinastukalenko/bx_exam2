<?php
$eventManager = \Bitrix\Main\EventManager::getInstance();

// [ex2-50] Проверка при деактивации товара
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['Ex2\Handlers\ProductDeactivation', 'OnBeforeIBlockElementUpdateHandler']);

// [ex2-93] Записывать в Журнал событий - открытие не существующих страниц сайта
// Событие вызывается в конце визуальной части эпилога сайта
$eventManager->addEventHandler("main", "OnEpilog", ["Ex2\Handlers\Check404", "onEpilogHandler"]);

// [ex2-51] Изменение данных в письме
$eventManager->addEventHandler("main", "OnBeforeEventAdd", ["Ex2\Handlers\FeedbackAuthor", "onBeforeEventAddHandler"]);


//[ex2-95] Упростить меню в адмистративном разделе для контент-менеджера
$eventManager->addEventHandler("main", "OnBuildGlobalMenu", ["Ex2\Handlers\ContentMenu", "onBuildGlobalMenuHandler"]);