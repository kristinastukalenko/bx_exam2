<?php
namespace Ex2\Handlers;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Localization\Loc;
Loc::LoadMessages(__FILE__);

class ContentMenu
{
    public static function onBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        // Если пользователь принадлежит группе "Контент-редакторы" (5) и не принадлежит группе "Администраторы" (1)
        // тогда, изменяем структуру меню
        if (in_array(5, $USER->GetUserGroupArray()) && !in_array(1, $USER->GetUserGroupArray())) {
            // Пункт меню "Рабочий стол"
            unset($aGlobalMenu['global_menu_desktop']);

            // Убираем подпункты, если есть следующие родительские пункты меню
            foreach ($aModuleMenu as $iKey => $sValue) {
                /* Можно удалить лишние пункты
                if ($aModuleMenu[$iKey]['parent_menu'] == 'global_menu_settings' // Настройки
                    || $aModuleMenu[$iKey]['parent_menu'] == 'global_menu_services' // Сервисы
                    || $aModuleMenu[$iKey]['parent_menu'] == 'global_menu_marketplace' // Маркетплейс
                    || $aModuleMenu[$iKey]['items_id'] == 'menu_iblock' // Инфоблоки (Импорт, экспорт и т.п.)
                ) {
                    unset($aModuleMenu[$iKey]);
                }
                */

                // Либо оставить только нужный
                if ($aModuleMenu[$iKey]['items_id'] != 'menu_iblock_/news') {
                    unset($aModuleMenu[$iKey]);
                }
            }
        }
    }
}