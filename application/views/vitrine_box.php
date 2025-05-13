<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Liste des Box</h2>

    <?php include('includes/message.php'); ?>

    <form method="GET" action="<?= site_url('vitrine/index'); ?>">
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
            <?php foreach ($warehouses as $w) : ?>
                <option value="<?= $w->id_warehouse; ?>" <?= isset($_GET['warehouse']) && $_GET['warehouse'] == $w->id_warehouse ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($w->name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn">Filtrer</button>
        <a href="<?= site_url('vitrine/index'); ?>" class="btn">Réinitialiser</a>
    </form>

    <?php if (!empty($boxes)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Bâtiment</th>
                    <th>Numéro</th>
                    <th>Taille</th>
                    <th>Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td><?= htmlspecialchars($box->warehouse_name ?? 'Indisponible'); ?></td>
                        <td><?= htmlspecialchars($box->num); ?></td>
                        <td><?= htmlspecialchars($box->size); ?> m²</td>
                        <td>
                            <a href="<?= site_url('vitrine/detail/' . $box->id_box); ?>" class="btn">Réserver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun box disponible.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
