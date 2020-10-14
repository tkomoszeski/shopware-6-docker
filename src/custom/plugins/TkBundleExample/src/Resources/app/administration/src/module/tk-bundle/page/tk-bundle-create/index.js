//TODO Make a new component tk-bundle-create which extends tk-bundle-detail
//TODO Make a 'getBundle' method which create a new object instead of fetching one
//TODO Make a 'onClickSave' method which saves your new object and after that uses 'this.$router.push' to redirect to your detail page
//TODO Add catch to your chain to create an error notification.

const { Component } = Shopware;

Component.extend('tk-bundle-create', 'tk-bundle-detail', {
    methods: {
        getBundle() {
            this.bundle = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.bundle, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({ name: 'tk.bundle.detail', params: { id: this.bundle.id } });
                }).catch((exception) => {
                    this.isLoading = false;

                    this.createNotificationError({
                        title: this.$t('tk-bundle.detail.errorTitle'),
                        message: exception
                    });
                });
        }
    }
});
