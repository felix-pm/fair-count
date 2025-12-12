# Aides extérieurs :
## script 1 : aides nous à comprendre le fonctionnement du manager reimbursementManager.php dans mon projet faircount
C'est un excellent exercice de logique "Tricount" (ou Fair Count). Pour réaliser cela correctement avec ta structure de base de données, il faut procéder en deux étapes principales :

Calculer la "Balance" de chaque utilisateur : (Ce qu'il a payé moins ce qu'il a consommé).

Algorithme de remboursement : Minimiser les transactions pour remettre tout le monde à zéro.

Étant donné que tu as une table expense_participants, le calcul est plus précis que simplement "diviser par le nombre de personnes du groupe". Le coût d'une dépense est divisé uniquement entre les participants de cette dépense spécifique.