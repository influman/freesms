# Installation
Gestion des Notifications SMS via API Free Mobile
    
  
### Ajout du périphérique
Cliquez sur "Configuration" / "Ajouter ou supprimer un périphérique" / "Store eedomus" / "Notifications SMS FreeMobile" / "Créer"  

  
*Voici les différents champs à renseigner:*

* [Obligatoire] - Le login de votre compte FreeMobile  
* [Obligatoire] - La clé API SMS FreeMobile associé à votre numéro de téléphone (voir vos options de compte)  

  
### Utilisation
Prédéfinissez des notifications dans les valeurs du périphérique créé par le plugin.  
Respectez bien la forme des valeurs et l'url du script appelé, à l'instar des valeurs par défaut fournies à la création.  
*NB1 : Ne supprimez pas la valeur cachée 9999 - [CHATBOT], dédiée au plugin du même nom.*  
*NB2 : Ne supprimez pas la valeur cachée 99999 - [ASK], dédiée au plugin du même nom.*  
  
Vous pouvez intégrer la valeur d'un ou plusieurs périphériques existants via son code API entre crochet, exemple : [123456].  
Vous pouvez sauter une ligne dans le message en utilisant [BR].  
Insérer la date [DATE] ou l'heure [TIME].  
  
Lancez ensuite vos notifications depuis vos règles.  

