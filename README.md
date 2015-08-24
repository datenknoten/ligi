# Ligi

Ligi is the esperanto word for link or connection. This application aims to connect people who want to give away stuff for free and without conditions.

## Chat

There is a chat on [webchat.datenknoten.me](https://webchat.datenknoten.me/) which can also accessed via XMPP: [server@conference.boese-ban.de](xmpp:server@conference.boese-ban.de)

## Install

* Clone [this Repository](https://github.com/datenknoten/ligi) somewhere.
* If not already, [install composer](https://getcomposer.org/).
* Install all the dependencies via `composer install`.
* Install the database via `php app/console doctrine:schema:update --force`
* Dump the assets: `php app/console assetic:dump --env=prod --no-debug`.
* [Configure](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) your webserver.

## Translation

Translation files are inside `src/Datenknoten/LigiBundle/Resources/translations` and can be either edited by hand or by using [transolution](https://bitbucket.org/fredrik_corneliusson/transolution/).