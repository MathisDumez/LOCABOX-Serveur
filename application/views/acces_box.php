<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Accès au Box</h2>

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