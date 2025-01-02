# Fonctionnalités pour interface

#Page d'accueil
- page de connexion -> lien a
- page d'inscription -> lien a
- logo -> lien a vers page index.html
- fonction pour récupérer les livres de la base de données --> paramètre : rien --> return : string (JSON)
- fonction pour chercher des livres --> paramètre : chaine de caractère --> return : string (JSON)


#Page de connexion :
- fonction d'envoi 
- fonction vérification de l'email : paramètre : string, retourne null ou tableau associatif (voir code Cédrine -114 Database.php) 
- Vérification du mot de passe
- redirection lien a :page d'inscription
- redirection lien a : index + message $machin

JS : 
- Fonction afficher mot de passe

#Page d'inscription
- fonction d'envoi
- fonction ajouter personne dans base de donnée paramètre : Objet personne return : bool
- fonction pour vérifier que la personne n'existe pas encore dans la DB / paramètre : string, string, return bool
- fonction envoi de mail avec token pour validation/ paramètre : string, string /  return bool
- redirection lien a : page "allez checker vos mails machins"
- redirection lien a : page "confirmation d'inscription"

JS : 
- Fonction afficher mot de passe
- Fonction vérifier que les deux mots de passes sont identiques

#Fichier variables de sessions

#Page d'accueil privée (connecté)
- Bouton déconnexion lien a
- Fonction pour afficher les livres --> identique à celle de l'index.php
- Page de compte : lien a : vers compte
- Page bibliothèque : lien a : vers la bibliothèque

#Page du compte privée (compte)
2 formulaires sur la même page, utiliser des eventlisteners pour savoir si on clique sur le bouton pour afficher un des formulaires
- Fonction changer mot de passe :  string (email), string (nv password), return bool
- Fonction supprimer le compte : paramètre : string, string, return bool (déconnexion + formulaire pour envoyer à la DB de supprimer aec les paramètres)
supprimer compte : event listener -> afficher popup "Indiquez votre adresse mail et mot de passe" -> qui est un formulaire HTML, qui s'affiche uniquement quand on clique sur le bouton "supprimer compte" -> avec un bouton type submit, et l'autre qui ferme le popup quand tu cliques

#Ma bibliothèque
- Fonction afficher livre en fct de la catégorie : paramètre : string , return : string (JSON) --> utiliser fetch pour en faire un objet
