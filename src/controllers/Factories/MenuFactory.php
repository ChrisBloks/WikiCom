<?php
// src/controllers/MenuFactory.php

require_once "./src/views//containers/Menu.php";
require_once "./src/views//containers/MenuItem.php";
require_once "./src/views//containers/SplitMenuItem.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";

class MenuFactory
{
    use tErrorMessageCollector;

    public function createMenu(array $menu_items, string $class = 'nav'): Menu
    {
        return $this->buildMenu($menu_items, $class);
    }

    protected function buildMenu(array $menu_items, string $class = 'nav'): Menu
    {
        $menu = new Menu($class);

        // login/auth checks
        foreach ($menu_items as $item) {
            try {
                $menu->addElement($this->buildMenuItem($item));
            } catch (\InvalidArgumentException $e) {
                $this->logError($e->getMessage());
            }
        }
        return $menu;
    }

    protected function buildMenuItem(array $item, string $link_class = 'nav-link', string $li_class = 'nav-item')
    {
        if (empty($item['label']) || empty($item['href'])) {
            $this->logError("Menu item missing required 'label' or 'href': ");
        }

        if (!empty($item['submenu'])) {
            $menuItem = new MenuItem(
                label: $item['label'],
                href: $item['href'],
                class: $link_class . ' dropdown-toggle',
                attrs: [
                        'role' => 'button',
                        'data-bs-toggle' => 'dropdown',
                        'aria-expanded' => 'false',
                    ],
                li_class: $li_class . ' dropdown'
            );

            $submenu = new Menu(class: 'dropdown-menu');
            foreach ($item['submenu'] as $subitem) {
                try {
                    $submenu->addElement($this->buildMenuItem($subitem, 'dropdown-item', ''));
                } catch (\InvalidArgumentException $e) {
                    $this->logError($e->getMessage());
                }
            }
            $menuItem->addElement($submenu);
        } else {
            $menuItem = new MenuItem(
                                label: $item['label'], 
                                href: $item['href'], 
                                class: $link_class, 
                                attrs: [], 
                                li_class: $li_class);
        }

        return $menuItem;
    }
}
