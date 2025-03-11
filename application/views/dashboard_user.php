<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Mes Réservations</h2>

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
                <th>Taille</th>
                <th>Bâtiment</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservations)) : ?>
                <?php foreach ($reservations as $reservation) : ?>
                    <tr>
                        <td>Box <?= htmlspecialchars($reservation->box_num); ?></td>
                        <td><?= htmlspecialchars($reservation->box_size); ?> m²</td>
                        <td><?= htmlspecialchars($reservation->warehouse_name); ?></td>
                        <td><?= date('d/m/Y', strtotime($reservation->start_reservation_date)); ?></td>
                        <td><?= date('d/m/Y', strtotime($reservation->end_reservation_date)); ?></td>
                        <td><?= htmlspecialchars($reservation->status); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">Aucune réservation en cours.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="change-password">
        <a href="<?= site_url('user/change_password'); ?>" class="btn">Changer de mot de passe</a>
    </div>

</div>

<?php include('includes/footer.php'); ?>
