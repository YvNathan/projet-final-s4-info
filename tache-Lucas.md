### coté Client                                          
- creation du model ClientModel

#### Login :
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

#### Operations
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

## V2
### coté Client
- Inclure frais de retrait lors de l'envoie :
    - FraisOperationModel :
        - getFrais($idTypeOperation, $montant, $numeroDest = null)
            - si autre opérateur => 0
            - sinon récupérer frais retrait
            - si même opérateur => frais transfert normal
            - sinon appliquer commission inter-opérateur
        - ajouter la fonction getCommission($montant, $numeroDest) dispo seulement pour trasfert :
            - creer une fonction est_autre_operateur($numeor) verfication regex dans  
            - si $numeroDEst est un autre operateur : commission calculer d'apres le pourcentage dans la table operateur
            - sinon 0

    - creation ApiFraisController  qui expose les fonctions de frais :
        - fonction getFrais()
        - fonction getComission()
    
    - routage des api
    - vue home.php :
        - amelioration des modals, afficher les frais necessaire selon le montant afficher 
        - ajout d'un checkbox d'inclusion de frais
        - afficher :
            - frais, commission et montant total
    - modification de la fonction doTransfert() de Client controller :
        - Verifier si frais inclus :
            - si oui : montant = montant + fraisRetrait(montant)
            - sinon rien
         
    