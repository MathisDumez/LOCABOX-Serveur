<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Alarmes du Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/detail_box/' . $box->id_box); ?>" class="btn">Retour</a>

    <table>
        <thead>
            <tr>
                <th>Bâtiment</th>
                <th>Box</th>
                <th>Locataire</th>
                <th>Date et Heure</th>
                <th>Type d'Alarme</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($alarms)) : ?>
                <?php foreach ($alarms as $alarm) : ?>
                    <tr>
                        <td><?= htmlspecialchars($alarm->warehouse_name); ?></td>
                        <td>Box <?= htmlspecialchars($alarm->box_num); ?></td>
                        <td><?= !empty($alarm->user_email) ? htmlspecialchars($alarm->user_email) : 'Aucun locataire'; ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($alarm->alarm_date)); ?></td>
                        <td><?= htmlspecialchars($alarm->info); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucune alarme enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>