<?php
// src/controllers/MenuFactory.php

require_once "./src/views//containers/Menu.php";
require_once "./src/views//containers/MenuItem.php";
require_once "./src/views//containers/SplitMenuItem.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";

class MenuFactory
{
    use tErrorMessageCollector;

    public function createMenu(array $menu_items, bool $isLoggedIn, string $class = 'nav'): Menu
    {
        return $this->buildMenu($menu_items, $isLoggedIn, $class);
    }

    protected function buildMenu(array $menu_items, bool $isLoggedIn, string $class = 'nav'): Menu
    {
        $menu = new Menu($class);

        // login/auth checks
        foreach ($menu_items as $item) {
            try {
                $menu->addElement($this->buildMenuItem($item, $isLoggedIn));
            } catch (\InvalidArgumentException $e) {
                $this->logError($e->getMessage());
            }
        }
        return $menu;
    }

    protected function buildMenuItem(array $item, bool $isLoggedIn, string $link_class = 'nav-link', string $li_class = 'nav-item')
    {
        if (empty($item['label']) || empty($item['href'])) {
            $this->logError("Menu item missing required 'label' or 'href': ");
        }

        if (!empty($item['submenu'])) {
            $menuItem = new MenuItem(
                $item['label'],
                $item['href'],
                $link_class . ' dropdown-toggle',
                [
                    'role' => 'button',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                ],
                $li_class . ' dropdown'
            );

            $submenu = new Menu('dropdown-menu');
            foreach ($item['submenu'] as $subitem) {
                try {
                    $submenu->addElement($this->buildMenuItem($subitem, $isLoggedIn, 'dropdown-item', ''));
                } catch (\InvalidArgumentException $e) {
                    $this->logError($e->getMessage());
                }
            }
            $menuItem->addElement($submenu);
        } else {
            $menuItem = new MenuItem($item['label'], $item['href'], $link_class, [], $li_class);
        }

        return $menuItem;
    }
}
