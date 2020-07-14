<?php declare(strict_types=1);

/**
 * Basic symfony event subscriber
 * https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber
 * https://academy.shopware.com/courses/take/shopware-6-developer-training-english/lessons/9225161-event-subscribers
 */

namespace FirstPluginForShopware\Storefront\Subscriber;

use FirstPluginForShopware\Core\Content\FirstPlugin\FirstPluginCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface {

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * @var EntityRepositoryInterface
     */
    private $firstPluginRepository;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $firstPluginRepository
    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->firstPluginRepository = $firstPluginRepository;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            FooterPageletLoadedEvent::class => 'onFooterPageletLoaded'
        ];
    }

    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event): void {
        if(!$this->systemConfigService->get("FirsPluginForShopware.config.showInStorefront")) {
            return;
        }

        $shops = $this->fetchShops($event->getContext());

        $event->getPagelet()->addExtension("first_plugin",$shops);
    }

    private function fetchShops(\Shopware\Core\Framework\Context $context) : FirstPluginCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addFilter(new EqualsFilter('active','1'));
        $criteria->setLimit(5);

        /**
         * @var FirstPluginCollection $firstPluginCollection
         */
        $firstPluginCollection = $this->firstPluginRepository->search($criteria,$context)->getEntities();

        return $firstPluginCollection;
    }
}