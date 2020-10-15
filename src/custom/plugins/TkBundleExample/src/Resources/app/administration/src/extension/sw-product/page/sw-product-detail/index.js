//ToDo Override the 'sw-product-detail'
//ToDo override the computed property 'product Criteria' and add the bundles

const { Component } = Shopware;

Component.override('sw-product-detail', {
    computed: {
        productCriteria() {
            const criteria = this.$super('productCriteria'); //get product criteria from parent
            criteria.addAssociation('bundles');

            return criteria;
        },
    }

});
