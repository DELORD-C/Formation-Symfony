# Symfony

# Lien utiles

Ahaslides
> https://ahaslides.com/FDJ
> https://ahaslides.com/SYMFONY2

Moncompte Dawan
> https://moncompte.dawan.fr

Documentation Symfony
> https://symfony.com/doc/current/index.html

Lien conférence
> https://bbb.dawan.fr/b/cle-hci-tyj-dqt

Télécharger WAMP (PHP 8, Mysql)
> https://sourceforge.net/projects/wampserver/files/WampServer%203/WampServer%203.0.0/wampserver3.2.6_x64.exe/download

Composer
> https://getcomposer.org/download/

Client Symfony
> https://symfony.com/download

Git
> https://git-scm.com/downloads

Repo Github
> https://github.com/DELORD-C/symfony

NodeJs
> https://nodejs.org/en/download/

# Prerequis

- WAMP/XAMP/LAMP
- Composer
- php.ini exetnsions et variables (voir doc)
- npm
- git

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

# Installer un projet local symfony
- WAMP/XAMP/LAMP (php8.1, mysql)
- Composer
- php.ini extensions et variables (voir doc)
> https://symfony.com/doc/current/setup.html#technical-requirements
- npm
- git
- telecharger le binaire symfony
> https://github.com/symfony-cli/symfony-cli/releases/download/v5.4.19/symfony-cli_windows_amd64.zip

- Ajouter le binaire symfony et php8.1 au PATH (variable d'environnement)
```php-template=
symfony new my_project_directory --version="6.1.*" --webapp
composer install
symfony serve
composer require symfony/webpack-encore-bundle
npm install
npm run dev (ou watch)
```

# NPM C'EST POUR LES DEPENDANCES JS ET CSS

# Cloner un projet symfony

```shell=
git clone [repo]
```

Modifier le fichier .env ou créer un .env.local avec vos paramètres puis :

```shell=
composer install
npm install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
npm run watch
```

# Exercices

## 1

Ajouter une colonne "Created At" à l'affichage d'un Post et afficher la donnée correspondante

La date doit s'afficher au format : Month, dd yyyy
ex : September, 09 2022


## 2
Ajouter un bouton Delete à la liste des posts, celui-ci doit envoyer vers une url formattée de la mèmem manière que pour la méthode show, mais le post doit être supprimé

## 3

Transformer la méthode edit pour qu'elle utilise un template twig de la même manière que les autres méthodes.

## 4

Ajouter bootstrap et bootstrap icons dans webpack et le rendre disponible sur toutes les pages

https://getbootstrap.com/

## 5

La table de post list doit être mise en forme avec bootstrap

Ajouter à votre application une navbar sur toutes les pages, celle-ci doit être stylisée avec bootstrap : https://getbootstrap.com/docs/5.2/components/navbar/#nav
Elle doit contenir un lien pour chaque page de notre app, les liens des pages post (list et create) doivent être dans un dropdown.

Bonus : la page active doit être mise ne avant dans la navbar

## 6
Ajouter la possibilité de créer un commentaire sur le page "show" d'un post

Ajouter une colonne avec le nombre de commentaire dans la liste des posts

## 7

Ajouter des messages de succès pour les situations:
- Post
    - create
    - delete
    - edit
- Comment
    - create

## 8

Créer une entité User qui aura pour champs
- firstname -> string
- lastname -> string
- email -> string
- phone -> string
- address -> string
- city -> string
- zip -> string/int
- password -> string
- createdAt -> DateTimeImmutable
- updatedAt -> DateTimeImmutable
- status -> boolean


Avoir les relations suivantes
- User -> ManyToOne -> User
- Post -> ManyToOne -> User
- Comment -> ManyToOne -> User


Créer un crud (create, read, update, delete) pour cette entité

> Lors de la création d'un utilisateur, un champ select doit nous permettre de selectionner un autre utilisateur existant comme référent de celui-ci





## 9

JSON / Ajax



## 10

Rediriger l'utilisateur après connexion sur post/list

## 11

Afficher les bouton login/logout que lorsque nécessaire

Empecher un utilisateur connecté d'accéder au formulaire login

# 12

Ajouter la possibilité d'ajouter ou retirer le rôle admin sur les utilisateurs

bonus : (pas sur soit même)


# 13

Appliquer les règles suivantes :

- Un utilisateur peux modifier ses propres informations (sauf son rôle et son status)
- Un admin peut ajouter, supprimer, modifier tout utilisateur
- Il faut être connecté pour poster un commentaire

Bonus donner la possibilité aux admin de modifier le role des autres utilisateurs soif a soit lui même (sur la même page edit)

# 14

Ajouter un language switcher dans la navbar qui permet de changer de langue (de manière permanente)

https://github.com/lipis/flag-icons

# 15

Téléchargez l'image :
https://www.dawan.fr/build/images/dawan-logo.5b6f94e2.png

Placez là dans votre répertoir (à vous de trouver où) (PAS LE DROIT DE LA METTRE DANS PUBLIC)

Afficher l'image

Trouver les 3 méthodes qui permettent d'ajouter une ou plusieurs images aux assets webpack d'un entrypoint