/**
 * TODO
 * 1. Do a named import template from the tk-bundle-list.html.twig (your template.twig ) this points to our twig file
 * 2. Get the Component object be destructuring the global Shopware Object - we need component object to register the component
 * 3. Get the Critera object by destructuring the global Shopware.Data Object
 * 4. Register new Component with the name tk-bundle list (your bundle list )
 * 5. Pass te template to the vue component
 * 6. Inject the repositoryFactory
 * 7. Create 2 data attributes: repository default null, bundles default null
 * 8. Create the metainfo
 * 9. Create a computed property named columns containing a method call which calls all the columns you want to display to listing
 * 10. Use the vue created lifecycle method to call a method, which fetches all bundles
 * 11. Create a methods property containing 'createdComponents' and 'getColumns'
 */

import template from './tk-bundle-list.html.twig'; //ad.1

const { Component } = Shopware; //ad.2
const { Criteria } = Shopware.Data; //ad.3

Component.register('tk-bundle-list',{
   template,

   inject: [
       'repositoryFactory' //this is from bottlejs
   ],

    data() { //creating variables in data vue js knows about those properties before even starts executing something
        return {
            repository: null,
            bundles: null
        };
    },

    metaInfo() {
       return {
           title: this.$createTitle() //TODO use default administration method createTitle to fetch title
       };
    },

    computed: { //TODO it's used for data can change
        columns() { //TODO we can change this name and use different one
            return this.getColumns();
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
               this.repository = this.repositoryFactory.create('tk_bundle'); //geting repository from bundles

               this.repository.search(new Criteria(), Shopware.Context.api).then((result) => {
                   this.bundles = result;
               });
        },

        getColumns() {
           return [
               {
                   property: 'name',
                   label: this.$t('tk-bundle.list.columnName'),
                   routerLink: 'tk.bundle.detail',
                   inlineEdit: 'string',
                   allowResize: true,
                   primary: true
                },
                {
                   property: 'discount',
                   label: this.$t('tk-bundle.list.columnDiscount'),
                   inlineEdit: 'number',
                   allowResize: true
                },
               {
                   property: 'discountType',
                   label: this.$t('tk-bundle.list.columnDiscountType'),
                   allowResize: true
               }

           ];
        }
    }
});