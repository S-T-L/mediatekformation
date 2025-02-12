# Mediatekformation
## Présentation
Ce site, développé avec Symfony 6.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br> 
Actuellement, seule la partie front office a été développée. Elle contient les fonctionnalités globales suivantes :<br>
![img1](https://github.com/user-attachments/assets/9c5c503b-738d-40cf-ba53-36ba4c0209e8)

## Dépot d'origine : https://github.com/CNED-SLAM/mediatekformation
Ce dépôt contient la présentation complète de l'application d'origine.

## Fonctionnalités ajoutées :

## Back- office : 
- Interface accessible uniquement après authentification aux administrateurs
![Capture d'écran 2025-02-12 161028](https://github.com/user-attachments/assets/4c194832-d430-4bb6-a651-9089ba28f2cb)
- Page de gestion des formations : Ajout / Modification / Suppression 
![Capture d'écran 2025-02-12 161515](https://github.com/user-attachments/assets/0b57bd79-dc7f-4df5-b048-127fec7fee21)
- Page de gestion des playlists : Ajout / Modification / Suppression si aucune formation n'est rattachée à elle
![Capture d'écran 2025-02-12 161610](https://github.com/user-attachments/assets/c1285dfd-5fb8-40b7-abad-275b41862c3e)
- Page de gestion des catégories : Ajout et suppression si aucune formation n'est rattachée à cette catégorie
Des tris et filtres identiques au front-office ont également été ajoutés.

## Amélioration du Front-office :
- Ajout d'une colonne pour afficher le nombre de formations par playlists
![Capture d'écran 2025-02-12 161937](https://github.com/user-attachments/assets/e489514b-0e09-47fd-86fc-a7b4adf0c60e)
- Possibilité de trier les playlists par nombre de formations (ordre croissant/décroissant)
- Affichage du nombre total de formations dans la page de détail des playlists.
![Capture d'écran 2025-02-12 162231](https://github.com/user-attachments/assets/889e494e-1b6f-4940-b6b2-c5875e61c071)

## La base de données
La base de données exploitée par le site est au format MySQL.
### Schéma conceptuel de données
Voici le schéma correspondant à la BDD.<br>
![img7](https://github.com/user-attachments/assets/f3eca694-bf96-4f6f-811e-9d11a7925e9e)
<br>video_id contient le code YouTube de la vidéo, qui permet ensuite de lancer la vidéo à l'adresse suivante :<br>
https://www.youtube.com/embed/<<<video_id>>>
### Relations issues du schéma
<code><strong>formation (id, published_at, title, video_id, description, playlist_id)</strong>
id : clé primaire
playlist_id : clé étrangère en ref. à id de playlist
<strong>playlist (id, name, description)</strong>
id : clé primaire
<strong>categorie (id, name)</strong>
id : clé primaire
<strong>formation_categorie (id_formation, id_categorie)</strong>
id_formation, id_categorie : clé primaire
id_formation : clé étrangère en ref. à id de formation
id_categorie : clé étrangère en ref. à id de categorie</code>

Remarques : 
Les clés primaires des entités sont en auto-incrémentation.<br>
Le chemin des images (des 2 tailles) n'est pas mémorisé dans la BDD car il peut être fabriqué de la façon suivante :<br>
"https://i.ytimg.com/vi/" suivi de, soit "/default.jpg" (pour la miniature), soit "/hqdefault.jpg" (pour l'image plus grande de la page d'accueil).
## Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>

Lien pour tester l'application en ligne : 
https://mediatekformation.qabox.fr/
