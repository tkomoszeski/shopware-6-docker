//import snippet files as deDE and enGB
//get the module factory as a partial allocation from the globally availalbe Shopware Object

//Register a new module with the name tk-bundle *(your bundle module)

//File the following fields of the Module Properites
/*
* type
* name
* title
* description
* color
* icon
* snippet
* navigation entry
* routes create a dummy route, otherwise the module is not shown in menu Example
*
* routes: {
*  index: {
*   component: 'dummy',
*   path: 'index'
* },
* */

import deDE from  './snippet/de-DE';
import enGB from  './snippet/en-GB';

const { Module } = Shopware;

Module.register('tk-bundle', {
    type: 'plugin',
    name: 'bundle',
    title: 'tk-bundle.general.mainMenuItemGeneral',
    description: 'tk-bundle.general.descriptionTextModule',
    color: '#ffd435',
    icon: 'default-shopping-paper-bag-product',


    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
      a: {component: 'b'}
    },

    navigation: [{
        label: 'tk-bundle.general.mainMenuItemGeneral',
        color: '#FFD435',
        path: 'tk.bundle.index',
        icon: 'default-shopping-paper-bag',
        position: 100
    }]
});