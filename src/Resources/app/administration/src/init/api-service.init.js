import CrehlerPayuApiService
    from '../core/service/api/crehler.payu.api.service';

const { Application } = Shopware;

Application.addServiceProvider('CrehlerPayuApiService', (container) => {
    const initContainer = Application.getContainer('init');

    return new CrehlerPayuApiService(initContainer.httpClient, container.loginService);
});
