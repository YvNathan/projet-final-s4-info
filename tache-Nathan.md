## Côté opérateur
- Création des Migrations                                           (Nathan)
- Création des seeds de données de test                             (Nathan)

### Models - Gestion opérateur
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

### Controllers et Views

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