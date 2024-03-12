---
title : Docker par la pratique
sub-title : IGN
author : Cédric Esnault
date : 12/03/2024 - IGN/ENSG
---

# Docker #

## Plan de la présentation ##

- Concepts
  - Virtualisation
  - Cloud
- Docker
  - Historique / installation
  - Utilisation
  - TPs
  - Dockerfile
  - TPs
- Docker-compose
  - Utilisation
  - Tps
- Kubernetes
  - Utilisation
  - Demo

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

## Isolation applicative ##{.figcenter}

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

<aside class="notes">

</aside>


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

Les installations classiques de Docker sont composées de 2 éléments principaux (sans descendre plus bas niveau).

* Un  **Daemon** avec une API qui va s'occuper des interaction avec les conteneurs , les images, les réseaux ...
* Un client **CLI** pour parler à cette API

l'[OCI](https://opencontainers.org
) est Un organisme qui à pour but de normaliser les éléments constitutifs de la virtualisation et des conteneurs.
C'est important car il existe également des alternatives/concurrents à Docker.


<aside class=notes>

Il existe des alternatives/concurrents à Docker.

OCI : Open Container Initiative
CNCF : Cloud Native Computing Foundation https://www.cncf.io/
Docker :
  runc : CLI
  containerd : démon
rkt :  coreOS rocket
Buildah : build images
Kaniko : build images
podman : deamonles docker

<aside>

## Versions ##

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
    //...
}
```

Au niveau du réseau, cette configuration permet de ne pas avoir de collision avec les réseaux IGN/ENSG, particulièrement lors de l'utilisation du VPN.

## Installation : particularités IGN 3 ##

Si on souhaite interagir avec les environnements interne à l'IGN, il faut également préciser :

- Les serveurs DNS
- les registres Docker non sécurisés

```json
{
//...
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
//...
}
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

<aside class="notes">

Attention : alerte sur les problèmes de sécurité avec Docker et les images ROOT.

</aside>

## Testons votre installation ##

```shell
docker container run hello-world
# Alias : docker run hello-world
```

La commande par défaut de cette image affiche un message confirmant la bonne installation de *Docker Engine*.
Si l'image n'est pas disponible en local, elle sera téléchargée sans avoir à faire un `docker image pull hello-world`

![](img/Hello-world.png)

# Docker : utilisation #

## Les images Docker ##

L'image est le "disque dur" figé sur lequel va se baser le conteneur.

Elle contient l'application, ses dépendances et des métadonnées.

C'est le moule pour créer les conteneurs.

## Où trouve-t-on les images ? ##

* Sur des *registry* sur internet, principalement hub.docker.com
* sur votre machine si vous avez déjà téléchargé l'image
* *from scratch* ou basée sur des images de base (ubuntu, centOs, alpine)
* à partir d'un *Dockerfile*
* en *commitant* un conteneur (à décourager)

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

![](img/container-layers.jpg)

## Commandes relatives aux images ##

Dans un premier temps, nous allons juste les utiliser afin de construire [des Conteneurs Docker](#/les-conteneurs)

Nous verrons plus tard comment travailler avec les images :

- `docker image ls`
- `docker image pull`
- `docker image inspect`
- `docker image rm`
- `docker image build`
- ...

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
docker container run --rm alpine cat /etc/hostname
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

Vous pouvez explorez le système de fichier pour voir ce qui s'y trouve.

## Lister les conteneurs ##

```bash
docker container ls
CONTAINER ID   IMAGE     COMMAND   CREATED   STATUS    PORTS     NAMES
```

Mais pourquoi on ne voit pas les conteneurs d'avant ?

## Lister tous les conteneurs ##

```bash
docker container ls -a
CONTAINER ID    IMAGE       COMMAND              CREATED        STATUS                     PORTS       NAMES
a5b74e24da65    alpine      "cat /etc/hostname"  9 seconds ago  Exited (0) 6 seconds ago               happy_cori
```

L'option `--rm` permet de détruire le conteneur une fois la commande terminé (Sinon, le conteneur reste présent dans l'état *Exited* )

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
| --: | :-- |
| `--name` |  donner un nom au conteneur |
| `-i` | interactif |
| `-t` | forcer l'allocation d'un TTY |
| `--rm` | supprimer le conteneur à la fin de son exécution |
| `-d` | démarrer le conteneur en arrière-plan |

Il en existe beaucoup d'autres : gestion des ressources, environnement d’exécution...

Permet de copier des fichiers entre l'hôte et un conteneur actif.

## Mise en réseau ##

Le conteneur dispose généralement de son propre réseau virtuel.

Docker permet de définir :

* Les liens réseaux entre conteneurs
* Les liens entre le réseau physique de l'hôte et les réseaux virtuels

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

Ce mode est peu utilisé, il peut parfois entrer en collisions avec des réglages réseaux d'entreprise (VPN,...). Il pose aussi des problèmes de sécurité ( suppression de l'isolation réseau).

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

Le port 8080 de la machine hôte est redirigé vers le port 80 du conteneur apache (httpd basé sur un OS alpine).

## Les volumes ##

Un conteneur est "jetable"

