<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Alarmes</h2>

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
                <th>Date et Heure</th>
                <th>Type d'Alarme</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($alarms)) : ?>
                <?php foreach ($alarms as $alarm) : ?>
                    <tr>
                        <td>Box <?= htmlspecialchars($alarm->id_box); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($alarm->alarm_time)); ?></td>
                        <td><?= htmlspecialchars($alarm->alarm_type); ?></td>
                        <td><?= htmlspecialchars($alarm->status); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Aucune alarme enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
