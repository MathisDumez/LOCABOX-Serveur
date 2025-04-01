<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Modifier une Réservation</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_reservation'); ?>" class="btn">Retour aux réservations</a>

    <form method="POST" action="<?= site_url('admin/modifier_reservation/' . $reservation->rent_number); ?>">
        <label for="start_reservation_date">Date de début :</label>
        <input type="date" name="start_reservation_date" value="<?= htmlspecialchars($reservation->start_reservation_date); ?>" required>

        <label for="end_reservation_date">Date de fin :</label>
        <input type="date" name="end_reservation_date" value="<?= htmlspecialchars($reservation->end_reservation_date); ?>" required>

        <label for="status">Statut :</label>
        <select name="status">
            <option value="En Attente" <?= ($reservation->status == 'En Attente') ? 'selected' : ''; ?>>En Attente</option>
            <option value="En Cours" <?= ($reservation->status == 'En Cours') ? 'selected' : ''; ?>>En Cours</option>
            <option value="Terminée" <?= ($reservation->status == 'Terminée') ? 'selected' : ''; ?>>Terminée</option>
            <option value="Annulée" <?= ($reservation->status == 'Annulée') ? 'selected' : ''; ?>>Annulée</option>
        </select>

        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="<?= site_url('admin/gestion_reservation'); ?>" class="btn">Annuler</a>
    </form>
</div>

<?php include('includes/footer.php'); ?>
