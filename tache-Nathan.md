## Côté opérateur
- Création des Migrations                                           (Nathan)
- Création des seeds de données de test                             (Nathan)

### Gestion opérateur
- Création du model OperateurModel
    - Fonction : getValidPrefix($id_operateur)
        - Récupérer les prefixes valides en utilisant join avec la table config
    
    - Fonction : getSituationGain($id_operateur)
        - Somme des frais attribués à l'opérateur

    - Fonction : getSituationsClients()
        - Liste des clients de l'opérateur avec leur solde actuel

- Creation du model FraisOperationModel
    - Fonction : getFrais($id_type_operation, $montant)
        - Filtrer sur le type d'opération et le montant

- Création du model TransactionModel
    - Fonction : createTransaction($idTypeOperation, $numero, $dateHeure, $montant, $numeroDest)
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
