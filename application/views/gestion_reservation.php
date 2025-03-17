<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Réservations</h2>

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
                <th>Utilisateur</th>
                <th>Box</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservations)) : ?>
                <?php foreach ($reservations as $reservation) : ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation->user_email); ?></td>
                        <td>Box <?= htmlspecialchars($reservation->box_num); ?></td>
                        <td><?= date('d/m/Y', strtotime($reservation->start_reservation_date)); ?></td>
                        <td><?= date('d/m/Y', strtotime($reservation->end_reservation_date)); ?></td>
                        <td><?= htmlspecialchars($reservation->status); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucune réservation enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
