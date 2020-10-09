CREATE TABLE `tk_bundle` (
    `id` BINARY(16) NOT NULL,
    `discount_type` VARCHAR(255) NOT NULL,
    `discount` DOUBLE NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `json.tk_bundle.translated` CHECK (JSON_VALID(`translated`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tk_bundle_translation` (
    `name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `tk_bundle_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`tk_bundle_id`,`language_id`),
    KEY `fk.tk_bundle_translation.tk_bundle_id` (`tk_bundle_id`),
    KEY `fk.tk_bundle_translation.language_id` (`language_id`),
    CONSTRAINT `fk.tk_bundle_translation.tk_bundle_id` FOREIGN KEY (`tk_bundle_id`) REFERENCES `tk_bundle` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.tk_bundle_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tk_bundle_product` (
    `bundle_id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `product_version_id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    PRIMARY KEY (`bundle_id`,`product_id`,`product_version_id`),
    KEY `fk.tk_bundle_product.bundle_id` (`bundle_id`),
    KEY `fk.tk_bundle_product.product_id` (`product_id`,`product_version_id`),
    CONSTRAINT `fk.tk_bundle_product.bundle_id` FOREIGN KEY (`bundle_id`) REFERENCES `tk_bundle` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.tk_bundle_product.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;