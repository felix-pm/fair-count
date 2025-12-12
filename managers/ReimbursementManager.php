<?php

class ReimbursementManager
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function getReimbursementPlan(int $groupId): array
    {
        // 1. Récupérer les balances (Combien chaque personne a payé vs consommé)
        $balances = $this->calculateBalances($groupId);

        // 2. Calculer les transactions optimisées
        return $this->calculateTransactions($balances);
    }

    /**
     * Calcule la balance financière de chaque membre du groupe.
     * Positif = On lui doit de l'argent.
     * Négatif = Il doit de l'argent.
     */
    private function calculateBalances(int $groupId): array
    {
        $balances = [];

        // --- ÉTAPE A : Initialiser tous les membres du groupe à 0 ---
        $sqlMembers = "SELECT user_id FROM group_users WHERE group_id = :groupId";
        $stmtMembers = $this->pdo->prepare($sqlMembers);
        $stmtMembers->execute(['groupId' => $groupId]);
        $members = $stmtMembers->fetchAll(PDO::FETCH_COLUMN);

        foreach ($members as $userId) {
            $balances[$userId] = 0.0;
        }

        // --- ÉTAPE B : Traiter les DÉPENSES (Expenses) ---
        // On récupère toutes les dépenses du groupe
        $sqlExpenses = "SELECT id, amount, user_id as payer_id FROM expenses WHERE group_id = :groupId";
        $stmtExpenses = $this->pdo->prepare($sqlExpenses);
        $stmtExpenses->execute(['groupId' => $groupId]);
        $expenses = $stmtExpenses->fetchAll(PDO::FETCH_ASSOC);

        foreach ($expenses as $expense) {
            $amount = (float)$expense['amount'];
            $payerId = $expense['payer_id'];
            $expenseId = $expense['id'];

            // 1. Celui qui a payé est "Crédité" du montant total (il a avancé l'argent)
            if (isset($balances[$payerId])) {
                $balances[$payerId] += $amount;
            }

            // 2. On cherche les participants pour cette dépense spécifique
            // (Ceux qui doivent rembourser une part)
            $sqlParticipants = "SELECT user_id FROM expense_participants WHERE expense_id = :expenseId";
            $stmtPart = $this->pdo->prepare($sqlParticipants);
            $stmtPart->execute(['expenseId' => $expenseId]);
            $participants = $stmtPart->fetchAll(PDO::FETCH_COLUMN);

            $numberOfParticipants = count($participants);

            if ($numberOfParticipants > 0) {
                $share = $amount / $numberOfParticipants;

                // Chaque participant "doit" sa part (on soustrait de sa balance)
                foreach ($participants as $participantId) {
                    if (isset($balances[$participantId])) {
                        $balances[$participantId] -= $share;
                    }
                }
            }
        }

        // --- ÉTAPE C : Traiter les REMBOURSEMENTS DÉJÀ EFFECTUÉS ---
        // Si Pierre a déjà rendu 10€ à Félix, il faut le prendre en compte.
        $sqlReimbursements = "SELECT amount, from_user_id, to_user_id FROM reimbursements WHERE group_id = :groupId";
        $stmtReimb = $this->pdo->prepare($sqlReimbursements);
        $stmtReimb->execute(['groupId' => $groupId]);
        $existingReimbursements = $stmtReimb->fetchAll(PDO::FETCH_ASSOC);

        foreach ($existingReimbursements as $reimbursement) {
            $amount = (float)$reimbursement['amount'];
            $fromUser = $reimbursement['from_user_id'];
            $toUser = $reimbursement['to_user_id'];

            // Celui qui a remboursé a "payé", sa dette diminue (balance augmente)
            if (isset($balances[$fromUser])) $balances[$fromUser] += $amount;
            
            // Celui qui a reçu a été remboursé, son crédit diminue (balance diminue)
            if (isset($balances[$toUser])) $balances[$toUser] -= $amount;
        }

        return $balances;
    }

    /**
     * Algorithme pour faire correspondre les débiteurs et les créanciers
     */
    private function calculateTransactions(array $balances): array
    {
        $debtors = [];   // Ceux qui doivent de l'argent (Balance < 0)
        $creditors = []; // Ceux à qui on doit de l'argent (Balance > 0)

        foreach ($balances as $userId => $amount) {
            // On arrondit pour éviter les erreurs de virgule flottante infimes
            $amount = round($amount, 2);
            if ($amount < -0.01) {
                $debtors[$userId] = $amount;
            } elseif ($amount > 0.01) {
                $creditors[$userId] = $amount;
            }
        }

        $transactions = [];

        // Tant qu'il y a des gens qui doivent de l'argent et des gens à rembourser
        while (!empty($debtors) && !empty($creditors)) {
            // On prend le plus gros débiteur et le plus gros créancier (Algorithme Glouton)
            // asort trie par valeur croissante (le plus petit négatif est le premier)
            asort($debtors); 
            // arsort trie par valeur décroissante (le plus grand positif est le premier)
            arsort($creditors);

            // Récupérer les ID et Montants (premier élément des tableaux)
            $debtorId = array_key_first($debtors);
            $debtorAmount = abs($debtors[$debtorId]); // On prend la valeur absolue pour le calcul

            $creditorId = array_key_first($creditors);
            $creditorAmount = $creditors[$creditorId];

            // On calcule le montant qui peut être remboursé maintenant
            // C'est le minimum entre ce que le débiteur doit et ce que le créancier attend
            $amountToPay = min($debtorAmount, $creditorAmount);
            $amountToPay = round($amountToPay, 2);

            // Enregistrer la transaction
            $transactions[] = [
                'from' => $this->getUserName($debtorId), // Fonction utilitaire pour avoir le nom
                'from_id' => $debtorId,
                'to' => $this->getUserName($creditorId),
                'to_id' => $creditorId,
                'amount' => $amountToPay
            ];

            // Mettre à jour les montants restants dans les tableaux
            $debtors[$debtorId] += $amountToPay;
            $creditors[$creditorId] -= $amountToPay;

            // Si la dette est réglée (proche de 0), on retire le débiteur de la liste
            if (round($debtors[$debtorId], 2) >= 0) {
                unset($debtors[$debtorId]);
            }
            
            // Si le créancier est remboursé (proche de 0), on le retire
            if (round($creditors[$creditorId], 2) <= 0) {
                unset($creditors[$creditorId]);
            }
        }

        return $transactions;
    }

    // Petite fonction utilitaire pour récupérer le prénom (optionnel mais plus joli)
    private function getUserName(int $userId): string
    {
        $stmt = $this->pdo->prepare("SELECT firstname FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['firstname'] : "User #$userId";
    }
}
?>