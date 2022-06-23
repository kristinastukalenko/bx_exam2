<?php
$eventManager = \Bitrix\Main\EventManager::getInstance();

// [ex2-50] Проверка при деактивации товара
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', array('Ex2\Handlers\ProductDeactivation', 'OnBeforeIBlockElementUpdateHandler'));