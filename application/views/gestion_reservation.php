<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestien des Réservations</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/dashboard'); ?>" class="btn">Retour au tableau de bord</a>
    <a href="<?= site_url('admin/gestion_reservation'); ?>" class="btn">Mettre à jour les statuts</a>

    <form method="GET" action="<?= site_url('admin/gestion_reservation'); ?>">
        <label for="email">Email :</label>
        <input type="text" name="email" id="email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">

        <label for="start_date">Date de début :</label>
        <input type="date" name="start_date" id="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">

        <label for="end_date">Date de fin :</label>
        <input type="date" name="end_date" id="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">

        <label for="warehouse">Bâtiment :</label>
        <select name="warehouse" id="warehouse">
            <option value="">Tous</option>
            <?php if (!empty($warehouses)) : ?>
                <?php foreach ($warehouses as $w) : ?>
                    <option value="<?= $w->id_warehouse; ?>" <?= isset($_GET['warehouse']) && $_GET['warehouse'] == $w->id_warehouse ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($w->name); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <label for="status">Statut :</label>
        <select name="status" id="status">
            <option value="">Tous</option>
            <?php if (!empty($status)) : ?>
                <?php foreach ($status as $s) : ?>
                    <option value="<?= $s->status; ?>" <?= isset($_GET['status']) && $_GET['status'] == $s->status ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($s->status); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit" class="btn">Filtrer</button>
        <a href="<?= site_url('admin/gestion_reservation'); ?>" class="btn">Réinitialiser</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Locataire</th>
                <th>Batiment</th>
                <th>Box</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservations)) : ?>
                <?php foreach ($reservations as $reservation) : ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation->user_email); ?></td>
                        <td><?= htmlspecialchars($reservation->warehouse_name); ?></td>
                        <td>Box <?= htmlspecialchars($reservation->box_num); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation->start_reservation_date)); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation->end_reservation_date)); ?></td>
                        <td><?= htmlspecialchars($reservation->status); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= site_url('admin/modifier_reservation/' . $reservation->rent_number); ?>" class="btn">Modifier</a>
                                <?php if ($reservation->status == 'En Attente') : ?>
                                    <a href="<?= site_url('admin/valider_reservation/' . $reservation->rent_number); ?>" class="btn btn-success">Valider</a>
                                    <a href="<?= site_url('admin/annuler_reservation/' . $reservation->rent_number); ?>" class="btn btn-cancel">Annuler</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">Aucune réservation enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