* Lorsqu'on détruit un conteneur, on supprime aussi les modifications apportées au système de fichier (dernière couche).
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
  * `docker volume create`
  * `docker volume ls`
  * `docker volume rm`
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
docker inspect docker.io/rok4/dataset:bdortho5m-martinique                                            
[{
  //...
    "Config": {
        //...
        "Volumes": {
          "/pyramids/BDORTHO": {}
        },
        //...
    },
  //...
]
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

Dans certains cas, Docker créé des volumes *"anonymes"*, leur nom est une longue chaîne alphanumérique

## Supprimer un volume ##

```bash
docker volume rm NAME
```

## Supprimer un volume lors de la suppression d'un conteneur ##

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

```bash
docker container run -it -v /chemin/sur/mon/ordi:/data alpine sh
docker container run -it \
--mount type=bind,source=/chemin/sur/mon/ordi,target=/data \
alpine sh
```

## A vous de jouer  maintenant ##

Lors de l'installation , nous avons créé un conteneur pour vérifier que Docker était bien installé

* Observez que le conteneur est bien existant et à l'arrêt
* Si vous avez déjà testé les manipulations d'exemple, vous pouvez recréer un nouveau conteneur hello-world.

## Correction ##

```bash
docker ls 
docker ls -a
```

`docker ls` est un alias de `docker container ls`

La première commande n'affiche rien car le conteneur lancé est déjà arrêté. En effet, l'image `hello-world` lance une commande qui affiche un message et c'est tout. Il faut ajouter l'option `-a` pour afficher également les conteneurs arrêtés.

## Gérer les conteneurs : supprimer ##

* Essayez de supprimer le(s) conteneur(s) déjà créé

## Correction ##

```bash
docker container rm NAME
```

On peut trouver le nom du conteneur à supprimer dans le résultat de la commande `docker container ls -a`. Si aucun nom n'est spécifié lors de la création du conteneur (option `--name`), docker génère un nom aléatoire composé d'un adjectif et d'un nom de personnalité.

On pourrait aussi utiliser l'identifiant du conteneur à la place du nom.

## Gérer les images ##

* Affichez la liste des images
* Créez un nouveau conteneur **hello-world**, notez le temps de chargement de l'image
* Supprimez ensuite la(s) images(s) existantes

## Correction ##

```bash
docker image ls
docker image rm hello-world:latest
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

* Téléchargez la dernière version de l'image **alpine** sur *dockerhub*

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

## Docker tag ##

La commande docker tag permet de gérer les tag de vos images locales, cela est utile pour "renommer" une image afin de l'envoyer sur un repository distant.
Cette commande permet de "nommer" l'image *bce5g99azq58* afin de l'uploader dans le registry du géoportail (Il faut les droits associés)

```bash
docker tag bce5g99azq58 registry.ul.geoportail.rie.gouv.fr/rok4/rok4-builder
```

## Gérer les entrées/sorties ##

* Démarrez un conteneur avec un shell interactif basé sur l'image **alpine**
* Dans un second terminal, observez les conteneurs en cours d’exécution
* Stoppez le conteneur (sans le détruire)
* [Relancez](https://docs.docker.com/reference/cli/docker/container/start/) le conteneur et [rattachez](https://docs.docker.com/reference/cli/docker/container/attach/) vous à son terminal
* Faites le ménage

## Correction ##

```bash
docker run --rm -i -t alpine /bin/sh
/# cat /etc/hostname
```

```bash
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
docker start NAME
docker attach NAME
```

```bash
docker stop NAME 
docker rm NAME 
```

Pour aller plus loin, cherchez des informations sur les options `-d`, `-w`, `-h`, `--rm` et `--name` de la commande `docker container run` et testez ces options.

```bash
docker container run --help
```

## Gérer les volumes "host" ##

* Démarrez un conteneur **alpine** avec un TTY en montant la racine de la machine hôte sur `/host` en mode *volume hote* et en lecture seule dans le conteneur
* Observez le contenu de `/host`
* Depuis un terminal sur votre machine host , ajouter un nouveau fichier dans votre `~/` et observez ce dossier dans votre conteneur *Alpine*

## Correction ##

```bash
docker  run -i -t -v /:/host:ro alpine sh
```

Le fichier `/etc/hostname` contient bien le nom d'hôte du conteneur. Le fichier `/host/etc/hostname` contient le nom d'hôte de la machine hôte. Le dossier `/host/home/ubuntu/` contient votre home (si votre user est bien ubuntu...)

## Gérer les volumes docker ##

* Démarrez un conteneur **alpine** avec un TTY et un **volume docker** sur `/data`
* Créez un fichier dans `/data/` puis supprimez le conteneur
* Démarrez un nouveau conteneur **alpine** avec un TTY et un volume docker avec le même nom sur `/data`
* Observez le contenu de `/data/`
* Listez puis supprimez les volumes

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

* Affichez la liste des interfaces de votre machine hôte puis d'un conteneur avec la commande `ip a`
* Lancez un nouveau conteneur avec l'option `--net host` puis regardez les interfaces

## Correction ## 

```bash
ip a
docker run --rm -it alpine ip a
docker run --rm -it --net host alpine sh
```

L'option `--net host` branche le conteneur sur les interfaces réseaux de la machine hôte. **Il n'y a donc pas d'isolation réseau.**, c'est une option à éviter dans la plupart des cas.

## Exposition de ports ##

<https://hub.docker.com/r/containous/whoami/>

* Démarrez un conteneur basé sur l'image "whoami", inspectez le et trouver l'IP pour tester une requête HTTP sur cette IP.

## Correction ##

```bash
docker container run containous/whoami
docker container inspect NAME
curl -s http://<IP_CONTENEUR>
```

L'IP du conteneur n'est accessible que depuis la machine hébergeant le conteneur. On ne peut pas y accéder depuis l’extérieur de la machine hôte avec son adresse IP car c'est un réseau privé, interne à notre hôte.

<aside class="notes">
  NO_PROXY=10.201.0.3 curl -s http://10.201.0.3
</aside>

## Exposition de ports ##

* Recréez un conteneur en ajoutant cette fois l'exposition du port 80 du conteneur sur le port 8080 de la machine et refaire les tests

## Correction ##

```bash
docker container run -p 8080:80 containous/whoami
curl -s http://127.0.0.1
curl -s http://<IP_HOST>
```

On peut toujours accéder au port 80 de l'IP du conteneur. On peut maintenant également accéder au port 8080 de la machine grâce à l'option `-p` y compris depuis l'exterieur de la machine (ici le LAN ENSG).

## Création de réseaux ##

* Créez un nouveau réseau **test-nginx**
* Utilisez `docker inspect` pour voir ce qui a été créé
* Créez un premier conteneur basé sur l'image `nginx` nommé (`--name`) **nginx** , attaché au réseau **test-ningx** et qui tourne en **daemon**.
* Créer un conteneur basé sur l'image `alpine/curl` sans préciser de réseau , avec un terminal interactif (`/bin/sh`)
  * Essayez d'atteindre le serveur *nginx* précédemment créé
* Créez un nouveau conteneur basé sur `alpine/curl` attaché au réseau **test-nginx** avec un terminal interactif
  * Réessayez de joindre le server *nginx*

```
curl -v "http://[IP|hostname][:PORT]/"
```

## Correction ##

```bash
docker network create test-nginx
docker inspect test-nginx
docker run --network test-nginx --name nginx -d nginx
docker run --rm -ti alpine/curl /bin/sh
# / # curl nginx
#  curl: (6) Could not resolve host: nginx
docker run --rm -ti --network test-nginx alpine/curl /bin/sh
# / # curl nginx
# <!DOCTYPE html>
# ...
```

## Docker : le grand nettoyage ##

Les commandes suivantes vont tenter de nettoyer tout ce qui n'est pas utilisé.
A utiliser avec tact, mais peut être salvateur !

```bash
docker system prune
docker volume prune
```

## Docker context ##

Il est possible de décorréler la CLI du Daemon Docker de votre Machine et ainsi de "piloter" un autre host avec votre CLI.
Les **Contextes** Docker sont fait pour cela.

```bash
docker context create distant --docker "host=ssh://ubuntu@autreStationLinuxavecDocker"
docker contexte use distant
docker ps -a
```

## Docker exec ##

La commande `docker exec` permet de lancer un nouveau processus dans un conteneur actif existant, cette commande est pratique pour lancer un *Shell* d'observation ou de debug par exemple.

```bash
docker exec -ti existingContainer /bin/bash
```

## Docker cp ##

La commande `docker cp`  permet d'échanger des fichiers/dossiers entre le user-space d'un conteneur et celui de votre host.

```
docker run --name alpineContainer -d alpine tail -f /dev/null
docker cp /etc/hostname alpineContainer:/local.hostname
docker exec alpineContainer cat /local.hostname
docker stop alpineContainer
docker cp alpineContainer:/etc/hostname ./alpine.hostname
#  Successfully copied 2.05kB to /home/CEsnault/alpine.hostname
cat alpine.hostname
# 4583aca170a9
docker rm alpineContainer
```

Cette commande fonctionne également lors de l'utilisation d'un contexte distant.

<aside class="notes">

tail -f /dev/null permet au processus principal de perdurer en arrière plan

</aside>

## Autres commandes docker ##

Il existe d'autres commandes docker dont la documentation est disponible sur <https://docs.docker.com/engine/reference/commandline/docker/>

⚠️ Attention, certaines de ces commandes sont propres au mode d'exécution en **cluster swarm**

# TP LAMP #

## Objectif LAMP ##

Le but de ce TP est de mettre en place les éléments nécessaires d'un serveur web de type LAMP.

> - **L**inux
> - **A**pache
> - **M**ySql
> - **P**HP

Les fichiers nécessaires sont disponibles dans le dépôt GIT de ce cours, dans le dossier `ressources` <https://github.com/cedric-esnault-ign/cours_docker.git> . Utilisez git pour récuperer ce dépot et travaillez dans le dossier `ressouces` .  

```
git clone https://github.com/cedric-esnault-ign/cours_docker.git 
cd cours_docker/ressources
```



## Apache ##

L'image à utiliser ici est `httpd`. Les options `--name -d -p -v ` peuvent être utiles. La racine du serveur web dans l'image est `/usr/local/apache2/htdocs/`

- créez un dossier `apache-racine` pour le montage **host**
- Lancez un conteneur avec un montage de la racine du serveur sur votre dossier `apache-racine` et exposant le port *80* du conteneur sur le port *8080* de la machine host.
- Qu'affiche la page <http://127.0.0.1:8080> ?
- Utilisez le fichier `index-lamp.html` présent dans le dossier **cartopoint** pour remplacer la page d’accueil


## Correction ##

```bash
docker run --name web -d -p 8080:80 -v "$(pwd)/apache-racine/:/usr/local/apache2/htdocs/" httpd:latest

cp cartopoint/index-lamp.html apache-racine/index.html
```

## Un peu de php ##

* Remplacez le fichier html par `index-lamp.php` (pensez à le renommer en index.php). qu'observez vous?

## Un peu de php ##

`httpd` est de base un simple serveur web sans fonctionnalité php. Il faudrait ajouter php dans cette image et configurer httpd pour interpréter les fichiers php. Sans cela, httpd cherche uniquement les fichiers `index.html` si aucun fichier n'est précisé dans l'URL.

Même en essayant  `http://127.0.0.1:8080/index.php`, le résultat n'est pas satisfaisant, il n'y a qu'une page verte alors qu'elle devrait afficher l'heure.

* Recréez votre conteneur en utilisant l'image **php:7.4-apache** qui contient l'interpréteur PHP. Attention, au niveau du montage, dans cette image, la racine du serveur Web est maintenant `/var/www/html` et plus `/usr/local/apache2/htdocs/` !

Note : On déroge ici un peu à la règle 1 processus par conteneur. On pourrait séparer apache et PHP, mais la liaison serait plus complexe.

* Rafraichissez plusieurs fois la page et profitez en pour regarder les logs `docker logs <NAME>`

<aside class="notes">

</aside>

## Correction ##

```bash
docker rm -f web
docker run --name web -d -p 8080:80 -v "$(pwd)/apache-racine/:/var/www/html/" php:7.4-apache

cp cartopoint/index-lamp.php apache-racine/index.php

```

## Ajout d'une Base De Données ##

Notre site web évolue ! Il va maintenant afficher une carte. Un clic permet de créer un point, sauvegardé en base de données. Un clic sur un point le supprime. Au chargement de la page, on affiche tous les points de la base de données.

* Utilisez l'image `cedricici/php:7.4-apache-mysql` puis remplacer le fichier `index.php` par le fichier `index-bdd.php`, observez les erreurs

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

</aside>

## Correction ##

```bash
docker rm -f web
docker network create lamp
docker run --net lamp -d --name web -p 8080:80 -v $(pwd)/apache-racine/:/var/www/html/ cedricici/php:7.4-apache-mysql
docker run --net lamp -d --name database -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=mymap -e MARIADB_USER=user -e MARIADB_PASSWORD=s3cr3t  mariadb
docker exec -it database mariadb -u user -D mymap --password="s3cr3t"

```

## Persistence de la Base De Données ##

Détruisez les conteneurs, puis reconstruisez l'ensemble. Les points sont perdus **:-(**
  
* Résolvez ce problème

<aside class="notes">


</aside>

## Correction ##

```bash
docker rm -f database
docker volume prune
docker run -v databasedata:/var/lib/mysql --net lamp -d --name database -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=mymap -e MARIADB_USER=user -e MARIADB_PASSWORD=s3cr3t  mariadb
```


# TP NextCloud #

## Déployer un nextCloud ##

Le but est de mettre en place NextCloud, un gestionnaire de fichier en ligne.
L'image à utiliser est **nextcloud**, disponible sur [https://hub.docker.com/_/nextcloud/](https://hub.docker.com/_/nextcloud/). Cette page contient beaucoup de détails que vous pouvez trouver dans les métadonnées de l'image.

## Première étape ##

un serveur simple avec une base de données SQLite (un fichier)

* Stoppez les éventuels conteneurs en cours (web) (ne détruisez pas les réseaux)
* Lancez un conteneur exposant sur le port 8080 de la machine hôte le serveur nextcloud et rendez-vous sur [http://127.0.0.1:8080](http://127.0.0.1:8080) pour accéder à l'interface d'initialisation du serveur.
* Suivez les étapes d'installation. (base SQLite)
* Utiliser l'application pour générer du contenu (uploadez des fichiers)
* Utilisez la commande `docker exec` pour lister vos fichiers uploadé ( `/var/www/html/data/` )

## Deuxième étape ##

* Ajoutez un volume pour persister les données lorsque le conteneur est détruit (testez)
* Préparez run réseau **nextcloud** pour notre application
* Lancez un conteneur MariaDB ou PostgreSQL avec persistence de données, un nom et utilisant le réseau **nextcloud** et reconstruisez l' application en utilisant cette base de données

<aside class="notes">

docker network create nextcloud
docker run -d --name nextcloud -v nextcloud-data:/var/www/html/data/ --net nextcloud -p 8080:80  nextcloud
docker run -d --name nextcloud-database -v databasedata:/var/lib/mysql --net nextcloud -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=nextcloud -e MARIADB_USER=nextcloud -e MARIADB_PASSWORD=s3cr3t  mariadb

</aside>

## Correction ##

```bash
docker network create nextcloud
docker run -d --name nextcloud -v nextcloud-data:/var/www/html/data/ --net nextcloud -p 8080:80  nextcloud
docker run -d --name nextcloud-database -v databasedata:/var/lib/mysql --net nextcloud -e MARIADB_RANDOM_ROOT_PASSWORD=yes -e MARIADB_DATABASE=nextcloud -e MARIADB_USER=nextcloud -e MARIADB_PASSWORD=s3cr3t  mariadb
```

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

La documentation de référence du docker file est présente ici :

<https://docs.docker.com/engine/reference/builder/>

## Instructions Dockerfile ##

Le **Dockerfile** est constitué d'une suite d'instructions, chaque ligne résultant en une nouvelle **couche** dans l'image.

[Architecture des images](#/architecture-des-images)

Un Dockerfile commence généralement par l'identification de l'image de base. (la seule exception est le passage d'un argument `ARG` permettant de définir dynamiquement l'image de `FROM`)

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
* `COPY` est compatible avec le *Multi-stage*

## Instructions Dockerfile ##

Modifier l'environnement d'exécution

```dockerfile
ENV     #Variable d environnement
USER    #Changement d utilisateur
WORKDIR #Changement du dossier de travail
```

Il est fortement conseillé de ne pas utiliser le user root pour des raisons de sécurité.

<aside class="notes" >

USER root interdit dans un environnement kubernetes sécurisé

</aside>

## Instructions Dockerfile ##

Modifier l’exécution

```dockerfile
CMD    #Commande par défaut
EXPOSE #Déclarer un port réseau
VOLUME #Déclarer un volume
HEALTHCHECK  #Définit une sonde de vie
```

D'autres instructions  son disponible sur <https://docs.docker.com/engine/reference/builder/>

## Avancé : paramétriser le Dockerfile ##

Il est possible de passer des paramètres lors du *build* de l'image.

```dockerfile
ARG <name>[=<default value>]
```

Dans le reste du fichier, on fait référence à cette variable avec `${name:-default_value}`

Pour définir la valeur lors de la construction :

```bash
docker image build --build-arg name=value .
```

Certaines variables sont automatiquement ajouté (Les réglages de Proxy)

## Multistage build ##

Le conteneur de production ne doit contenir que ce qui est nécessaire pour le run, il ne doit rien rester dasn l'image en rapport avec le dev/build.
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

Seul le dernier **FROM** sera contenu dans l'image finale

[Documentation multistage build](https://docs.docker.com/develop/develop-images/multistage-build/)

## Bonnes pratiques de conceptions ##

* un conteneur est éphémère : **utilisation de volumes**
* un conteneur doit être léger : **juste ce qu'il faut**
* un seul processus par conteneur

<aside class="notes" >

En mode cloud natif, on évitera l'utilisation de volume en favorisant les applications stateless.

</aside>

## Bonnes pratiques de conceptions ##

* minimiser le nombre de couche du système de fichiers **en minimisant les commandes RUN et en utilisant beaucoup de `&&`**
* optimiser l'utilisation du cache de build :
  - les commandes qui changent le moins en premiers (`EXPOSE` ...)
  - les commandes ADD plutôt vers la fin

# TP Dockerfile #

## reprise de notre application de cartopoint ##

Nous allons ici simplement créer une nouvelle image pour avoir une moyen de livrer notre application.

* Cette image sera basée sur l'image déjà référencé : `php:7.4-apache` avec le mot clé **FROM** (mais pas `cedricici/php:7.4-apache-mysql` qui est déjà un *build* d'image)
* Installer les drivers mysql pour cette image avec le mot clé **RUN** , l'image `php:7.4-apache` contient une commade pour cela : `docker-php-ext-install mysqli pdo_mysql`
* Copiez le fichier `index-bdd.php` en `index.php` dans le dossier par défaut du serveur web.
* Et c'est tout :D
* Construisez une image nommée **cartopoint:1.0** à partir de ce Dockerfile avec la commande `docker build`
* Nous avons maintenant une image autonome pour ce qui est de la partie statique de notre site Web (les données restent bien entendu dans la base de donnée)
* Testez cette image !

## Correction ##

```Dockerfile
FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo_mysql
COPY index-bdd.php /var/www/html/index.php
```

```bash
docker build -t cartopoint:1.0 .
docker rm -f web
docker run -d --name web --net lamp -p 8080:80 cartopoint:1.0
```

Cela devrait fonctionner si vous n'avez pas détruit la stack LAMP! Sinon, il faudra recréer réseau et base de donnée.

## Une application node.js ##

Cette fois ci, nous allons créer pas à pas une image Docker pour une application **node.js** dont le code de l'application est disponible dans le dossier `ressources\findmefast\`

* Commençons par créer un fichier nommé `Dockerfile`
* Avec l'image Alpine : [node:18-alpine](https://hub.docker.com/layers/library/node/18-alpine/images/sha256-d51f2f5ce2dc7dfcc27fc2aa27a6edc66f6b89825ed4c7249ed0a7298c20a45a?context=explore)
* Il faut ensuite réaliser ces actions :
  * exposer le port `1111`
  * Créer un dossier `/app` à la racine du conteneur
  * Définir l'espace de travail dans ce nouveau dossier
  * Copier le fichier `package.json` dans ce dossier
  * Exécuter la commande `npm install --production` afin d'installer les dépendances
  * Copier les sources (le dossier `/public` et le fichier `server.js` ) dans ce dossier
  * définir la commande par défaut : `npm start`

<aside class="notes">

</aside>

## Une application node.js ##

Et on pourra tester l'application avec la commande `docker run` en mappant un port de votre machine sur le port `1111` du conteneur.

=> Pour pouvoir tester l'application **entre vous** il faudra faire une petite manipulation pour [mapper ce port dans la VM Virtualbox](https://www.it-connect.fr/configurer-le-port-forwarding-sur-une-vm-virtualbox%EF%BB%BF/)

## Correction ##


```Dockerfile
FROM node:18-alpine
EXPOSE 1111
RUN mkdir -p /app
WORKDIR /app
COPY package.json /app/
RUN npm install --production
COPY public /app/public
COPY server.js /app/
CMD ["npm", "start"]
```

```bash
docker build -t findmefast .
docker run -d --name findmefast -p 1111:1111 findmefast
```

## Une compilation C ##

Nous allons maintenant créer une petite image **multistage** pour compiler puis exécuter un petit programme qui calcul **n** n nombre premier (le seul intérêt de ce programme est de solliciter le CPU).
Nous profiterons de ce programme pour bien comprendre la notion de *Kernel* et de *Userspace* dans les conteneurs. Dans un premier temps , nous allons compiler le programme sur notre host.

* Placez vous dans le dossier `ressources/prime`
* Installez `gcc` : `sudo apt-get update && sudo apt-get install gcc`
* Compiler le programme : `gcc prime.c -o prime`
* Mesurez le temps nécessaire pour calculer les 10000 premiers nombres premiers (utilisez `time`, le programme `./prime` prends en argument la quantité de nombre premier à trouver.

## Correction ##

```bash
$ time ./prime 10000
Calcul des 10000 premiers nombres premiers
real    0m1,821s
user    0m1,818s
sys     0m0,002s
```

## Compilation dans un conteneur ##

Créez une nouvelle image que vous nommerez **prime:debian**

* Partir d'une image `debian` dernière version disponible
* Installer les paquets nécessaires pour la compilation
* Ajouter le fichier source
* Compiler le programme
* Définir une commande par défaut (Par exemple `./prime 1`)
* Mesurez le temps nécessaire pur calculer les 10000 premiers nombres premiers avec cette image.

Que pensez vous des résultats obtenus, est-ce normal d'après vous?

## Correction ##

```Dockerfile
FROM debian 
RUN apt-get update && apt-get install -y gcc
COPY prime.c prime.c
RUN gcc prime.c -o prime
CMD [ "./prime","1" ]
```

```bash
$ docker build -t prime:debian .
$ time docker run prime:debian ./prime 10000 
Calcul des 10000 premiers nombres premiers
real    0m2,624s
user    0m0,022s
sys     0m0,018s
```

Le temps dans l'espace utilisateur de notre Hote est nul car toute l'opération se déroule dans l'espace utilisateur du conteneur.

## Amélioration multistage ##

Nous allons améliorer l'image en créant une image multistage

* Nommer le premier `FROM` pour définir un premier *stage*
* Une fois le fichier compilé, créer un nouveau *stage*
* Récupérer le fichier compilé lors du premier *stage*
* Laissez la commande par défaut
* Générez une nouvelle image appelée **prime:multi**

⚠️ n'écrasez pas l'image **prime:debian** ⚠️

* Comparez le poids des deux images
* Comparez les vitesses d’exécution

## Correction ##

```Dockerfile
FROM debian as builder
RUN apt-get update && apt-get install -y gcc
COPY prime.c prime.c
RUN gcc prime.c -o prime
FROM debian:stable-slim as runner
COPY --from=builder prime prime
CMD [ "./prime","1" ]

```

## Correction ##

On peut même aller plus loin et utiliser une image plus petite et se basant sur l'image **scratch** qui ne contient rien. Cela pose tout de même quelques limitation, l'image ne contenant même pas de shell, il n'est pas possible de passer des paramètres.

```Dockerfile
FROM debian as builder
RUN apt-get update && apt-get install -y gcc
COPY prime.c prime.c
RUN gcc prime.c -o prime -static 
FROM scratch as runner
COPY --from=builder prime /prime
CMD ["/prime","10000"]
```

```bash
$ docker build -t prime:scratch  .
$ docker image ls | grep prime
prime  scratch  5759381e4528   2 minutes ago    810kB
prime  multi    f36f1d448847   14 minutes ago   74.8MB
prime  debian   a3c10b1c84ef   33 minutes ago   347MB
```

# TP LibreQR #

## Création du Dockerfile ##

Vous êtes en avance, voici une proposition de Dockerfile à créer en toute autonomie : dockeriser une application **LibreQR**, une application web de génération de QR Code.
Voici les ressources nécéssaires :

* Le code source et la documentation se trouvent là : <https://code.antopie.org/miraty/libreqr>
* basé sur l'image `php:7.4-apache`
* Installez les paquets `libpng-dev` et `unzip`
* Installez le driver GD pour php `docker-php-ext-install gd`
* Penser à rendre le dossier et les fichiers accessibles à l'utilisateur du service httpd `www-data`.
* Utiliser la configuration PHP de production et non de développement (voir la partie "Configuration" de <https://hub.docker.com/_/php> )
* Modifier le fichier de configuration à inclure pour changer le texte affiché en bas de page.

## Correction ##

```Dockerfile
FROM php:7.4-apache
RUN apt-get update && \
    apt-get install -y unzip libpng-dev 
RUN docker-php-ext-install gd
ADD main.zip /main.zip
ADD config.inc.php /var/www/html/
WORKDIR /var/www/html
RUN unzip /main.zip && \
    mv libreqr/* . && \
    chown -R www-data:www-data /var/www/html/
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

```bash
docker image build -t qr .
docker container run -p 8080:80 -e LIBREQR_CUSTOM_TEXT="Générateur de QR code dans docker" qr

```

## Proposez vos projets ##

Nous pouvons voir ensemble les étapes nécessaires à la **Dockerisation** de vos applications.

# Docker-compose #

## Bilan docker ##

- Une commande par conteneurs
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

- *la version du format* (obligatoire, en 2023 : `version: "3"`)
- les **services**
- les **volumes**
- les **réseaux**

## Les versions ##

Le modèle du docker-compose.yml a plusieurs versions possibles.

| Compose file format | Docker Engine |
| :-----------------: | :------------ |
| specification       | 19.03.0+      |
| 3.8                 | 19.03.0+      |
| 3.7                 | 18.06.0+      |
| ...                 | ...           |
| 2.0                 | 1.10.0+       |
| 1.0                 | 1.9.1.+       |

<https://docs.docker.com/compose/compose-file/compose-versioning/>

La dernière version **specification** est devenue un "standard" de manière à ne plus être propre à l'utilisation avec le daemon docker.

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

Utiliser une image est plus sûr. Un pipeline de CI est chargé de construire les images avec une gestion des *Tags* permettant d'avoir des releases connues.
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
     - "80:80" # Bien mettre les guillemets
```

Seuls les services qui seront exposés doivent être connecté au réseau "externe" , les autres services doivent être connectés au même réseau interne pour pouvoir communiquer entres eux.
Il n'est nécéssaire de déclarer que les ports ouvert sur le host. Les conteneurs d'un même réseau ont un accès total aux autre conteneurs de ce réseau.

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
docker compose logs -f 
```

## Commandes ##

Nettoyage des conteneurs stoppés

```bash
docker-compose rm
```

Nettoyage des éléments

```bash
docker-compose down
```

## Documentation ##

`docker-compose help`

ou

[Compose command-line reference](https://docs.docker.com/compose/reference/)

# TP docker-compose #

## On prends les mêmes... ##

Pour ce TP, nous alons tenter de reproduire le TP LAMP en utilisant un docker-compose.yaml

* Écrivez le docker-compose qui va réaliser les mêmes actions. N'oubliez pas  :
  * les volumes
  * les réseaux
  * les variables d'environnements
  * les dépendances
* Lancez la stack en mode démon
* Observez les logs
* Notez le nom des conteneurs créés

note : Il existe des solutions plus "sûr" pour passer des variables d'environnement sensibles (mot de passe)

## Correction ##

```yaml
version: "3"
services:
  web:
    image: cartopoint:1.0
    ports:
      - "8080:80"
    networks:
      - lamp
    depends_on:
      - database
  database:
    image: mariadb
    environment:
      - MARIADB_RANDOM_ROOT_PASSWORD=yes
      - MARIADB_DATABASE=mymap
      - MARIADB_USER=user
      - MARIADB_PASSWORD=s3cr3t
    volumes:
      - databasedata:/var/lib/mysql
    networks:
      - lamp

volumes:
  databasedata:

networks:
  lamp:

```

# Kubernetes #

## Orchestration de conteneurs ##

Quand on parle de **Docker** en 2023, on peut difficillement ne pas évoquer Kubernetes (**K8S**).

**K8S** est une solution d'orchestration de conteneur mis au point par Google et devenue la référence en la matière. On peut résuler **K8S** à un **super compose**, même si il permet beaucoup plus de chose.
Kubernetest a permis d'amener les conteneurs **Docker** en production en apportant le contrôle et la sécurité qui n'était pas au niveau d'un outils créé avant tout pour les développeurs.

**Docker** propose lui aussi son orchestrateur ; **SWARM**, dont la CLI est intégré au client `docker`. Celui -ci n'étant pas au niveau de kubernetes, nous n'en parlerons pas, même si il a eu l'avantage d'être plus simple que K8S il y a quelques années.


<aside class="notes">

curl -sfL https://get.k3s.io | sh -
export KUBECONFIG="/etc/rancher/k3s/k3s.yaml"

kompose pour convertire une application docker-compose en manifests K8S

</aside>

## principe de Kubernetes ##

Une solution d'orchestration de conteneur va permettre de déployer et de maintenir en fonctionnement des conteneurs. Du point de vue de l'utilisateur développeur, **k8s** c'est surtout :

- une API pour contrôler les objets **k8s** (listing, création, suppression etc...)
- un client CLI (kubectl) pour interragir avec cette API

L'API de **k8s** est extensible, c'est à dire qu'il est possible de créer de nouveaux types d'objets kubernetes et ainsi d'ajouter de nouvelles fonctionalitées à vos cluster.

## Concepts de k8s ##

- Les **[manifest](https://kubernetes.io/docs/concepts/overview/working-with-objects/#describing-a-kubernetes-object)** permettent de représenter un objet **k8s** sous la forme d'un fichier *yaml* ou *json*
- Les **[Namespaces](https://kubernetes.io/docs/concepts/overview/working-with-objects/namespaces/)** permettent une certaine isolation au sein d'un même cluster kubernetes (~projets).
- Les **[Noeuds](https://kubernetes.io/docs/concepts/architecture/nodes/)** sont les "machines" qui supportent les conteneurs (**pods**)
- Les **[pods](https://kubernetes.io/docs/concepts/workloads/pods/)** sont les plus simples objets **k8s**, ils peuvent contenir un ou plusieurs conteneurs
- Les **[services](https://kubernetes.io/docs/concepts/services-networking/service/)** représentent l'élement réseau de base dans **k8s**, ils permetent d'exposer un service sur un port par exemple. Ils réalisent la répartition sur les **pods** qui leur sont ratachés. Il existe plusieurs types de services **k8s**. Les **ingress** sont d'autres objets réseaux pour exposer les services du cluster.

## Concepts de k8s ##

- Les **[volumes](https://kubernetes.io/docs/concepts/storage/volumes/)** permettent la persistance des données. C'est un élément à manier avec précaution car la persistence des données en volume est un élément complexe à gérer dans les architectures cloud-natives, on lui préférera du stockage Objet.
- Les **[workload management](https://kubernetes.io/docs/concepts/workloads/controllers/)** permettent d'organiser les **pods** , il en existe de plusieurs types, **deployment**,**statefullset**,**daemonset**,**Jobs**,**Cronjobs** etc...
- Les **[configMaps](https://kubernetes.io/docs/concepts/configuration/configmap/)** permettent de stocker des configurations pour les pods. Il existe aussi la notion de **secret** pour stocker des données plus sensibles (bien qu'ils ne soient pas chiffrés par défaut)

Il y a bien d'autres types d'objets Kubernetes !

## Installation ##

Pour pouvoir tester **k8s**, il va nous falloir installer une mini-distribution kubernetes : [k3s](https://k3s.io/)
Cette distribution permet de tester rapidement kubernetes sur un seul noeud en masquant sa complexité sous-jacente.

```bash
curl -sfL https://get.k3s.io | sh - 
```

Après quelques instant, nous pouvns tester si le cluster (de 1 noeud...) est disponible :

```bash
sudo k3s kubectl get node
```

![](img/k3s-ok.png)

## Application ##



<aside class="notes">


cd kompose/ 
kompose convert -f docker-compose.yml 

docker tag cartopoint:1.0 ghcr.io/cedric-esnault-ign/cartopoint:1.0
docker login ghcr.io -u cedric-esnault-ign 
docker push ghcr.io/cedric-esnault-ign/cartopoint:1.0


sudo k3s kubectl apply -f databasedata-persistentvolumeclaim.yaml
sudo k3s kubectl apply -f database-deployment.yaml
sudo k3s kubectl apply -f lamp-networkpolicy.yaml
sudo k3s kubectl apply -f web-deployment.yaml
sudo k3s kubectl apply -f web-service.yaml 
sudo k3s kubectl apply -f web-deployment.yaml



sudo k3s kubectl inspect 
sudo k3s kubectl inspect pod 
sudo k3s kubectl pods ls
sudo k3s kubectl pods
sudo k3s kubectl get pod
sudo k3s kubectl get pods
sudo k3s kubectl get pod
sudo k3s kubectl inspect  pods
sudo k3s kubectl describe pod/web-f9c8ff8db-kssc8
sudo k3s kubectl get deployments
sudo k3s kubectl describe  deployment/web
sudo k3s kubectl get pods
sudo k3s kubectl get services
sudo k3s kubectl port-forward
sudo k3s kubectl port-forward -h
sudo k3s kubectl port-forward service/truc 9999:80
sudo k3s kubectl get service
sudo k3s kubectl port-forward service/web 9999:8080
sudo k3s kubectl apply -f database-deployment.yaml
sudo k3s kubectl apply -f web-service.yaml 
sudo k3s kubectl port-forward service/web 9999:8080
sudo k3s kubectl get pod
sudo k3s kubectl exec --stdin --tty web-f9c8ff8db-kssc8 -- /bin/bash
sudo k3s kubectl get service
sudo k3s kubectl apply -f web-service.yaml 

sudo k3s kubectl port-forward service/web 9999:8080

</aside>