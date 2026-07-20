### coté Client                                          
- creation du model ClientModel

#### Login :
- creation de AuthClientController :
    - Fonction login() :
        - si session redirect to home
        - sinon redirect login
    - Fonction doLogin() : 
        - validation du numero envoyer 
        - rediriger si invalide
        - get Client, si inexsitant utiliser register
        - generation de session et stockage des info
        - redirection vers home
    - Fonction logout() :
        - destruction de session 
        - redirection vers login

    - Fonction register() :
        - verifier si le numero 

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
