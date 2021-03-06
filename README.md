# PPE 1820 - API REST

- [PPE 1820 - API REST](#ppe-1820---api-rest)
  - [Introduction](#introduction)
  - [Links](#links)
  - [Description du projet](#description-du-projet)
  - [Realisation technique](#realisation-technique)
  - [Les tests](#les-tests)
  - [Documentation](#documentation)
  - [Languages](#languages)
  - [Conclusion](#conclusion)
  - [Auteur](#auteur)

## Introduction
Dans le cadre de ma  3 ième année de BTS Services Informatiques aux Organisations. Il nous a été demander de réaliser une
API REST. Nous avons crée une API REST, permettant la communication entre [Application Mobile](https://github.com/Grezor/App_KOTLIN_2020) et la base de données du site ecommerce et du mobile.

## Links
<!-- Site Ecommerce -->
- [ChopTaPhoto_2020](https://github.com/Grezor/ChopTaPhoto_2020)
<!-- Application Android -->
- [Application Mobile](https://github.com/Grezor/App_KOTLIN_2020)

## Description du projet
Le projet est en liaison avec mon site Ecommerce, je vous explique en petit format comment fonctionne le projet

```
[client] - souhaite acheter une borne photo pour un mariage
[Employé] - Detecte qu'une personne souhaite la borne "EventMariage" pour un weekend
[client] - reçoit bien la commande, avec le matériel nécessaire
[client] - puis le client fait des photos, tout le weekend
 ... une fois le weekend passé, le client décide de voir les photos
[client] - il doit installer l'application sur son téléphone. 
[client] -  le client insère son nom d'événement dans l'application
[client] - il y a la liste des photos, prise par l'événement.
[client] - peut liker et dislike les photos
```

## Realisation technique

  ## Les tests

  - Vérification des requetes
  - Vérification des affichages (GET, POST)
  - Vérifications des insert, et update

Pour vérifier le fonctionnement de l'API, j'ai utilisé un fichier qui **requests.http**

Pour cela j'ai utilisé l'extension de Visual Studio code 
  - [Rest Client](https://marketplace.visualstudio.com/items?itemName=humao.rest-client)
  


## Documentation
  - [HTTP status](https://developer.mozilla.org/fr/docs/Web/HTTP/Status)
  - [Documentation PHP](https://www.php.net/manual/fr/)
  - [logiciel test API](https://insomnia.rest/)
  - [Images API]()
  - [Voir l'api](http://duplessigeoffrey.fr/api2/photos.php?code=EFFICOM)
  - [Information Base de donnée](https://github.com/Grezor/PPE_1820_API/blob/master/documentations/database.md)


## Languages 
- PHP

## Conclusion
Tout d'abord ce projet m'a permis d'appliquer mes connaissance que j'ai pu acquerir durant mes année d'étude, telles que la conception de la base de donnée, mais aussi le langage PHP.

## Auteur
- **Duplessi Geoffrey** 