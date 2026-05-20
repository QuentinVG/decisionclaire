# Sécurité et données

DécisionClaire ne connecte aucun compte bancaire, ne récupère aucune donnée externe et ne stocke aucune donnée bancaire.

Mesures présentes :

- CSRF Laravel sur les formulaires ;
- validation stricte via Form Requests ;
- échappement Blade par défaut ;
- policies sur les simulations sauvegardées ;
- suppression possible des simulations ;
- export PDF réservé au propriétaire de la simulation ;
- aucune clé secrète dans le dépôt ;
- compte utilisateur optionnel.

Les données financières saisies sont déclaratives et servent uniquement au calcul indicatif.
