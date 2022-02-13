# Documentation d'installation

## pré-requis
Cette application a été développé dans un environnement ``PHP 7.4`` avec ``Symfony 5.4``, ``Postgres 13`` et ``Yarn``.

## Installation sur serveur

Récupérer le projet sur Github
```shell
git clone https://github.com/wikub/Kreno.git kreno
cd kreno
```

Lancer l'installation des composants
```shell
composer install
```

Déclarer les variables de configuration en créant le fichier ``.env.local`` en vous basant sur le variable pralablement déclaré dans le fichier ``.env``

Pour les variables en production basez vous sur la [documentation Symfony : Configuring Environment Variables in Production](https://symfony.com/doc/current/configuration.html#encrypting-environment-variables-secrets)

Créer la base de données et lancer la création du schema

```shell
php bin/console doctrine:migration:migrate
```

Lancer la compilation des éléments du Front :
```shell
yarn build
```
