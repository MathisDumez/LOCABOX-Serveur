<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Mes Réservations</h2>
    
    <?php include('includes/message.php'); ?>

    <div class="change-password">
        <a href="<?= site_url('user/change_password'); ?>" class="btn">Changer de mot de passe</a>
    </div>

    <form method="GET" action="<?= site_url('user/dashboard'); ?>">
        <label for="size">Taille :</label>
        <select name="size" id="size">
            <option value="">Toutes</option>
            <?php foreach ([7, 40] as $s) : ?>
                <option value="<?= $s; ?>" <?= isset($_GET['size']) && $_GET['size'] == $s ? 'selected' : ''; ?>><?= $s; ?> m²</option>
            <?php endforeach; ?>
        </select>

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
        <a href="<?= site_url('user/dashboard'); ?>" class="btn">Réinitialiser</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Bâtiment</th>
                <th>Box</th>
                <th>Taille</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservations)) : ?>
                <?php foreach ($reservations as $reservation) : ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation->warehouse_name); ?></td>
                        <td>Box <?= htmlspecialchars($reservation->box_num); ?></td>
                        <td><?= htmlspecialchars($reservation->box_size); ?> m²</td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation->start_reservation_date)); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation->end_reservation_date)); ?></td>
                        <td><?= htmlspecialchars($reservation->status); ?></td>
                        <td>
                            <?php if ($reservation->status != 'Annulée' && $reservation->status != 'Terminée') : ?>
                                <a href="#" class="btn btn-cancel" onclick="simpleConfirm('Confirmer l\'annulation ?', function(confirmé) {
                                    if (confirmé) {
                                        window.location.href = '<?= site_url('user/annuler_reservation/' . $reservation->rent_number); ?>';
                                    }
                                }); return false;">Annulée</a>
                            <?php else : ?>
                                <span class="text-muted">Action Impossible</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">Aucune réservation en cours.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?= $pagination_links ?? ''; ?>

</div>

<?php include('includes/footer.php'); ?>
