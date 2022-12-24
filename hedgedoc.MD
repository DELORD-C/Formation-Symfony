# Symfony

# Lien utiles

Ahaslides
> https://ahaslides.com/fdj
> https://ahaslides.com/symfony

Teams
> https://teams.microsoft.com/l/meetup-join/19:meeting_MmEwYzQ0MmQtYjQyYi00ZmU2LTlmNjktYmRkNjY0YTg2ZGI1@thread.v2/0?context=%7B%22Tid%22:%224b9ebca5-d704-46a3-843c-255c5a7240e4%22,%22Oid%22:%22c8da740c-6352-40d1-87ad-ba392d02acb7%22%7D

Repo Github
> https://github.com/DELORD-C/symfony

Git
> https://github.com/git-for-windows/git/releases/download/v2.39.0.windows.1/Git-2.39.0-64-bit.exe
```shell=
git clone https://github.com/DELORD-C/symfony.git
```

Doc symfony
> https://symfony.com/doc/current/index.html
> https://symfony.com/doc/6.0/the-fast-track/fr/index.html

Doc Easy Admin
> https://symfony.com/doc/current/the-fast-track/en/9-backend.html

## Le framework Symfony

### Model MVC (Model Vue Controller)
Rêquete http => controller => model => vue

### Centrage HTTP
La requête http va régir le fonctionnement du framework

### Versions
> https://symfony.com/releases

### Arborescence

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

#### Le fichier .env

Il contient la configuration de notre applicatio symfony, par exemple l'url de connexion à la base de donnée.



## Prérequis

### Binaire symfony
> https://github.com/symfony-cli/symfony-cli/releases/download/v5.4.19/symfony-cli_windows_amd64.zip

- Télécharger l'utilitaire
- Décompresser l'archive
- Déplacer symfony.exe dans un dossier symfony à la racine de C:
- Ajouter le dossier au Path (variable d'environnement)
- Verification du fonctionnement
```shell=
symfony -v
symfony check:requirements
```

### php v8.1
> https://windows.php.net/downloads/releases/php-8.1.13-nts-Win32-vs16-x64.zip

- Télécharger l'utilitaire
- Décompresser l'archive
- Déplacer le dossier php dans un dossier à la racine de C: ou dans programme files
- Ajouter le dossier au Path (variable d'environnement)
- Verification du fonctionnement
```shell=
php -v
```

##### php.ini
Dans le fichier php.ini de notre installation php.ini

activer les extensions :
```ini=
extension=bz2
extension=curl
extension=ffi
extension=ftp
extension=fileinfo
extension=gd
extension=gettext
extension=gmp
extension=intl
extension=imap
extension=ldap
extension=mbstring
extension=openssl
extension=soap
extension=sockets
extension=sodium

extension=mysqli
extension=pdo_mysql

extension=pdo_sqlite
extension=sqlite3
```

changer la variable realpath_cache_size :
```ini=
realpath_cache_size = 5M
```

#### composer
> https://getcomposer.org/Composer-Setup.exe

#### Moteur de base de donnée
Vous pouvez utiliser n'importe quelle base de donnée sql

#### npm
Sur windows, npm est compris dans nodejs
> https://nodejs.org/dist/v18.12.1/node-v18.12.1-x64.msi

## Installation du projet

Pour éviter les conflit de version php, créer un fichier .php-version contenant la version de votre php (8.1.13) puis lancer la commande
```shell=
symfony new my_project_directory --version="6.2.*" --webapp
```

Modifier le fichier .env pour changer l'url de connexion à la database puis créer la database avec la commande :

> si vous utilisez sqlite, créer le fichier et pointez sur celui-ci dans l'url de connexion

```shell=
php bin/console doctrine:database:create
```

# Commandes utiles

Stopper un script :
`Ctrl` + `C`

Lancement du serveur avec le binaire symfony
```shell=
symfony serve
symfony server:start
```
Arrêt du serveur avec le binaire symfony
```shell=
symfony server:stop
```

Lister toutes les routes
```shell=
php bin/console debug:router
```

Installer webpack
```shell=
composer require symfony/webpack-encore-bundle
npm install
npm install bootstrap
npm install bootstrap-icons
```

## Database

Créer la database
```shell=
php bin/console doctrin:database:create
php bin/console d:d:c
```

Créer une migration en fonction des entités
```shell=
php bin/console make:migration
```

Appliquer les migrations non appliquées
```shell=
php bin/console doctrine:migrations:migrate
php bin/console d:m:m
```

# Controllers

Les méthode de controller doivent TOUJOURS retourner un objet Response (httpFoundation)

# Webpack encore

Pour gérer les dépendance front, symfony utilise webpack encore, il faut donc l'installer, puis configurer le point d'entrée (on peut en ajouter des nouveau dans webpack.config)

Il suffit ensuite de compiler les assets
```shell=
npm run dev
npm run watch //sert à recompiler à chaque modification des assets
```

Il suffit ensuite d'ajouter les feuilles de style et script à nos templates via les fonctions twig
```twig=
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Symfony - {% block title %}App{% endblock %}</title>
        {{ encore_entry_link_tags('app') }}
    </head>
    <body>
        {% include 'parts/_nav.html.twig' %}
        {% block body %}{% endblock %}
        {{ encore_entry_script_tags('app') }}
    </body>
</html>
```

## Bootstrap

Pour utiliser bootstrap il suffit de l'installer
```shell=
npm install bootstrap
```

puis de l'ajouter à notre point d'entrée
```jsx=
import 'bootstrap/dist/css/bootstrap.css';
```

Pour utiliser le scss (sass) il faut installer sass et sass-loader
```shell=
npm install sass sass-loader
```

Puis l'activer dans webpack.config.js
```jsx=
    .enableSassLoader()
```

> Pour écrire le caractère tild "~" il suffit de faire `alt gr` + `2`

# Traductions

> https://symfony.com/doc/current/translation.html

Extraire toutes les traductions système :
```shell=
php bin/console translation:extract --force fr --format=yaml
```

# Cache Symfony

Il existe deux grands types de cache http dans symfony

## Cache d'expiration

Consiste à donner une durée de vie au cache

## Cache de validation

Consiste à vérifier la validité du cache avant de renvoyer ou générer la page.

# Méthodologie post création d'application

![](https://hedgedoc.dawan.fr/uploads/upload_feafe09ae34d89c1e668ca3d10c3b245.png)

![](https://hedgedoc.dawan.fr/uploads/upload_3bf1cef2a69bba2e9841627fc87c6921.png)



# Dupliquer un projet git

1) Create a new repo in github using UI/command as usual. This will be the target repo where duplicate of source repo to be pasted.

2) Open a command prompt and Clone the source/existing repo using the command:

git clone --bare https://sourceRepoURL

A new folder will be created containing some files from source repo.

3) Move to that folder:
   cd sourceRepoFolder

4) Push these files into the target repo using command:
   git push --mirror https://targetRepoURL

