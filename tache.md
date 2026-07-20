- ETU003916 : Lucas
- ETU003973 : Nathan

# Listes des tâches
## Initialisation 
- Creation du depot git                                             (Nathan)
- Initilialisation du framework CI4                                 (Nathan)
- Configuration de base de donnée :
    - configurer Database.php pour SQLite 3 et la base operateur.db (Lucas)
    - initialiser la base avec spark migrate                        (Lucas)
- Conception de la base de donnée                                   (Lucas + Nathan)

## V1 
### Base
- Creation des migrations et script sql :
    - operateur                                                     (Nathan)
    - config                                                        (Nathan)
    - type_operation                                                (Nathan)
    - frais_operation                                               (Nathan)
    - transaction                                                   (Nathan)
    - client                                                        (Lucas)
- Execution des migrations : migrate                                (Lucas)
- Creation des seeds pour les données de test par defaut            (Lucas)
- Execution des seeds                                               (Lucas)

### coté Client                                          
- creation du model ClientModel                                     (Lucas)

#### Login :                                                        (Lucas)
- creation de AuthClientController :                                 
    - Fonction login() :
        - si session redirect to home
        - sinon redirect login
    - Fonction doLogin() : 
        - validation du numero envoyer : validation general
        - rediriger si invalide
        - get Client :
            - si existe on continue
            - sinon :
                - validation du prefix
                - insertion du nouveau client
        - generation de session et stockage des info
        - redirection vers home
    - Fonction logout() :
        - destruction de session 
        - redirection vers login

- ajout des routes :
    - login (get) -> login()
    - login (post)-> doLogin()
    - logout(get) -> logout()
- configuration du filter et helper:
    - AuthFilter.php : redirection vers login si pas de session
    - Filter.php : ajout de auth associé à AuthFilter
    - configuration de helper
- creation de la vue :
    - formulaire avec champ text pour le numero
    - validation js du numero 
- creation de home associer à ClientController:
    - vue home avec info session
    - route avec filter

#### Operations                                                     (Lucas)
- Voir solde :
    - ClientController : envoyer le solde du client connecté
    - view home.php :
        - affichage du solde (hidden par défaut)
        - bouton "Voir le solde"
        - JS : afficher/masquer le solde

- Faire un dépôt :
    - ClientController : créer doDepot() -> TransactionModel::createDepot()
    - view home.php :
        - bouton "Faire un dépôt"
        - modal Bootstrap
        - formulaire

- Faire un retrait :
    - ClientController : créer doRetrait() -> TransactionModel::createRetrait()
    - view home.php :
        - bouton "Faire un retrait"
        - modal Bootstrap
        - formulaire

- Faire un transfert :
    - ClientController : créer doTransfert() -> TransactionModel::createTransfert()
    - view home.php :
        - bouton "Faire un transfert"
        - modal Bootstrap
        - formulaire

- Voir l'historique :
    - TransactionModel : créer getHistoriqueClient()
    - ClientController : historique()
    - view 
    - view historique.php :
        - tableau Bootstrap

## Côté opérateur
- Création des Migrations                                           (Nathan)
- Création des seeds de données de test                             (Nathan)

### Models - Gestion opérateur                                      (Nathan)
- Création du model OperateurModel
    - Fonction : getValidPrefix($id_operateur)
        - Récupérer les prefixes valides en utilisant join avec la table config
    
    - Fonction : getSituationGain($id_operateur)
        - Somme des frais attribués à l'opérateur

    - Fonction : getSituationsClients()
        - Liste des clients de l'opérateur avec leur solde actuel

- Creation du model ConfigModel
    - Fonction : ajouterPrefixe()

- Creation du model FraisOperationModel
    - Fonction : getAll($nbPage, ?$idTypeOperation)
        - Paginer les résultats avec $nbPage
        - Filtre sur le type d'opération

    - Fonction : getFrais($id_type_operation, $montant)
        - Filtrer sur le type d'opération et le montant

    - Fonction privée : chevaucheTrancheExistante($idTypeOperation, $borneMin, $borneMax, ?int $excludeId = null)
        - Vérifier que les bornes ne se chevauchent pas avec des bornes existantes :
            - $BorneMax > borne_min
            - $BorneMix < borne_max
            - Exclure l'id actuel si ça conçerne la modification d'une tranche

    - Fonction : ajouterBareme($idTypeOperation, $borneMix, $borneMax, $frais)
        - Vérifier que ça ne chevauche pas une tranche
        - insérer
    
    - Fonction : modifierBareme($idTypeOperation, $borneMin, $borneMax, $frais, $idBareme)
        - Vérifier que les nouvelles bornes ne chevauchent pas des tranches
        - Modifier

    - Fonction : supprimerBareme($id)
        - Supprimer le bareme

