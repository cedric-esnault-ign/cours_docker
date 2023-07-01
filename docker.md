---
title : Docker par la pratique
sub-title : IGN
author : Cédric Esnault
date : 04/07/2023 - IGN/ENSG
---



# Docker #

## Plan de la présentation ##

- Concepts
  - Virtualisation
  - Cloud
- Docker
  - Historique
  - Organisation
  - Installation
  - Premier conteneur
  - Première image
- Docker-compose
  - Installation
  - première Stack
  - swarm
- Kubernetes
- Concurrence?
- La suite

<aside class="notes">

</aside>

## Cédric Esnault ##

Architecte Technique à la **M** **A** **R** **S** à l'IGN

Utilisateur de Docker depuis 2014

Ce cours est librement inspiré de plusieurs sources dont celles de Thibault Coupin.

# Concepts #

## Virtualisation du système ##

 Il existe plusieurs niveaux de virtualisation en informatique, avec un but commun : partager des ressources physiques pour simplifier et accélérer la transformation. Voici plusieurs niveau de virtualisation de ressources :

* Émulation (ex: qemu-system-arm)
* Virtualisation complète (ex: Virtualbox)
* Para-virtualisation (ex: VMWare)
* Isolation applicative - conteneur (ex: ....Docker)

<aside class="notes">

</aside>

## Isolation applicative ##

- Initialement spécifique au monde Unix
- Isolation de l'espace utilisateur
- « *Partage* » du noyau
- outils de packaging de de manipulation

`Les conteneurs sont maintenant disponible sous Windows!`{.note .fragment}

<aside class="notes">

</aside>

## Isolation applicative ##{.figcenter} ##

![](img/Diagramme_ArchiIsolateur.png)

<aside class="notes">

</aside>

## C'est quoi un conteneur ? ##

* Un espace isolé pour exécuter un processus (cpu et mémoire)
* Un paquet contenant l'application et ses dépendances
* Un segment réseau dédié


## Cloud ##

La notion de Cloud tel qu'on l'entend aujourd'hui a été popularisée par *Amazon* quand ces derniers ont eu l'idée de mettre à disposition de tous leurs serveurs inutilisés pendant les périodes creuses de leur activité. Il était nécessaire de fournir des solutions de virtualisations rapide et sûre.

Petit à petit l'utilisation de conteneur pour simplifier les taches automatisées et améliorer le *TimeToMarket* s'est imposé. Aujourd'hui les plateforme de **Paas**, **Saas** et de **Faas** sont basés sur des conteneurs (pas forcément Docker car il existe des alternatives).


<aside class="notes">

</aside>

# Docker #

## Historique ##

Inventé par Solomon Hyke , Docker est une technologie qui vise à populariser l'usage des conteneurs afin de faciliter la mise en place de méthodologies Devops.

* 1979 chroot
* 2007 La technologie cgroup est intégrée au noyau Linux
* 2008 Sortie de LXC ; il s’appuie sur les espaces de nom de cgroups et Linux, comme Docker le fera par la suite
* 2013 Sortie de Docker comme logiciel open source par dotCloud Inc. qui deviendra Docker Inc.
* 2014 Docker est disponible sur Amazon EC2
* 2015 Google sort de Kubernetes
* 2018 Kubernetes est certifié Docker et devient la solution incontournable du marché

## Pourquoi Docker ##

* Simplifier les déploiements : **API**
* Changer le mode de livrables : **Images**
* Faciliter la gestion des dépendances : **l'isolation**

## C'est quoi Docker ##

Docker c'est avant tout deux notions :

Des **Conteneurs** constitués :

* 1 process (~~ou plusieurs~~)
* Isolation par rapport à l'OS hôte (cgroups/namespaces)
* Utilisant le noyau de l'hôte

Des **Image** contenants :

* Les librairies nécessaires
* Le code de l'application

Et un peu aussi des **réseaux** et des **volumes**

## Pourquoi Docker plutôt qu'une VM ? ##

* Léger
  * le conteneur ne contient que le processus de l'application
  * scalabilité "facilitée"
* Reproductible
  * on peut recréer un conteneur vierge rapidement
  * on peut scripter la création de l'image
* Portabilité
  * la même exécution quelque soit l'environnement
  * les images peuvent être transférées d'une machine à une autre

## Les apports de Docker face aux autres solutions ##

* hub.docker.com : diffusion d'image officielle et communautaire
* Simplification de la création de conteneur, notamment pour la communauté dev
* Concept de configuration par variables d'environnement
* Cross platform

## Cas d'usage ##

* **Développement** : Environnement portable
* **Intégration continue** : Environnement propre et portable
* **Optimisation** : Mutualisation de ressources matérielles
* **Gestion de l'obsolescence** : Plusieurs versions de l'environnement d'exécution sur le même serveur

## Docker : l'outil ##

Les installation classiques de Docker sont composées de 2 éléments principaux (sans descendre plus bas niveau).

* Un  **Daemon** avec une API qui va s'occuper des interaction avec les conteneurs , les images, les réseaux ...
* Un client **CLI** pour parler à cette API

## Versions ##

containerd runc etc,

- CE : *community edition* qui est la version gratuite que nous allons utiliser ;
- EE : *entreprise edition* qui est plus évoluée avec des fonctionnalités supplémentaires et une certification de fonctionnement sur certain matériel.

Depuis mars 2017, la nomenclature des versions suit la forme des versions de Ubuntu à savoir *AA.MM* avec AA l'année et MM le mois (ex. : 17.04 pour la version d'avril 2017).

- une version *stable* est compilée tous les trimestre (ex : 17.03, 17.06, 17.09, 17.12...) ;
- une version *edge* est compilée tous les mois et contient les nouvelles fonctionnalités.

## Architecture ##

Un conteneur ne devrait isoler qu'un seul et unique processus à la fois, une fois ce processus terminé le conteneur s'arrête.
Cette règle peut parfois être transgressé lorsqu'une amélioration notable peut être apportée par l'exécution de plusieurs processus dans un même conteneur (par ex: apache et php).

## Installation ##

