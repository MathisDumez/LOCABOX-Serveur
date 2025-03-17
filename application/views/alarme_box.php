<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Alarmes du Box</h2>

    <a href="<?= site_url('admin/etat_box'); ?>" class="btn btn-secondary">Retour à l'état des boxs</a>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"><?= htmlspecialchars($this->session->flashdata('success')); ?></p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"><?= htmlspecialchars($this->session->flashdata('error')); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Bâtiment</th>
                <th>Box</th>
                <th>Utilisateur</th>
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