- Création du model TransactionModel
    - Fonction : createTransaction($idTypeOperation, $numero, $dateHeure, $montant, $numeroDest)
        - Normaliser le numéro
        - Vérifier que le numero du client est enregistré et récupérer son id
        - Valider le montant
        - Récupérer les frais relatifs à l'opération : FraisOperationModel->getFrais
        - Vérifier que le numero du destinataire est enregistré 
            - Sinon, créer une Exception ('Le numéro du destinataire n'a pas encore de compte')
        - Créer la transaction
        - Mettre à jour le solde du client
        - Si destinataire non null -> Mettre à jour le solde du destinataire

    - Fonction : createDepot($numero, $dateHeure, $montant)
        - Récupérer l'id de type_operation pour dépot
        - appeler createTransaction($idTypeOperation, $numero, $dateHeure, $montant, null)

    - Fonction : createRetrait($numero, $dateHeure, $montant)
        - Récupérer l'id de type_operation pour retrait
        - appeler createTransaction($idTypeOperation, $numero, $dateHeure, $montant, null)

    - Fonction : createTransfert($numero, $dateHeure, $montant, $numeroDest)
        - Récupérer l'id de type_opration pour transfert
        - appeler createTransaction($idTypeOperation, $numero, $dateHeure, $montant, $numeroDest)

- Erreur de conception : Suppression de la table operateur - C'est une appli de gestion d'UN operateur 
    - Suppression de la Migration pour Operateur
    - Modification des Migrations Clients et Config
    - Suppression du model OperateurModel
    - Simplification de la table config -> suppression du champ id_operateur

### Controllers et Views                                            (Nathan)

- Création du Controller SituationController
    - Route GET /operateur/situation
    - Fonction : index()
        - Récupérer le gain total : TransactionModel->getSituationGain()
        - Récupérer la liste des clients avec leur solde : ClientModel->getSituationsClients()
        - Afficher la vue operateur/situation

- Création du Controller ConfigController
    - Route GET /operateur/config
    - Fonction : index()
        - Lister les préfixes existants (ConfigModel)
        - Afficher la vue operateur/config (tableau + bouton "Ajouter" -> modal Bootstrap)
    - Route POST /operateur/config
    - Fonction : store()
        - Valider et ajouter un préfixe : ConfigModel->ajouterPrefixe($prefixe)
        - Rediriger avec message de succès/erreur (flashdata)

- Création du Controller FraisOperationController
    - Route GET /operateur/frais
    - Fonction : index()
        - Lister les types d'opération et leurs tranches de frais (FraisOperationModel + TypeOperationModel)
        - Afficher la vue operateur/frais (tableau par type + boutons "Ajouter"/"Modifier"/"Supprimer" -> modals Bootstrap)
    - Route POST /operateur/frais
    - Fonction : store()
        - Valider et ajouter une tranche : FraisOperationModel->ajouterBareme(...)
        - Rediriger avec message de succès/erreur (flashdata), capturer les exceptions (chevauchement)
    - Route POST /operateur/frais/(:num)/update
    - Fonction : update($id)
        - Valider et modifier une tranche : FraisOperationModel->modifierBareme(...)
        - Rediriger avec message de succès/erreur (flashdata), capturer les exceptions
    - Route POST /operateur/frais/(:num)/delete
    - Fonction : delete($id)
        - Supprimer une tranche : FraisOperationModel->supprimerBareme($id)
        - Rediriger avec message de succès/erreur (flashdata)

- Views (app/Views/operateur/)
    - layout Bootstrap partagé (navbar + flashdata success/error)
    - situation.php : gain total + tableau des clients et soldes
    - config.php : liste des préfixes existants + modal "Ajouter un préfixe"
    - frais.php : tableau des tranches par type d'opération + modals "Ajouter"/"Modifier"/"Supprimer" une tranche

- Ajout de pagination sur la page situation des comptes clients
    - Modification de ClientModel -> ajout paginate dans getSituationsClients()
    - Ajout du pager dans SituationController
    - Ajout des blocs de navigation de page dans la view


## V2
### Côté opérateur
- Recréer la table Operateur avec les champs :
    - nom TEXT
    - autre_operateur boolean
    - pct_commission
- Revoir le Model pour Config
- Revoir le Controller et la view rattachée à Config pour ajouter l'opérateur (le nom uniquement dans la liste)

- Créer OperateurController
    - getAll()
    - modify()
    - delete()

- View Operateur pour gérer la liste des opérateurs et le pourcentage de commission par opérateur autre que soi

- Mettre FK_id_operateur dans :
    - config

- Ajout de la fonction : getOperateurByPrefixe($numero)
    - Récupère l'opérateur lié à un préfixe

- Ajouter la colonne commission dans Transaction

- Dans TransactionModel :
    - Retravailler la fonction getSituationGains : retourne array au lieu de float 
        - Filtre possible sur type d'opération
        - Séparation opérateur et autres opérateurs
            - récupérer le préfixe des numéros destinataires
            - récupérer les opérateurs concernés à partir de ces préfixes
            - séparer les gains par opérateurs
            - toujours récupérer le total

    - Fonction : getMontantsAEnvoyerAutresOperateurs() : array
        - Filtrer les transactions sur les opérations de transferts
        - Séparer les opérations par opérateurs autres que soi
        - Filtrer les transactions sur les opérateurs autres que soi
        - Récupérer le total des montants transférés + commissions

- Modification de SituationController pour la séparation opérateur/autres

- Nouveau Controller : ReportController pour gérer les montants à envoyer aux autres opérateurs
    - fonction index()
        - Récupère les montants à envoyer aux autres opérateurs (montant à transférer + commissions)