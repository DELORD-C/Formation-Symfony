# Exercice 5

1. Sur la page `post/edit/{post}` d'un utilisateur, la liste de ses objets `Post` apparait en dessous du formulaire
2. Créer une page `post/{post}` pour consulter un Post
3. Sur la page d'édition et de vue d'un `Post`, l'adresse e-mail de l'auteur apparait
4. Sur la page `post/list`, ajouter un champ recherche qui filtre les objets `Post`
5. Mettre à jour la sécurité pour les nouvelles routes.
6. Ajouter la fonctionnalité des commentaires
   - Les commentaires doivent apparaitre sous le post concerné lors de son affichage
   - N'importe quel utilisateur connecté doit pouvoir laisser un commentaire sur un `Post`
   - Un commentaire peut être supprimé/modifié par son propriétaire et/ou un `ROLE_ADMIN`
   - Lorsqu'un `Post` est supprimé, tous ses commentaires doivent l'être aussi