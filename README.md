# Documentation des Fonctions PHP **Assurances Saint Gabriel**

## Table des Matières
- [1. Connexion à la Base de Données](#connexion-à-la-base-de-données-getdatabaseconnection)
- [2. Fonction de Connexion](#fonction-de-connexion-loginemail-password)
- [3. Fonction d'Inscription](#fonction-dinscription-registerfirstname-lastname-email-phone-address-password)
- [4. Fonction de Déconnexion](#fonction-de-déconnexion-disconnect)
- [5. Fonctions Utilitaires pour les Informations Utilisateur](#fonctions-utilitaires-pour-les-informations-utilisateur)
- [6. Gestion des Actualités](#gestion-des-actualités)
- [7. Téléchargement d'Image](#téléchargement-dimage-uploadimagefile)
- [8. Gestion des Simulations](#gestion-des-simulations)
- [9. Gestion des Contrats](#gestion-des-contrats)
- [10. Gestion des Utilisateurs](#gestion-des-utilisateurs)
- [11. Journalisation](#journalisation-logactionuserid-action)
- [12. Fonctions Administratives](#fonctions-administratives)
- [Nouvelles Fonctions Qui Pourraient Être Utiles](#nouvelles-fonctions-qui-pourraient-être-utiles)
- [Documentation de la Base de Données](#documentation-de-la-base-de-données-assurancessaintgabsql)

---

## 1. Connexion à la Base de Données : *getDatabaseConnection()* {#connexion-à-la-base-de-données-getdatabaseconnection}
Cette fonction établit une connexion à la base de données MySQL.

- **Paramètres** : Aucun.
- **Retourne** : Un objet *PDO* représentant la connexion à la base de données.
- **Exceptions** : Lance une *PDOException* si la connexion échoue.

## 2. Fonction de Connexion : *login($email, $password)* {#fonction-de-connexion-loginemail-password}
Cette fonction gère la connexion de l'utilisateur.

- **Paramètres** :
  - *$email* : Adresse e-mail de l'utilisateur.
  - *$password* : Mot de passe de l'utilisateur (en texte clair).

- **Retourne** : Un tableau indiquant le succès ou l'échec de la tentative de connexion, ainsi qu'un message d'erreur en cas d'échec.
- **Logs** : Enregistre un événement de connexion réussie.
- **Redirections** : Redirige l'utilisateur vers sa page de compte si la connexion est réussie.

## 3. Fonction d'Inscription : *register($firstName, $lastName, $email, $phone, $address, $password)* {#fonction-dinscription-registerfirstname-lastname-email-phone-address-password}
Cette fonction gère l'inscription des utilisateurs.

- **Paramètres** :
  - *$firstName*, *$lastName* : Prénom et nom de famille de l'utilisateur.
  - *$email*, *$phone*, *$address* : Informations de contact.
  - *$password* : Mot de passe de l'utilisateur (en texte clair).

- **Retourne** : Un tableau indiquant le succès ou l'échec de l'inscription.
- **Logs** : Enregistre une inscription réussie.
- **Redirections** : Redirige l'utilisateur vers sa page de compte si l'inscription est réussie.

## 4. Fonction de Déconnexion : *disconnect()* {#fonction-de-déconnexion-disconnect}
Cette fonction déconnecte l'utilisateur actuel.

- **Paramètres** : Aucun.
- **Retourne** : Aucun.
- **Logs** : Enregistre une déconnexion réussie.
- **Redirections** : Redirige l'utilisateur vers la page d'accueil.

## 5. Fonctions Utilitaires pour les Informations Utilisateur {#fonctions-utilitaires-pour-les-informations-utilisateur}
- ***getCurrentUserID()*** : Récupère l'ID de l'utilisateur actuel à partir de la session.
- ***getCurrentUserRole()*** : Récupère le rôle de l'utilisateur.
- ***getCurrentUserInfo($field)*** : Récupère des informations spécifiques sur l'utilisateur à partir de la base de données.
- ***isUserLoggedIn()*** : Vérifie si l'utilisateur est connecté.

## 6. Gestion des Actualités {#gestion-des-actualités}
- ***createNews($date, $title, $caption, $keywords, $image)*** : Crée une nouvelle entrée d'actualité.
  - **Paramètres** : Date de l'actualité, titre, légende, mots-clés et image.
  - **Logs** : Enregistre la création de l'actualité.

## 7. Téléchargement d'Image : *uploadImage($file)* {#téléchargement-dimage-uploadimagefile}
Cette fonction gère le téléchargement d'images.

- **Paramètres** : *$file* - le fichier téléchargé.
- **Retourne** : Le nouveau nom de fichier si le téléchargement est réussi.
- **Exceptions** : Lance une *Exception* si le fichier n'est pas valide ou si le téléchargement échoue.
- **Logs** : Enregistre l'événement de téléchargement d'image.

## 8. Gestion des Simulations {#gestion-des-simulations}
- ***createSimulation($userId, $typesAssurance, $informations)*** : Crée une nouvelle simulation d'assurance.
  - **Paramètres** : ID de l'utilisateur, types d'assurance, et autres détails.
  - **Logs** : Enregistre la création d'une nouvelle simulation.

- ***getUserSimulations($userId)*** : Récupère toutes les simulations pour un utilisateur spécifique.

- ***updateSimulationStatus($simulationId, $statut, $reponse = null)*** : Met à jour le statut d'une simulation.
  - **Paramètres** : ID de la simulation, nouveau statut, et réponse optionnelle.

## 9. Gestion des Contrats {#gestion-des-contrats}
- ***createContractFromSimulation($simulationId)*** : Crée un contrat à partir d'une simulation acceptée.
  - **Paramètres** : ID de la simulation.
  - **Logs** : Enregistre la création d'un contrat.

- ***getUserContracts($userId)*** : Récupère tous les contrats pour un utilisateur.

## 10. Gestion des Utilisateurs {#gestion-des-utilisateurs}
- ***getAllUsers()*** : Récupère une liste de tous les utilisateurs du système.
- ***updateUser($userId, $firstName, $lastName, $email, $role)*** : Met à jour les informations de l'utilisateur.

## 11. Journalisation : *logAction($userId, $action)* {#journalisation-logactionuserid-action}
Cette fonction journalise les actions effectuées par les utilisateurs.

- **Paramètres** : ID de l'utilisateur et description de l'action.
- **Retourne** : Booléen indiquant le succès de l'entrée du journal.

## 12. Fonctions Administratives {#fonctions-administratives}
- ***getAllSimulations()*** : Récupère toutes les simulations (utilisation admin).
- ***getAllContracts()*** : Récupère tous les contrats (utilisation admin).
- ***getLogs($userId = null, $startDate = null, $endDate = null)*** : Récupère les journaux d'activités des utilisateurs filtrés par ID utilisateur ou date.

## Nouvelles Fonctions Qui Pourraient Être Utiles
1. ***deleteUser($userId)*** : Supprime un utilisateur par son ID.
   - **Paramètres** : *$userId*.
   - **Logs** : Enregistre la suppression d'un utilisateur.

2. ***archiveContract($contractId)*** : Archive un contrat au lieu de le supprimer définitivement.
   - **Paramètres** : *$contractId*.
   - **Logs** : Enregistre l'archivage d'un contrat.

3. ***sendEmailNotification($email, $subject, $message)*** : Envoie une notification par e-mail à un utilisateur.
   - **Paramètres** : *$email*, *$subject*, *$message*.

4. ***resetUserPassword($email)*** : Génère un lien de réinitialisation de mot de passe et l'envoie à l'utilisateur.
   - **Paramètres** : *$email*.
   - **Logs** : Enregistre la demande de réinitialisation de mot de passe.

5. ***getActiveUsers()*** : Récupère une liste des utilisateurs actuellement actifs.
   - **Retourne** : Tableau des utilisateurs connectés.

6. ***deleteNews($newsId)*** : Supprime une entrée d'actualité par son ID.
   - **Paramètres** : *$newsId*.
   - **Logs** : Enregistre la suppression de l'entrée d'actualité.

## Documentation de la Base de Données (*assurancessaintgab.sql*) {#documentation-de-la-base-de-données-assurancessaintgab.sql}
La base de données *assurancessaintgab* contient plusieurs tables utilisées pour gérer les utilisateurs, les simulations, les contrats, les actualités et la journalisation des actions. Voici une description de chaque table et de son rôle :

### 1. **Table **users** {#table-users}
- **Description** : Contient les informations des utilisateurs enregistrés sur le site.
- **Champs principaux** :
  - *id* (INT, PRIMARY KEY, AUTO_INCREMENT) : Identifiant unique de l'utilisateur.
  - *first_name* (VARCHAR) : Prénom de l'utilisateur.
  - *last_name* (VARCHAR) : Nom de famille de l'utilisateur.
  - *email* (VARCHAR, UNIQUE) : Adresse e-mail de l'utilisateur.
  - *phone* (VARCHAR) : Numéro de téléphone.
  - *address* (TEXT) : Adresse de l'utilisateur.
  - *password* (VARCHAR) : Mot de passe haché de l'utilisateur.
  - *role* (INT) : Rôle de l'utilisateur (1 = utilisateur standard, 2 = administrateur).

### 2. **Table **simulations** {#table-simulations}
- **Description** : Stocke les simulations d'assurance créées par les utilisateurs.
- **Champs principaux** :
  - *id* (INT, PRIMARY KEY, AUTO_INCREMENT) : Identifiant unique de la simulation.
  - *user_id* (INT, FOREIGN KEY) : Identifiant de l'utilisateur ayant créé la simulation.
  - *types_assurance* (JSON) : Types d'assurance demandés dans la simulation.
  - *informations* (JSON) : Détails supplémentaires sur la simulation.
  - *statut* (VARCHAR) : Statut actuel de la simulation (par exemple : En attente, Répondu, Acceptée).
  - *date_creation* (DATETIME) : Date de création de la simulation.

### 3. **Table **contrats** {#table-contrats}
- **Description** : Contient les informations sur les contrats créés à partir des simulations acceptées.
- **Champs principaux** :
  - *id* (INT, PRIMARY KEY, AUTO_INCREMENT) : Identifiant unique du contrat.
  - *user_id* (INT, FOREIGN KEY) : Identifiant de l'utilisateur lié au contrat.
  - *simulation_id* (INT, FOREIGN KEY) : Identifiant de la simulation utilisée pour créer le contrat.
  - *types_assurance* (JSON) : Types d'assurance inclus dans le contrat.
  - *informations* (JSON) : Détails supplémentaires sur le contrat.
  - *date_creation* (DATETIME) : Date de création du contrat.

### 4. **Table **news** {#table-news}
- **Description** : Stocke les actualités publiées sur le site.
- **Champs principaux** :
  - *id* (INT, PRIMARY KEY, AUTO_INCREMENT) : Identifiant unique de l'actualité.
  - *date* (DATE) : Date de publication de l'actualité.
  - *title* (VARCHAR) : Titre de l'actualité.
  - *caption* (TEXT) : Description courte de l'actualité.
  - *keywords* (TEXT) : Mots-clés associés à l'actualité pour le référencement.
  - *image* (VARCHAR) : Chemin vers l'image associée à l'actualité.

### 5. **Table **logs** {#table-logs}
- **Description** : Journalise les actions des utilisateurs sur le site.
- **Champs principaux** :
  - *id* (INT, PRIMARY KEY, AUTO_INCREMENT) : Identifiant unique du log.
  - *user_id* (INT, FOREIGN KEY) : Identifiant de l'utilisateur ayant effectué l'action.
  - *action* (TEXT) : Description de l'action effectuée.
  - *ip_address* (VARCHAR) : Adresse IP de l'utilisateur lors de l'action.
  - *timestamp* (DATETIME) : Date et heure de l'action.

### 6. **Table **uploads** {#table-uploads}
- **Description** : Contient les fichiers téléchargés par les utilisateurs, principalement des images.
- **Champs principaux** :
  - *id* (INT, PRIMARY KEY, AUTO_INCREMENT) : Identifiant unique du fichier téléchargé.
  - *user_id* (INT, FOREIGN KEY) : Identifiant de l'utilisateur ayant téléchargé le fichier.
  - *file_name* (VARCHAR) : Nom du fichier téléchargé.
  - *file_path* (VARCHAR) : Chemin vers l'emplacement du fichier sur le serveur.
  - *upload_date* (DATETIME) : Date de téléchargement du fichier.

---

Pour toute question ou suggestion concernant cette documentation, n'hésitez pas à nous contacter ou à soumettre une *issue* sur GitHub.

