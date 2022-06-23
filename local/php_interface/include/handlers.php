<?php
$eventManager = \Bitrix\Main\EventManager::getInstance();

// [ex2-50] Проверка при деактивации товара
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['Ex2\Handlers\ProductDeactivation', 'OnBeforeIBlockElementUpdateHandler']);

// [ex2-93] Записывать в Журнал событий - открытие не существующих страниц сайта
// Событие вызывается в конце визуальной части эпилога сайта
$eventManager->addEventHandler("main", "OnEpilog", ["Ex2\Handlers\Check404", "onEpilogHandler"]);