<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Codes d'Accès</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_code'); ?>" class="btn">Retour à la gestion des codes</a>
    <a href="<?= site_url('admin/detail_box/' . $box->id_box); ?>" class="btn">Détail du box</a>

    <table>
        <thead>
            <tr>
                <th>Box</th>
                <th>Code Généré</th>
                <th>Date de Création</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($history)) : ?>
                <?php foreach ($history as $code) : ?>
                    <tr>
                        <td>Box <?= htmlspecialchars($code->id_box); ?></td>
                        <td><?= htmlspecialchars($code->code); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($code->code_date)); ?></td>
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
