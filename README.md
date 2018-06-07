Extranet
========

Site réalisé avec CodeIgniter (https://www.codeigniter.com/).

Ce site nécessite une version de PHP supérieure ou égale à 7.1.

## Mise en route

Après avoir cloné le dépôt, il faudra commencer par installer les dépendences,
avec un simple coup de `composer install`.

Il faudra également mettre à jour les informations de la base de donnée, en
éditant le fichier `application/config/database.php`.

Une fois cela fait, il ne restera plus qu'à copier le fichier
`config.example.yml` en `config.yml`, et de compléter le fichier avec les
domaines supportés et les clés d'API.

Pour lancer le site en local, un simple coup de `make serve` depuis un système
linux lancera un serveur de développement sur http://localhost:8008.

Lors du déploiement du site, il faudra configurer nginx ou Apache pour servir
le dossier `public` uniquement.

## Formattage du code

Pour avoir un code cohérent, il est possible d'utiliser un formatteur de code.

Pour l'utiliser, il faut d'abord dans un premier temps l'installer, avec
la commande `npm install`.

Puis si vous êtes sur un linux, il est possible d'utiliser la commande
`make format` qui se chargera de formater l'ensemble du code PHP de
l'application.
