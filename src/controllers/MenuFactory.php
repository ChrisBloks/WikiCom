<?php
// src/controllers/MenuFactory.php

require_once "./src/views//containers/Menu.php";
require_once "./src/views//containers/MenuItem.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";

class MenuFactory
{
    use tErrorMessageCollector;

    public function createMenu(array $menu_items, bool $isLoggedIn): Menu
    {
        return $this->buildMenu($menu_items, $isLoggedIn);
    }

    private function buildMenu(array $menu_items, bool $isLoggedIn): Menu
    {
        $menu = new Menu();

        // login/auth checks
        foreach ($menu_items as $item) {
            try {
                if (!empty($item['guest_only']) && $isLoggedIn) {
                    continue;
                }
                if (!empty($item['auth_only']) && !$isLoggedIn) {
                    continue;
                }

                $menu->addElement($this->buildMenuItem($item, $isLoggedIn));
            } catch (\InvalidArgumentException $e) {
                $this->logError($e->getMessage());
            }
        }

        return $menu;
    }

    private function buildMenuItem(array $item, bool $isLoggedIn)
    {
        if (empty($item['label']) || empty($item['href'])) {
            throw new \InvalidArgumentException(
                "Menu item missing required 'label' or 'href': " . json_encode($item)
            );
        }

        $menuItem = new MenuItem($item['label'], $item['href']);

        if (!empty($item['submenu'])) {
            $menuItem->addElement($this->buildMenu($item['submenu'], $isLoggedIn));
        }

        return $menuItem;
    }
}