Mickaël Borne (IGN) à préparé une excellente documentation, basée sur la documentation officielle (<https://docs.docker.com/engine/install/ubuntu/>) pour installer Docker dans l'environnement IGN.

<http://dev-env.sai-experimental.ign.fr/outils/docker/installation-docker-ce/>

Pour une installation à l'ENSG, nous simplifieront quelques aspects.

`Nous allons installer Docker dans la machine virtuelle Ubuntu à partir de la documentation officielle`{.note}

<aside class="notes">

Il est nécessaire d'installer curl

</aside>

## Installation : particularités IGN 1 ##

Pour permettre les accès à internet dans un environnement ou un proxy doit être utilisé, il faut configurer celui ci à plusieurs endroits.
Tout d'abord, au niveau du Daemon Docker qui est lancé par System-D :

```bash
sudo mkdir -p /etc/systemd/system/docker.service.d/
sudo vi /etc/systemd/system/docker.service.d/proxy.conf
```

Dont le contenu sera (à adapter) :

```bash
[Service]
Environment="http_proxy=http://proxy.ign.fr:3128/"
Environment="https_proxy=http://proxy.ign.fr:3128/"
Environment="no_proxy=localhost,127.0.0.1,docker-registry.ign.fr"
```

## Installation : particularités IGN 2 ##

Lors des modification de configuration, il faut s'assure que le daemon Docker tourne correctement:

```bash
sudo systemctl status daemon-reload
```

Puis, au besoin il faut le relancer :

```bash
sudo systemctl daemon-reload
sudo systemctl restart docker
```

<aside class="notes">
 préciser qu'il faudra indiquer les réglages proxy dans les images également

</aside>


## Installation : particularités IGN 2 ##

Le fichier */etc/docker/daemon.json* va permettre de définir les spécificités réseaux de l'installation Docker.

```json
{
    "bip": "192.168.199.1/24",
    "fixed-cidr": "192.168.199.1/25",
    "default-address-pools" : [
        {"base" : "192.168.200.0/23","size" : 28}
    ]
}
```
Au niveau du réseau, cette configuration permet de ne pas avoir de collision avec les réseaux IGN/ENSG


## Installation : particularités IGN 3 ##

Si on souhaite interagir avec les environnements interne à l'IGN, il faut également préciser :

 - Les serveurs DNS
 - les registres Docker non sécurisés

```json
    "dns": [
        "172.21.2.14",
        "172.16.2.91"
    ],
    "dns-search": ["ign.fr"],
    "insecure-registries": [
        "http://registry.localhost",
        "http://localhost:5000",
        "http://dockerforge.ign.fr:5000"
    ]
```

## Installation : Droits ##

Pour éviter d'avoir à faire sudo docker, on peut ajouter un utilisateur au groupe docker :

* Ajouter l'utilisateur au groupe docker : 
```bash 
sudo adduser $USER docker
```
* Redémarrer la session
* Tester
```bash
docker run --rm hello-world
```
Nous venons de lancer notre premier conteneur

note : Le projet [PWD](http://play-with-docker.com) permet également de tester Docker sans l'installer au travers d'une interface web. Les sessions de travail durent 4h et la vitesse de l'interface dépend souvent de l'activité des autres utilisateurs...

## Testons votre installation ##

```shell
docker container run hello-world
# Alias : docker run hello-world
```

La commande par défaut de cette image affiche un message confirmant la bonne installation de *Docker Engine*.
Si l'image n'est pas disponible en local, elle sera téléchargée sans avoir à faire un `docker image pull hello-world`

# Docker : utilisation #

## Les images Docker ##

L'image est le "disque dur" figé sur lequel va se baser le conteneur.

Elle contient l'application, ses dépendances et des métadonnées.

C'est le moule pour créer les conteneurs.

## Où trouve-t-on les images ? ##

* Sur des *registry* sur internet, principalement hub.docker.com
* sur votre machine si vous avez déjà téléchargé l'image
* *from sracth* ou basée sur des images de base (ubuntu, centOs, alpine)
* à partir d'un *Dockerfile*
* en *commitant* un conteneur

## Nomenclature des Images ##

```bash
[REGISTRY/]IMAGE[:TAG]
```

- `REGISTRY` : URL du dépôt (par défaut hub.docker.io)
- `IMAGE` : nom de l'image (peut contenir un chemin)
- `TAG` : tag de l'image (par défaut *latest*)

par exemple :

- `node:14.20-alpine`
- `registry.gpf-tech.ign.fr/geoplateforme/gpf-rok4:latest`

## Architecture des Images ##{.figcenter}

![](https://docs.docker.com/storage/storagedriver/images/container-layers.jpg)

## Commandes relatives aux images ##

Nous verrons plus tard comment travailler avec les images

#TODO: lien vers Images

## Les conteneurs ##

- Un conteneur est une instance d'image.
- Le conteneur permet d'isoler un processus (et ses enfants)
- Le conteneur ne peut pas vivre si le processus se termine.
- Chaque conteneur a son propre stockage même s'ils sont basés sur la même image.

## Démarrer un conteneur ##

```bash
docker container run OPTIONS REGISTRY/IMAGE:TAG COMMANDE 
```

- `OPTIONS` : diverses options sont possibles
- `REGISTRY/IMAGE:TAG` : l'image à utiliser
- `COMMANDE` : la commande à lancer dans le conteneur. **Une commande par défaut peut être définie dans les métadonnées de l'image.**

Cette commande créé le conteneur (l'environnement d'exécution) et lance le processus dans le conteneur.

## Démarrer un conteneur ##

Exemple :

```bash
docker container run alpine cat /etc/hostname
```

* On utilise l'image `alpine`
* On exécute la commande `cat /etc/hostname`
* Le conteneur affiche le contenu du fichier `/etc/hostname` et s'arrête.

## Démarrer un conteneur ##

Exemple :

```bash
docker container run -it alpine /bin/sh
```

Démarre un shell dans le conteneur.

*Comme si on était dans une VM.*

## Lister les conteneurs ##

```bash
docker container ls
CONTAINER ID   IMAGE     COMMAND   CREATED   STATUS    PORTS     NAMES
```

Mais pourquoi on ne voit pas les conteneurs d'avant ?

## Lister les conteneurs ##

```bash
docker container ls -a
CONTAINER ID    IMAGE       COMMAND              CREATED        STATUS                     PORTS       NAMES
a5b74e24da65    alpine      "cat /etc/hostname"  9 seconds ago  Exited (0) 6 seconds ago               happy_cori
```

## L'activité des conteneurs ##

```bash
docker container stats [NOM]
```

- CPU
- Mémoire
- Réseau
- Disque (i/o)

## Supprimer les conteneurs ##

```bash
docker container rm NOM
```

- Le conteneur doit être arrêté

## Gérer les conteneurs ##

- `stop` et `start` (`kill` aussi)
- `restart`
- `pause` et `unpause`

## Options utiles ##

| Options | Description|
| :--: | :--:|
| `--name` |  donner un nom au conteneur |
| `-i` | interactif |
| `-t` | forcer l'allocation d'un TTY |
| `--rm` | supprimer le conteneur à la fin de son exécution |
| `-d` | démarrer le conteneur en arrière-plan |

Il en existe beaucoup d'autres : gestion des ressources, environnement d’exécution...


## Mise en réseau ##

Le conteneur dispose généralement de son propre réseau virtuel.

Docker permet de définir :

*  les liens réseaux entre conteneurs
* les liens entre le réseau physique de l'hôte et les réseaux virtuels

## Création de réseaux ##


```bash
# Créer un réseau
docker network create ...
# Connecter un conteneur sur un réseau
docker network connect ...
# Liste des réseaux
docker network ls ...
# Déconnecter un conteneur d'un réseau
docker network disconnect ...
# Supprimer un réseau
docker network rm ...
```

## A la création du conteneur ##

La commande `docker container run` dispose de l'option `--net`

4 valeurs possibles :

- `none` : pas de réseau
- `host` : les réseaux de l'hôte
- `bridge` (par défaut) : un réseau isolé avec un mécanisme de *bridge*
- Le nom d'un réseau créé avec la commande `docker network create`

## Réseau de l'hôte ##

```bash
docker container run --rm --net host alpine ip a
1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN qlen 1
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    inet 127.0.0.1/8 scope host lo
       valid_lft forever preferred_lft forever
33481: eth0@if33482: <BROADCAST,MULTICAST,UP,LOWER_UP,M-DOWN> mtu 1500 qdisc noqueue state UP 
    link/ether e6:bd:09:a3:dc:84 brd ff:ff:ff:ff:ff:ff
    inet 192.168.0.8/23 scope global eth0
       valid_lft forever preferred_lft forever
```



## Réseau bridge par défaut ##

- Les conteneurs sont sur un réseau séparé
- Ils peuvent communiquer avec l'extérieur et entre eux (via l'IP)
- L'extérieur ne peut pas communiquer avec le conteneur, sauf si explicitement demandé (option `-p`).

## Réseau créé ##

- Prévu pour interconnecter des conteneurs
- Même fonction que `bridge` + résolution DNS des autres conteneurs

## Exposer un port sur l'hôte ##

```bash
docker container run --rm -p 8080:80 httpd:alpine
```

-> [http://127.0.0.1:8080](http://127.0.0.1:8080)

Le port 8080 de la machine hôte est redirigé vers le port 80 du conteneur.

## Les volumes ##

Un conteneur est "jetable"

* Lorsqu'on détruit un conteneur, on supprime aussi les modifications apportées au système de fichier.
* Les conteneurs ont des systèmes de fichiers isolés.

**Les volumes apportent une solution à cela**


## 2 types de volumes ##

- un dossier de la machine hôte
- un volume géré par docker


## Volume hôte ##

On utilise l'option `-v LOCAL_PATH:PATH_ON_CONTAINER:MODE`

- `LOCAL_PATH` : le chemin absolu sur l'hôte
- `PATH_ON_CONTAINER` : où brancher ce dossier dans le conteneur ?
- `MODE` (optionnel) : mode d'accès, principalement *rw* (par défaut) et *ro*

```bash
docker container run --rm -it -v /:/mondossierlocal:ro alpine /bin/sh
```

## Volume docker ##

* Gestion des volumes avec un workflow dédié :
  * create
  * ls
  * rm
* Abstraction du backend de stockage :
  * local
  * partage réseau
  * baie de stockage

## Créer un volume ##

```bash
docker volume create --name NAME [OPTS]
```

## Créer un volume lors de la création d'un conteneur ##

```bash
docker container run -v [NAME]:[PATH_ON_CONTAINER]:[OPTS]
```

On peut préciser le *driver* à utiliser. Le driver dépend du backend, par défaut local.


## Volume anonyme ##

Les métadonnées d'une image peuvent forcer la création d'un volume

```bash
docker image inspect rok4/data-bdortho-d075                                            
[{...
    "Config": {...
        "Volumes": {
            "/rok4/config/pyramids/ORTHO_JPG_PM_D075": {}
        }
    },
...]
```

## Volume docker ##

Lister les volumes

```bash
$ docker volume ls
DRIVER              VOLUME NAME
local               2bd7394a7adebb03f073bd82048048124578e0b506adea3064fda5d38ef7b678
local               data-telegraf
local               e0c1ad4b13ed61067082a3511feaae14dbdcacd19632594c129548e241575e0c
local               minidlna
local               mongodb
```

*Dans certains cas, Docker créé des volumes "anonymes", leur nom est une longue chaîne alphanumérique*


## Supprimer un volume ##

```bash
docker volume rm NAME
```

## Supprimer un volume lors de la suppression d'un conteneur

```bash
docker container rm -v CONTAINER_NAME
```

⚠️ Ne concerne que les volumes anonymes

## Gestion des données à la création d'un volume ##

- un volume hôte remplace totalement un chemin du conteneur.
- un volume docker utilisé pour la première fois est initialisé avec le contenu du chemin de montage dans le conteneur.


## Volumes avancés ##

L'option `--mount` permet des montages plus élaborés :
- autant de possibilités qu'avec le fichier `/etc/fstab`
- suppose que le support existe, pas de création à la volée comme avec un `docker volume create` (ex. : pas de création de l'export NFS sur le serveur)


## Volumes avancés ##

Un volume hôte est un `--mount` particulier.

`docker run -it -v /chemin/sur/mon/ordi:/data alpine sh`

`docker run -it --mount type=bind,source=/chemin/sur/mon/ordi,target=/data alpine sh`


## A vous de jouer ##

Lors de l'installation , nous avons créé un conteneur pour vérifier que Docker était bien installé

`Observer que le conteneur est bien existant et à l'arrêt`{.note}

## correction ##

```bash
docker ls 
docker ls -a
```

`docker ls` est un alias de `docker container ls`

La première commande n'affiche rien car le conteneur lancé est déjà arrêté. En effet, l'image `hello-world` lance une commande qui affiche un message et c'est tout. Il faut ajouter l'option `-a` pour afficher également les conteneurs arrêtés.

## Gérer les conteneurs : supprimer ##

`Supprimer le(s) conteneur(s) déjà créé`{.note}

## correction ##

```bash
docker container rm NAME
```

On peut trouver le nom du conteneur à supprimer dans le résultat de la commande `docker container ls -a`. Si aucun nom n'est spécifié lors de la création du conteneur (option `--name`), docker génère un nom aléatoire composé d'un adjectif et d'un nom de personnalité.

On pourrait aussi utiliser l'identifiant du conteneur à la place du nom.

## Gérer les images ##

`Afficher la liste des images`{.note}

`Créez un nouveau conteneur hello-world, notez le temps de chargement de l'image , supprimer ensuite la(s) images(s) existantes`{.note}

## Correction ##

```bash
docker image ls
docker image rm <IMAGE-NAME>
docker run hello-world
docker ls -a
docker rm <CONTAINER-NAME>
docker image rm hello-world
```

La commande `docker container rm` ne supprime que le conteneur. Pour supprimer l'image, il faut utiliser la commande `docker image rm <IMAGE-NAME>` (ou l'alias `docker rmi <IMAGE-NAME>`).
Une image ne peut pas être supprimée si il existe un conteneur basé dessus, même stoppé.
Il faut donc supprimer le conteneur avant de pouvoir supprimer l'image.

## Gérer les repository ##

<https://hub.docker.com/_/alpine/>

`Télécharger la dernière version de l'image "alpine" sur dockerhub`{.note}

## Correction ##

```bash
docker image pull alpine
docker image inspect alpine:latest
```

**Dockerhub** est le dépôt d'image par défaut de Docker.
Ici, on ne précise pas le tag. C'est donc le tag `latest` qui est téléchargé.
On affiche ensuite les métadonnées de l'image téléchargée, on y trouvera des informations utiles sur l'image :

* date de création
* type de processeur compatible
* commande par défaut
* ...



## Gérer les entrées/sorties ##

`Action : Démarrer un conteneur avec un shell interactif basé sur l'image "alpine" puis, dans un second terminal, observer les conteneurs en cours d’exécution. Stopper le conteneur (sans le détruire).`{.note}

`Action : relancer le conteneur et se rattacher à son terminal .`{.note}

`Action : Faire le ménage .`{.note}


## Correction ##

```bash
docker run --rm -i -t alpine sh
docker run --rm -i -t alpine cat /etc/hostname
```
Une fois dans le conteneur, on peut afficher le nom d'hôte du conteneur avec la commande `cat /etc/hostname`.
On peut également modifier la commande (unique) lancée dans un conteneur.

```bash
docker ls
docker stop NAME
docker ls
```

On peut voir le conteneur en état `Exited`. Dans le premier terminal, on peut voir que le shell est arrêté.


## Correction ##

```bash
docker  start NAME
docker attach NAME
```

```bash
docker stop NAME 
docker rm NAME 
```

Pour aller plus loin, chercher des informations sur les options `-d`, `-w`, `-h`, `--rm` et `--name` de la commande `docker container run` et tester ces options.

```bash
docker container run --help
```

## Gérer les volumes "host" ##

`Action : Démarrer un conteneur "alpine" avec un TTY en montant la racine de la machine hôte sur "/host" en mode "volume hote" et en lecture seule dans le conteneur. Observer le contenu de "/host", par exemple, ajouter un nouveau fichier dans votre "~/"`{.note}

## Correction ##

```bash
docker  run -i -t -v /:/host:ro alpine sh
```

Le fichier `/etc/hostname` contient bien le nom d'hôte du conteneur. Le fichier `/host/etc/hostname` contient le nom d'hôte de la machine hôte.

## Gérer les volumes docker ##

`Action : Démarrer un conteneur "alpine" avec un TTY et un volume docker sur "/data", créer un fichier dans "/data/" puis supprimer le conteneur `{.note}

`Action : Démarrer un nouveau conteneur "alpine" avec un TTY et un volume docker avec le même nom sur "/data", Observer le contenu de "/data/"`{.note}

`Action : Lister puis supprimer les volumes `{.note}

## Correction ##

```bash
docker run --rm -i -t -v NAME:/data:rw alpine sh

docker run -i -t -v NAME:/data:rw alpine sh

docker volume ls
docker volume rm NAME
```

On ne peut supprimer un volume que si aucun conteneur ne l'utilise. Le message d'erreur indique l'identifiant du conteneur utilisant ce volume :

```bash
$ docker volume rm Data
Error response from daemon: remove Data: volume is in use - [a1bf0b7f7cb74abd283b3190af6efee0a9cc4d12bede45fe698b71c368b2f57a]
```

## Gérer le Réseau ##

`Action : Affichez la liste des interfaces de votre machine hôte puis d'un conteneur avec la commande "ip a"`{.note}

`Action : Lancez un nouveau conteneur avec l'option "--net host" puis regarder les interfaces`{.note}

## Correction ## 

```bash
ip a
docker run --rm -it alpine ip a
docker run --rm -it --net host alpine sh
```

L'option `--net host` branche le conteneur sur les interfaces réseaux de la machine hôte. **Il n'y a donc pas d'isolation réseau.**, c'est une option à éviter dans la plupart des cas.

## Exposition de ports ##

<https://hub.docker.com/r/containous/whoami/>

`Action : Démarrez un conteneur basé sur l'image "whoami", inspectez le et trouver l'IP pour tester une requete HTTP sur cette IP. `{.note}


`Action : Démarrez un conteneur basé sur l'image "whoami", inspectez le et trouver l'IP pour tester une requete HTTP sur cette IP `{.note}

## Correction ##

```bash
docker container run containous/whoami
docker container inspect NAME
curl -s http://<IP_CONTENEUR>
```

L'IP du conteneur n'est accessible que depuis la machine hébergeant le conteneur. On ne peut pas y accéder depuis l'exterieur de la machine hôte avec son adresse IP car c'est un réseau privé, interne à notre hôte.

<aside class="notes">
        NO_PROXY=10.201.0.3 curl -s http://10.201.0.3
</aside>

## Exposition de ports ##

`Action : Recréez un conteneur en ajoutant cette fois l'exposition du port 80 du conteneur sur le port 8080 de la machine et refaire les tests `{.note}


## Correction ##

```bash
docker container run -p 8080:80 containous/whoami
curl -s http://127.0.0.1
curl -s http://<IP_HOST>
```

On peut toujours accéder au port 80 de l'IP du conteneur. On peut maintenant également accéder au port 8080 de la machine grâce à l'option `-p`.

## Création de Réseau ##

#TODO: miniexo réseau

# TP LAMP #

## Objectif LAMP ##

Le but de ce TP est de mettre en place les éléments nécessaires d'un serveur web de type LAMP.

> - **L**inux
> - **A**pache
> - **M**ySql
> - **P**HP

Les fichiers nécessaires sont disponible dans le dépôt GIT suivant

<https://exo.git>

## Apache ##

L'image à utiliser ici est `httpd`. Les options `--name -d -p -v ` peuvent être utiles. La racine du serveur web dans l'image est `/usr/local/apache2/htdocs/`

* Lancez un conteneur exposant le port *80* du conteneur sur le port *8080* de la machine. Qu'affiche la page <http://127.0.0.1:8080> ?

* Utilisez le fichier `index-lamp.html` pour remplacer la page d’accueil

## Un peu de php ##

* Remplacez le fichier html par `index-lamp.php` (pensez à le renommer). qu'observez vous?

## Correction ##

`httpd` est de base un simple serveur web sans fonctionnalité php. Il faudrait ajouter php dans cette image et configurer httpd pour interpréter les fichiers php. Sans cela, httpd cherche uniquement les fichiers `index.html` si aucun fichier n'est précisé dans l'URL.

Même en ajoutant `index.php` à l'URL, le résultat n'est pas satisfaisant, il n'y a qu'une page verte alors qu'elle devrait afficher l'heure.

* Recréez votre conteneur en utilisant l'image **lavoweb/php-7.1** qui contient l'interpréteur PHP. Attention, dans cette image, la racine du serveur Web est maintenant `/var/www/html`

Note : On déroge ici un peu à la règle 1 processus par conteneur. On pourrait séparer apache et PHP, mais la liaison serait plus complexe.

* Utilisez le site et profitez en pour regarder les logs `docker logs <NAME>`

<aside class="notes">

docker  run -d --name web -p 8080:80 -v $PWD/mondossier:/var/www/html/ lavoweb/php-7.1

</aside>

## Ajout d'une Base De Données ##

Notre site web évolue ! Il va maintenant afficher une carte. Un clic permet de créer un point, sauvegardé en base de données. Un clic sur un point le supprime. Au chargement de la page, on affiche tous les points de la base de données.

* remplacer le fichier `index.php` par le fichier `index-bdd.php`, observez les erreurs

Pour respecter le principe d'un seul processus par conteneur, il faut lancer un second conteneur pour notre base de données. Utilisez l'image `mariadb`. Vous trouverez des informations sur la configuration de cette image sur [hub.docker.com](https://hub.docker.com/_/mariadb).

* Configurez la base de données de façon à ce que le code php fonctionne (nom d'utilisateur, mot de passe et base de données). L'option `-e` de `docker run` permet de passer des variables d'environnement au conteneur. Les deux conteneurs doivent se situer sur le même réseau pour pouvoir se "voir" (DNS)

## Correction ##

Les variables à utiliser :

| Nom | Valeur | Explication |
| -- | :--: | ---------- |
| `MARIADB_RANDOM_ROOT_PASSWORD` | `yes` | Laisse le choix du mot de passe du super administrateur de la base à mariadb. On pourrait utiliser `MARIADB_ROOT_PASSWORD` pour choisir ce mot de passe, mais dans le contexte de ce TP, ça n'a pas d'importance. |
| `MARIADB_DATABASE` | `mymap` | C'est le nom de la base de données à créer. Ce nom est défini dans le code PHP. |
| `MARIADB_USER` | `user` | C'est le nom de l'utilisateur à créer. Il est défini dans le code PHP. |
| `MARIADB_PASSWORD` | `s3cr3t` | C'est le mot de passe de l'utilisateur à créer. Il est défini dans le code PHP. |

Avec la commande `docker exec ...`, lancez le client mariadb en ligne de commande pour explorer la base de données et observez le contenu de la table point au fur et à mesure des interactions avec la carte. (`select * from point;` sur la table *mymap* )

<aside class="notes">

Le fait de définir les paramètres de connexion à la base de données dans un code source est une mauvaise pratique. Il faudrait que le code PHP détermine ces informations à partir de variable d'environnement.

docker network create lamp
docker run --net lamp -d --name web -p 8080:80 -v $PWD/mondossier:/var/www/html/ lavoweb/php-7.1
docker run --net lamp -d --name database -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=mymap -e MARIADB_USER=user -e MARIADB_PASSWORD=s3cr3t  mariadb
docker exec -it database mariadb -u user -D mymap --password="s3cr3t"

</aside>

## Persistence de la Base De Données ##

Détruisez les conteneurs, puis reconstruisez l'ensemble. Les points sont perdus :-(
  
* *Résolvez ce problème

<aside class="notes">

docker run -v databasedata:/var/lib/mysql --net lamp -d --name database -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=mymap -e MARIADB_USER=user -e MARIADB_PASSWORD=s3cr3t  mariadb

</aside>


# TP NextCloud #

## Déployer un nextCloud ##

Le but est de mettre en place NextCloud, un gestionnaire de fichier en ligne.
L'image à utiliser est **nextcloud**, disponible sur [https://hub.docker.com/_/nextcloud/](https://hub.docker.com/_/nextcloud/). Cette page contient beaucoup de détails que vous pouvez trouver dans les métadonnées de l'image.

## Première étape ##

un serveur simple avec une base de données SQLite (un fichier)

* Lancez un conteneur exposant sur le port 8080 de la machine hôte le serveur nextcloud et rendez-vous sur [http://127.0.0.1:8080](http://127.0.0.1:8080) pour accéder à l'interface d'initialisation du serveur.
* Suivez les étapes d'installation. (base SQLite)
* Utiliser l'application pour générer du contenu (uploadez des fichiers)
* Utilisez la commande `docker exec` pour lister vos fichiers uploadé ( `/var/www/html/data/` )

## Deuxième étape ##

* Ajoutez un volume pour persister les données lorsque le conteneur est détruit (testez)
* Préparez run réseau **nextcloud** pour notre application
* Lancez un conteneur MariaDB ou PostgreSQL avec persistence de données, un nom et utilisant le réseau **nextcloud** et reconstruisez l'application en utilisant cette base de données


<aside class="notes">

docker network create nextcloud
docker run -d --name nextcloud -v nextcloud-data:/var/www/html/data/ --net nextcloud -p 8080:80  nextcloud
docker run -d --name nextcloud-database -v databasedata:/var/lib/mysql --net nextcloud -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=nextcloud -e MARIADB_USER=nextcloud -e MARIADB_PASSWORD=s3cr3t  mariadb

</aside>

# Dockerfile #

## Construire ses propres images ##

* Objectif
  * Automatiser la création des images grâce à un jeu d'instructions
  * Un environnement d’exécution propre lors des mises à jours
  * Pouvoir reconstruire : mouvance IaC, paradigme cloud

* A partir d'un Dockerfile
  * On part d'une image existante
  * Chaque ligne équivaut à lancer un conteneur et à le commiter
  * L'image est créée avec le nom spécifié

## Construction d'une image ##

On construit une image avec un Dockerfile

```bash
docker image build DOCKERFILE_PATH
```

`DOCKERFILE_PATH` est le chemin du dossier contenant le Dockerfile.

## Instructions Dockerfile ##

Le **Dockerfile** est constitué d'une suite d'instructions, chaque ligne résultant en une nouvelle **couche** dans l'image.

[Architecture des images](#/architecture-des-images)

Un Dockerfile commence généralement par l'identification de l'image de base.

```dockerfile
FROM debian:jessie
```

## Instructions Dockerfile ##

Modifier les métadonnées

```dockerfile
LABEL ma_cle="ma valeur"
```

Le consortium OpenContainers a défini une [liste de clés](https://github.com/opencontainers/image-spec/blob/main/annotations.md#pre-defined-annotation-keys) :

* org.opencontainers.image.authors
* org.opencontainers.image.version
* ...

## Instructions Dockerfile #

Lancer une commande

```dockerfile
RUN commande
```

* installer des dépendances
* déplacer des fichiers
* compiler une librairie
* ...

## Instructions Dockerfile ##

Ajouter des fichiers locaux ou distants

```dockerfile
ADD <src> <dest>
COPY <src> <dest>
```

Globalement identiques mais :

* `ADD` supporte les URL
* `ADD` décompresse les archives
* gestion du cache de build différente
* `COPY` est plus "prévisible"
* `COPY` est compatible aves le *Multi-stage*

## Instructions Dockerfile ##

Modifier l'environnement d'exécution

```dockerfile
ENV     #Variable d environnement
USER    #Changement d utilisateur
WORKDIR #Changement du dossier de travail
```

Il est fortement conseillé de ne pas utiliser le user root pour des raisons de sécurité.

## Instructions Dockerfile ##

Modifier l’exécution

```dockerfile
CMD    #Commande par défaut
EXPOSE #Déclarer un port réseau
VOLUME #Déclarer un volume
```

## Avancé : paramétriser le Dockerfile ##

```dockerfile
ARG <name>[=<default value>]
```

Dans le reste du fichier, on fait référence à cette variable avec `${name:-default_value}`

Pour définir la valeur lors de la construction :

```bash
docker image build --build-arg name=value .
```

## Multistage build ##

Le conteneur doit contenir ce qui est nécessaire pour le run, rien en rapport avec le dev/build.
Pour optimiser le poids de l'image de RUN, on sépare la création de l'image en plusieurs phases.
Un conteneur de *build* génère un package à copier sur le conteneur de *run*

## Multistage build ##

```dockerfile
FROM debian:jessie as builder
RUN apt-get update && apt-get install -y  build-essential BUILD_DEPENDENCIES
ADD https://github.com/...../master.zip /master
RUN make # Cette commande génère le fichier /master/monBinaire

FROM debian:jessie
RUN apt-get update && apt-get install RUN_DEPENDENCIES
COPY --from=builder /master/monBinaire /opt/bin/
CMD /opt/bin/monBinaire
```

[Documentation multistage build](https://docs.docker.com/develop/develop-images/multistage-build/)

## Bonnes pratiques de conceptions ##

* un conteneur est éphémère : **utilisation de volumes**
* un conteneur doit être léger : **juste ce qu'il faut**
* un seul processus par conteneur

## Bonnes pratiques de conceptions ##

* minimiser le nombre de couche du système de fichiers **en minimisant les commandes RUN et en utilisant beaucoup de `&&`**
* optimiser l'utilisation du cache de build :
  - les commandes qui changent le moins en premiers (`EXPOSE` ...)
  - les commandes ADD plutôt vers la fin

# TP Dockerfile #

## Une application node.js ##

Nous allons créer pas à pas une image Docker pour répondre à un besoin simple.

Le code de l'application est disponible dans le dépot <>

Commençons par y créer un fichier nommé `Dockerfile`

Premièrement, il faut choisir une image de base, pour node, nous allons choisir une version récente basé sur un OS Alpine : **(node:18-alpine)[https://hub.docker.com/layers/library/node/18-alpine/images/sha256-d51f2f5ce2dc7dfcc27fc2aa27a6edc66f6b89825ed4c7249ed0a7298c20a45a?context=explore]**

Il faut ensuite réaliser ces actions :

* Créer un dossier `/app` à la racine du conteneur
* Définir l'espace de travail dans ce nouveau dossier
* Copier le fichier `package.json` dans ce dossier
* Exécuter la commande `npm install --production` afin d'installer les dépendances
* Copier les sources (le dossier `/public` et le fichier `server.js` ) dans ce dossier
* exposer le port `1111`
* définir la commande par défaut : `npm start`

<aside class="notes">

```Dockerfile
FROM node:18-alpine

RUN mkdir -p /app
WORKDIR /app

COPY package.json /app/
RUN npm install --production

COPY public /app/public
COPY server.js /app/

EXPOSE 1111

CMD ["npm", "start"]
```

Docker build -t findmefast .

</aside>

## Une application node.js ##

On construira l'image avec la commande `docker build -t findmefast .`

Et on pourra tester l'application avec la commande `docker run` en mappant un port de votre machine sur le port `1111` du conteneur.

=> Pour pouvoir tester l'application entre vous il faudra faire une petite manipulation pour mapper également ce port dans la VM Virtualbox.


## Une compilation C ##

Nous allons maintenant créer une petite image **multistage** pour compiler puis exécuter un petit programme qui calcul **n** n nombre premier (le seul intérêt de ce programme est de solliciter le CPU).
Nous profiterons de ce programme pour bien comprendre la notion de *Kernel* et de *Userspace* dans les conteneurs. DAns un premier temps , nous allons compiler le programme sur notre host.

* Placez vous dans le dossier `/prime` du dépot git
* Installez `gcc` : `sudo apt-get update && sudo apt-get install gcc`
* Compiler le programme : `gcc prime.c -o prime`
* Mesurez le temps nécéssaire pour calculer les 10000 premiers nombres premiers (utilisez `time`, le programme `./prime` prends en argument la quantité de nombre premier à trouver.


<aside class="notes">

time ....

</aside>

## Compilation dans un conteneur ##

Créez une nouvelle image que vous nommerez **prime:normal**

* Partir d'une image `debian` dernière version disponible
* Installer les paquets nécessaires pour la compilation
* Ajouter le fichier source
* Compiler le programme
* Définir une commande par défaut (Par exemple `./prime 1`)
* Mesurez le temps nécessaire pur calculer les 10000 premiers nombres premiers avec cette image.

Que pensez vous des résultats obtenus, est-ce normal d'après vous?


## Amélioration multistage ##

Nous allons améliorer l'image en créant une image multistage

* Nommer le premier `FROM` pour définir un premier *stage*
* Une fois le fichier compilé, créer un nouveau *stage*
* Récupérer le fichier compilé lors du premier *stage*
* Laissez la commande par défaut
* Générez une nouvelle image appelée **prime:multi** 

/!\ n'écrasez pas l'image **prime:normal** /!\

* Comparez le poids des deux images
* Comparez les vitesses d’exécution


<aside class="notes">

```Dockerfile
FROM debian:bookworm as builder

RUN apt-get update && apt-get install -y gcc
ADD ./prime.c /prime.c
RUN gcc prime.c -o prime

FROM debian:bookworm-slim

COPY --from=builder prime prime

CMD [ ./prime 1 ]
```

```bash
docker run -ti prime ./prime 10000
```

</aside>

# TP LibreQR #

## Création du Dockerfile ##

Vous êtes en avance, voici une proposition de Dockerfile à créer en toute autonomie : dockeriser une application **LibreQR**, une application web de génération de QR Code.
Voici les ressources nécéssaires :

* Le code source et la documentation se trouvent là : <https://code.antopie.org/miraty/libreqr>
* basé sur l'image `php:apache`
* Pour installer la dépendance `php-gd` on utilisera plutôt <https://github.com/mlocati/docker-php-extension-installer>
* Penser à rendre le dossier et les fichiers accessibles à l'utilisateur du service httpd `www-data`.
* Utiliser la configuration PHP de production et non de développement (voir la partie "Configuration" de <https://hub.docker.com/_/php> )
* Modifier le fichier de configuration à inclure pour changer le texte affiché en bas de page.

<aside class="notes">

```Dockerfile
FROM php:apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd

ADD https://code.antopie.org/miraty/libreqr/archive/main.zip /main.zip

RUN apt-get update && \
    apt-get install -y unzip && \
    cd /var/www/html && \
    unzip /main.zip && \
    mv libreqr/* . && \
    chown -R www-data:www-data /var/www/html/

ADD config.inc.php /var/www/html
RUN chown -R www-data:www-data /var/www/html/
ENV LIBREQR_THEME=libreqr \
    LIBREQR_DEFAULT_LOCALE=fr \
    LIBREQR_CUSTOM_TEXT_ENABLED=true \
    LIBREQR_CUSTOM_TEXT="LibreQR on docker" \
    LIBREQR_DEFAULT_REDUNDANCY=high \
    LIBREQR_DEFAULT_MARGIN=20 \
    LIBREQR_DEFAULT_SIZE=300 \
    LIBREQR_DEFAULT_BGCOLOR=FFFFFF \
    LIBREQR_DEFAULT_FGCOLOR=000000
```

!!! abstract "config.inc.php"
```
<?php
// Basé sur https://code.antopie.org/miraty/libreqr/src/branch/main/config.inc.php
// This file is part of LibreQR, which is distributed under the GNU AGPLv3+ license

// LIBREQR SETTINGS

// Theme's directory name
define("THEME", $_ENV["LIBREQR_THEME"]);

// Language used if those requested by the user are not available
define("DEFAULT_LOCALE", $_ENV["LIBREQR_DEFAULT_LOCALE"]);

// Will be printed at the bottom of the interface
define("CUSTOM_TEXT_ENABLED", $_ENV["LIBREQR_CUSTOM_TEXT_ENABLED"] == "true");
define("CUSTOM_TEXT", $_ENV["LIBREQR_CUSTOM_TEXT"]);

// Default values
define("DEFAULT_REDUNDANCY", $_ENV["LIBREQR_DEFAULT_REDUNDANCY"]);
define("DEFAULT_MARGIN", intval($_ENV["LIBREQR_DEFAULT_MARGIN"]));
define("DEFAULT_SIZE", intval($_ENV["LIBREQR_DEFAULT_SIZE"]));
define("DEFAULT_BGCOLOR", $_ENV["LIBREQR_DEFAULT_BGCOLOR"]);
define("DEFAULT_FGCOLOR", $_ENV["LIBREQR_DEFAULT_FGCOLOR"]);

```

```
docker image build -t qr .
docker container run -p 8080:80 -e LIBREQR_CUSTOM_TEXT="Générateur de QR code dans docker" qr

```

</aside>

## Proposez vos projets 

Nous pouvez voir ensemble les étapes nécessaires à la **Dockerisation** de vos applications.



























































# Docker-compose #

## Bilan docker ##

- Un commande par conteneurs
- Toutes les options à écrire
- Gestion fine des conteneurs, réseaux et volumes

## Limites Docker+Dockerfile ##

Imaginez la complexité pour déployer un CMS comprenant :

- un CMS (Wordpress)  
- une base de données
- un FTP pour déposer des fichiers à publier
- un système de cache pour soulager la BDD
- un proxy inverse pour gérer le reste du cache


<aside class="notes">
![Déploiement wordpress en docker](/img/docker-wordpress.png)
</aside>

## Définition ##

Docker-compose permet de définir tous les éléments nécessaires pour faire tourner une application multi-conteneurs.

- Un fichier définit les composants :
  - image
  - réseau
  - volume
  - relation/dépendance
- Compose pilote directement le daemon docker

## Le fichier de définition ##

L'application est définie dans un fichier au format YAML avec 3 sections principales, plus quelques autres informations :

- *la version du format*
- les **services**
- les **volumes**
- les **réseaux**
- *des configs (pour une utilisation swarmkit)*
- *des secrets (pour une utilisation swarmkit)*

## Les versions ##

Le modèle du docker-compose.yml a plusieurs versions possibles.

| Compose file format | Docker Engine |
| ------------------: | :------------ |
| 3.8                 | 19.03.0+      |
| 3.7                 | 18.06.0+      |
| 3.6                 | 18.02.0+      |
| ...                 | ...           |
| 2.0                 | 1.10.0+       |
| 1.0                 | 1.9.1.+       |

<https://docs.docker.com/compose/compose-file/compose-versioning/>

## Les services ##

Permet de définir les éléments composant l'application :

- image ou *Dockerfile*
- utilisation de volumes
- utilisation de réseaux
- port d'écoute
- ...

Tous les détails sur la [doc](https://docs.docker.com/compose/compose-file/#/service-configuration-reference).

## Services : images ou build ##

Quelle est la base du conteneur ?

- une image

```yaml
image: httpd:alpine
```

- un *DockerFile*

```yaml
build: ./path
# ou
build:
  context: ./path
  dockerfile: monDockerFile
  args:
    ...
```

Utiliser une image est plus sûr. Un pipeline de CI est chargé de construire les images avec une gestion des Tags permettant d'avoir des releases connues.
La création de l'image "à la volée" dans le docker-compose est une option viable en mode de développement.

## Services : volumes ##

Montage des volumes Docker ou host

```yaml
volumes:
  # sans précision, Docker créé un volume nommé (sans nom...)
  - /var/lib/mysql

  # Un volume Docker nommé
  - datavolume:/var/lib/mysql
  
  # Un volume "host" en chemin absolu
  - /opt/data:/var/lib/mysql
  
  # Un volume "host" en chemin relatif au fichier docker-compose
  - ./cache:/tmp/cache
  
  # Un volume "host" en chemin relatif au home de l'utilisateur
  - ~/configs:/etc/configs/:ro
  
```

## Services : réseau ##

Branchement des réseaux et exposition de ports sur la machine hôte

```yaml
services:
  some-service:
    networks:
     - some-network
     - other-network
    ports:
     - "80:80" #Bien mettre les guillemets
```

Seuls les services qui seront exposés doivent être connecté au réseau "externe" , les autres services doivent être connectés au même réseau interne pour pouvoir communiquer entres eux.






## Services : dépendances inter-conteneurs ##

Docker-compose démarre les conteneurs dans le bon ordre à condition qu'il le connaisse...

- on peut déclarer des dépendances avec **depends_on**

```yaml
depends_on:
  - service1
  - service2
```



## Services : command ##

Pour surcharger la commande par défaut, on utilise le paramètre **command**

```yaml
command: some command && some other
```



## Les volumes ##

- Permet de définir des volumes (driver, options)
- Tout volume utilisé par un service doit être déclaré, même s'il est externe (*external*)
- Les montages host->container ne sont pas concernés

Tous les détails sur la [doc](https://docs.docker.com/compose/compose-file/#volume-configuration-reference).

```yaml
volumes:
  monvolume:
  monsecondvolume:
    driver: toto
```

## Les réseaux ##

- Permet de définir des réseaux (driver, options)
- Par défaut, un réseau est créé par projet
- Gestion de l'IPAM
- Tout réseau utilisé par un service doit être déclaré, même s'il est externe (*external*)

Tous les détails sur la [doc](https://docs.docker.com/compose/compose-file/#/network-configuration-reference).

## Réseaux : driver par défaut (bridge) ##

```yaml
networks:
  monreseau:
    ipam:
      config:
        - subnet: 192.168.91.1/24
          ip_range: 192.168.91.0/25
          gateway: 192.168.91.1
```

#TODO :  plein d'autres choses à dire sur les réseaux



## CLI ##

Depuis la version2 de docker compose, la commande `docker-compose` est intégré à la commande `docker` et devient donc une sous-commande de la commande `docker`.

On lance l'application avec la commande

```bash
docker compose up [SERVICE]
```

L'option `-d` permet de lancer les conteneurs en arrière-plan.
L'option `-f` permet de spécifier un fichier compose différent (par défaut `docker-compose.yaml`)


## Commandes ##

Gestion des conteneurs

```bash
docker compose stop [SERVICE]
docker compose kill [SERVICE]
```

## Commandes ##

Nettoyage des conteneurs stoppés

```
docker-compose rm
```

Nettoyage des éléments

```
docker-compose down
```



## Documentation ##

`docker-compose help`

ou

[Compose command-line reference](https://docs.docker.com/compose/reference/)