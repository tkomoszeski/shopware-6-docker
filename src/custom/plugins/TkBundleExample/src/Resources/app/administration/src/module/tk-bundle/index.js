//import snippet files as deDE and enGB
//get the module factory as a partial allocation from the globally availalbe Shopware Object
//import detail and create component
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
*
* //Tutorial v2 Add the index/listing route inside navigation and add listing route as well in routing
* */

import './page/tk-bundle-list';
import './page/tk-bundle-create';
import './page/tk-bundle-detail';

import deDE from  './snippet/de-DE';
import enGB from  './snippet/en-GB';

const { Module } = Shopware;

Module.register('tk-bundle', {
    type: 'plugin',
    name: 'Bundle',
    title: 'tk-bundle.general.mainMenuItemGeneral',
    description: 'tk-bundle.general.descriptionTextModule',
    color: '#ffd435',
    icon: 'default-shopping-paper-bag-product',


    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    // add the index/listing route
    routes: {
        list: {
            component: 'tk-bundle-list',
            path: 'list'
        },
        //add route for detail view & create view and don't forget the meta.parentPath object
        detail: {
            component: 'tk-bundle-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'tk.bundle.list'
            }
        },
        create: {
            component: 'tk-bundle-create',
            path: 'create',
            meta: {
                parentPath: 'tk.bundle.list'
            }
        }

    },


    //add here navigation listing component
    navigation: [{
        label: 'tk-bundle.general.mainMenuItemGeneral',
        color: '#FFD435',
        path: 'tk.bundle.list',
        icon: 'default-shopping-paper-bag',
        position: 100
    }]
});