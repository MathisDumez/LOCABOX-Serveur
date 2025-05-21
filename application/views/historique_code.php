<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Codes d'Accès</h2>

    <?php include('includes/message.php'); ?>

    <table>
        <thead>
            <tr>
                <th>Box</th>
                <th>Code Généré</th>
                <th>Date de Création</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($codes)) : ?>
                <?php foreach ($codes as $code) : ?>
                    <tr>
                        <td>Box <?= htmlspecialchars($code->id_box); ?></td>
                        <td><?= htmlspecialchars($code->generated_code); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($code->created_at)); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucun code enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
