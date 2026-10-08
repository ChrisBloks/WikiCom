<?php
// src/controllers/MenuFactory.php

namespace Wiki\controllers\factories;

use Wiki\tools\traits\tErrorMessageCollector,
Wiki\tools\utils\HtmlUtils,
Wiki\views\containers\Menu,
Wiki\views\containers\Menuitem;

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
        if (empty($item['label']) || empty($item['page_value'])) {
            $this->logError("Menu item missing required 'label' or 'page_value': ");
        }
        if (!empty($item['submenu'])) {

            $menuItem = new MenuItem(
                label: $item['label'],
                page_value: $item['page_value'],
                class: $link_class . ' dropdown-toggle',
                attrs: [
                    'role' => 'button',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                    'data-user-id' => $item['id'] ?? -1,
                    // 'data-target-page' => $item['page_value']
                ],
                li_class: $li_class . ' dropdown',

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
                page_value: $item['page_value'],
                class: $link_class,
                attrs: [
                    'data-user-id' => $item['id'] ?? -1,
                    'data-target-page' => 'no'//$item['page_value']
                ],
                li_class: $li_class
            );
        }
        return $menuItem;
    }
}
