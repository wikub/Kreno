#language: fr
Fonctionnalité: Connexion à l'application

Contexte:
    Etant donné que l'utilisateur "john1@doe.fr" est enregistré avec le mot de passe "0000"

Scénario: Connexion
    Etant donné que je suis sur la page de Connexion
    Lorsque je me connecte en tant que "john1@doe.fr" avec le mot de passe "0000"
    Alors je dois être sur ma page d'accueil
