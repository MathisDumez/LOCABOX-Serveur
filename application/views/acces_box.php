<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Accès aux Box</h2>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"> <?= htmlspecialchars($this->session->flashdata('success')) ?> </p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"> <?= htmlspecialchars($this->session->flashdata('error')) ?> </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Box</th>
                <th>Batiment</th>
                <th>Utilisateur</th>
                <th>Date et Heure</th>
                <th>Type d'Accès</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($acces_logs)) : ?>
                <?php foreach ($acces_logs as $log) : ?>
                    <tr>
                        <td>Box <?= htmlspecialchars($log->num); ?></td>
                        <td><?= htmlspecialchars($log->user_email); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($log->access_date)); ?></td>
                        <td><?= $log->locked ? 'Fermeture' : 'Ouverture'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Aucun accès enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
