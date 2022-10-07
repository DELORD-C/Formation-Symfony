# Symfony

# Lien Utiles

Mon Compte Dawan
> https://moncompte.dawan.fr

Repo Github
> https://github.com/DELORD-C/symfony

Doc Symfony
> https://symfony.com/doc/current/index.html

Installation de Symfony
> https://symfony.com/doc/current/setup.html

Doc Twig
> https://twig.symfony.com/doc/3.x/

Réunion Teams
> https://teams.microsoft.com/l/meetup-join/19%3ameeting_Yjk1YzZkMjAtMDFjYi00NGFlLTgzOWItNWNlYTQ0NjJlZjdk%40thread.v2/0?context=%7b%22Tid%22%3a%224b9ebca5-d704-46a3-843c-255c5a7240e4%22%2c%22Oid%22%3a%22c8da740c-6352-40d1-87ad-ba392d02acb7%22%7d

Bootstrap icons
> https://icons.getbootstrap.com/

# Commandes Utiles

Lancer le serveur symfony
```shell=
symfony server:start
```

Lister les routes
```shell=
php bin/console debug:router
```

Installer un paquet composer
```shell=
composer require nom_du_paquet
```

Installer un paquet npm/yarn
```shell=
npm install nom_du_paquet
yarn install nom_du_paquet
```

Compiler et "surveiller" les modifications
```shell=
npm run watch
yarn run watch
```


# Lexique
`composer` : Gestionnaire de pacquets php
`npm` : Gestionnaire de paquets js & css (webpack)
`twig` : Moteur de template de Symfony
`Doctrine` : L'`ORM` de symfony
`ORM` : Gestionnaire de base de donnée


# Installation

## Avec le client Symfony

Vérifier les prérequis système
```shell=
symfony check:requirements (Attention, peut être pas très fiable selon les versions)
```

Créer le répertoire du projet et installer celui-ci
```shell=
symfony new my_project_directory --version="6.1.*" --webapp
```

## Avec Composer

```shell=
composer create-project symfony/skeleton:"6.1.*" my_project_directory
cd my_project_directory
composer require webapp
```

# Architecture des dossiers
```shell=
assets/ //css, js, medias
bin/ //console
config/ //configuration de l'application
migrations/ //historiques des actions de l'ORM sur la base de donnée
node_modules/ //dépendances npm
public/ //racine de notre dossier web
src/ //contient tout le code php, les controllers, entités, repertoires, etc.
templates/ //templates de pages en twig
translations/ //traductions
var/ //cache & logs
vendor/ //dépendances php
```

# Doctrine

Créer la database
```shell=
php bin/console doctrine:database:create
```

Créer / Modifier une Entité
```shell=
php bin/console make:entity
```

Créer une migration
```shell=
php bin/console make:migration
```

Effectuer une migration
```shell=
php bin/console doctrine:migration:migrate 
```


# Diagramme POST USER
![](https://hedgedoc.dawan.fr/uploads/upload_03a49f59b775de4c5ae8783061d90f09.png)

# CSRF Token
# Tags dans services.yaml (approfondir les services en général)
# Mailing
# PDF Creation
