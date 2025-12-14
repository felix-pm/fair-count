# Aides extérieurs :
## script 1 : aides nous à comprendre le fonctionnement du manager reimbursementManager.php dans mon projet faircount
C'est un excellent exercice de logique "Tricount" (ou Fair Count). Pour réaliser cela correctement avec ta structure de base de données, il faut procéder en deux étapes principales :

Calculer la "Balance" de chaque utilisateur : (Ce qu'il a payé moins ce qu'il a consommé).

Algorithme de remboursement : Minimiser les transactions pour remettre tout le monde à zéro.

Étant donné que tu as une table expense_participants, le calcul est plus précis que simplement "diviser par le nombre de personnes du groupe". Le coût d'une dépense est divisé uniquement entre les participants de cette dépense spécifique.

## script 2 (Gemini):  comment tu ferais pour éviter que dans ce tableau on ait des valeurs différentes d'un entier et qu'on ait des doublons de dedans? 

$participantsIds = 
[
$currentUserId, 
$_POST["participant_1"],
$_POST["participant_2"],
$_POST["participant_3"],
$_POST["participant_4"]
]

Correction: dans UserController fonction create_group():

$participantsIds = [
                $currentUserId, 
                (int) ($_POST["participant_1"] ?? 0),
                (int) ($_POST["participant_2"] ?? 0),
                (int) ($_POST["participant_3"] ?? 0),
                (int) ($_POST["participant_4"] ?? 0),
                ];

                $finalarrays=array_unique(array_filter($participantsIds));

## script 3 (Gemini):  comment tu fais pour récupérer l'id d'une ligne d'une table qui vient d'être créée en php? 

Résultat: $newExpenseId = (int)$this->db->lastInsertId(); dans la fonction create_expense de Expense_manager