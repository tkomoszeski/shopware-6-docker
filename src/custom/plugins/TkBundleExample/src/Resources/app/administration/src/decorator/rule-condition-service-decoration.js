import '../core/component/tk-cart-contains-bundle';


const { Application } = Shopware;

Application.addServiceProviderDecorator('ruleConditionDataProviderService', (ruleConditionService) => {
    ruleConditionService.addCondition('tkBundleContainsBundle', {
        component: 'tk-cart-contains-bundle',
        label: 'tk-condition.condition.cartContainsBundle.label',
        scopes: ['cart']
    });

    return ruleConditionService;
});
