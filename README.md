# shopware-6-docker
shopware-6-docker

## Setup shopware container

Before you run this command read please:
[Performance tweaks](https://dockware.io/docs#performance-tweaks)

```code
docker-compose up -d
```

## Remove shopware container
```code
docker-compose rm
```

## Login to shopware docker container:
```code
docker exec -it shopware /bin/bash
```

## Shopware Admin

```code
User: admin
Password: shopware
```

## Mysql credentials
```code
User: root
Password: root
Port: 3306
```

## SFTP credentials
```code
User: dockware
Password: dockware
Port: 22
```

##Mailcatcher
```code
Host: localhost
Port: 1025
```

## Best practices and etc, for shopware plugins
[Read me](https://symfony.com/doc/current/bundles/best_practices.html#composer-metadata)