# Exercices

## 1

Arriver au résultats suivants :

Si j'appelle la route : /random/`5`
j'obtient un nombre entre `5` et 1000

Si j'appelle la route /random/`0`/`9`
j'obtient un nombre netre `0` et `9`

Si j'appelle la route /random
j'obtient un nombre entre 0 et 1000

Si j'appelle la route /random/`0`/inf
j'obtient un nombre entre `0` et 999999999999999

Si j'appelle la route /random/default/800
j'obtient un nombre entre 0 et `800`


## 2

Ajouter bootstrap au point d'entrée app afin que celui-ci soit relié sur toutes nos pages


![](https://hedgedoc.dawan.fr/uploads/upload_42725c1ddb2bd432114994a2a4323c34.png)


## 3
Créer un formulaire de création de compte (Register)

- Créer un UserController
- Créer un Form/UserType => formulaire
- Créer un template qui affiche le formulaire

Bonus : Ajouter un champ "répéter le mot de passe"

## 4
- Créer un CRUD complet pour les utilisateurs
- Faire apparaitre dans la liste des user, une colonne posts qui affichera le nombre de post de chaque utilisateur

## 5

Modifier votre application pour arriver aux résultats suivants :

<!-- Doctrine:
- Entité User
    - username
    - phone
    - address
    - city
    - zip
    - country
    - refere (un autre user, supérieur) < le faire en dernier
    
- Entité Comment
    - post (Entité Post)
    - user (Entité User)
    - content
    - createdAt
    - likes -->

Fonctionnement :
- On doit pouvoir laisser un commentaire sur un post et voir les commentaires sur un post
- On doit pouvoir "liker" un commentaire qu'une seule fois et "unliker" un commentaire qu'on avait déjà liké
- Sur la page d'affichage d'un post, le nombre de j'aime doit apparaitre sur chaque commentaire
- Dans la liste des post, le nombre de commentaires doit appraitre sur chaque post

## 6

Mettre en place une messagerie, avec la liste des messages d'un utilisateur, et la page pour envoyer un nouveau message

Message
- sender
- target
- subject
- body
- createdAt
- state de type boolean


Etapes :

- Créer l'entité avec ses potentielles relations
- Créer le form type
- Créer le controlleur
- Créer les templates

Utiliser php bin/console make:crud pour générer le controller et les pages et gagner du temps

Bonus:
- Sur le lien dans la navbar d'un utilisateur connecté apparaitra une pastille rouge de "notification" lorsqu'il a des messages non lus
- Ajouter la possibilité d'envoyer le message à plusieurs personnes en même temps

## 7

Ajouter à la page app_message_list la liste des messages envoyés par l'utilisateur connecté

Ajouter un bouton répondre et répondre à tous sur la page app_message_read

Créer un Voter pour les messages et appliquer une politique gràce à celui-ci.

## 8

1) traduire tout le site en français

2) pluraliser les titres des listes des messages

3) ajouter la possibilité de passer en langage Espagnol