# Exercice 2

Contrôle d'accès

1. Créer un CRUD pour les objets Post
(Fournir automatiquement un User en guise d'autheur lors de la création)
2. Supprimer l'entité User & son Répertoire
3. Créer l'entité user via make (doc) 
4. Créer un Formulaire d'inscription (Droits par défaut)
5. Créer un formulaire de Connexion
6. Créer un bouton déconnexion
7. Créer un utilisateur avec le rôle ROLE_ADMIN
8. Arriver aux résultats suivants :
   - Lorsque qu'on est déconnecté, on a accès qu'aux formulaires d'inscription et connexion
   - Lorsqu'un utilisateur par défaut est connecté, il a accès à tous sauf les routes /user
   - Les ROLE_ADMIN ont accès à tout