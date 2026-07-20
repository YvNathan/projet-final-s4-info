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
                - insertion du nouveu client
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
- creation de home :
    - vue home avec info session
    - route avec filter

#### Operations
- Voir solde :
    - dans le homeController 
