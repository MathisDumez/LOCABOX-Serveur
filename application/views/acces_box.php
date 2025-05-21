<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Accès au Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/detail_box/' . $box->id_box); ?>" class="btn">Retour</a>

    <table>
        <thead>
            <tr>
                <th>Bâtiment</th>
                <th>Box</th>
                <th>Locataire</th>
                <th>Date et Heure</th>
                <th>Type d'Accès</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($access_logs)) : ?>
                <?php foreach ($access_logs as $log) : ?>
                    <tr>
                        <td><?= htmlspecialchars($log->warehouse_name); ?></td>
                        <td>Box <?= htmlspecialchars($log->box_num); ?></td>
                        <td><?= !empty($log->user_email) ? htmlspecialchars($log->user_email) : 'Aucun locataire'; ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($log->access_date)); ?></td>
                        <td><?= $log->locked ? 'Fermeture' : 'Ouverture'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucun accès enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>