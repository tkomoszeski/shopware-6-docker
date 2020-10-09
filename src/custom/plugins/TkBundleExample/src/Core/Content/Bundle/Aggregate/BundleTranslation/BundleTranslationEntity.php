<?php declare(strict_types=1);

namespace TkBundleExample\Core\Content\Bundle\Aggregate\BundleTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use TkBundleExample\Core\Content\Bundle\BundleEntity;

class BundleTranslationEntity extends TranslationEntity {

    /**
     * @var string
     */
    protected $bundleId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var BundleEntity
     */
    protected $bundle;

    /**
     * @return string
     */
    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    /**
     * @param string $bundleId
     */
    public function setBundleId(string $bundleId): void
    {
        $this->bundleId = $bundleId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return BundleEntity
     */
    public function getBundle(): BundleEntity
    {
        return $this->bundle;
    }

    /**
     * @param BundleEntity $bundle
     */
    public function setBundle(BundleEntity $bundle): void
    {
        $this->bundle = $bundle;
    }




}