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

import template from './tk-bundle-detail.html.twig'; //ad.1


//Import mixin from shopware singleton
const { Component, Mixin } = Shopware; //ad.2


Component.register('tk-bundle-detail',{
   template,

   inject: [
       'repositoryFactory' //TODO this is from bottlejs
   ],

    mixins: [
      Mixin.getByName('notification')
    ],

    data() { //TODO creating variables in data vue js knows about those properties before even starts executing something
        return {
            bundle: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    //TODO Add notification mixin
    metaInfo() {
       return {
           title: this.$createTitle() //TODO use default administration method createTitle to fetch title
       };
    },

    computed: { //TODO it's used for data can change
        options() {
            return [
                {
                    value: 'absolute', name: this.$t('tk-bundle.detail.absoluteText')
                },
                {
                    value: 'percentage', name: this.$t('tk-bundle.detail.percentageText')
                }
            ];
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create('tk_bundle'); //TODO geting repository from bundles
            this.getBundle();
        },

        getBundle() {
            this.repository.get(this.$route.params.id, Shopware.Context.api).then(
                (entity) => {
                    this.bundle = entity;
                }
            );
        },

        onClickSave() {
            this.isLoading = true;

            this.repository.save(this.bundle,Shopware.Context.api).then(()=> {
                this.getBundle();
                this.isLoading = false;
                this.processSuccess = true;
            }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('tk-bundle.detail.errorTitle'),
                    message: exception
                });
            });
        },

        saveFinish() {
            this.processSuccess = false;
        }
    }
});