<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Liste des Box</h2>

    <!-- Formulaire de filtrage -->
    <form method="GET" action="<?= site_url('Vitrine_Controller/index'); ?>">
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

        <label for="available">Disponibilité :</label>
        <select name="available" id="available">
            <option value="">Toutes</option>
            <option value="1" <?= isset($_GET['available']) && $_GET['available'] == '1' ? 'selected' : ''; ?>>Disponible</option>
            <option value="0" <?= isset($_GET['available']) && $_GET['available'] == '0' ? 'selected' : ''; ?>>Indisponible</option>
        </select>

        <button type="submit" class="btn">Filtrer</button>
        <a href="<?= site_url('Vitrine_Controller/index'); ?>" class="btn btn-reset">Réinitialiser</a>
    </form>

    <?php if (!empty($boxes)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Bâtiment</th>
                    <th>Numéro</th>
                    <th>Taille</th>
                    <th>Disponibilité</th>
                    <th>Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td><?= htmlspecialchars($box->warehouse_name ?? 'Indisponible'); ?></td>
                        <td><?= htmlspecialchars($box->num); ?></td>
                        <td><?= htmlspecialchars($box->size); ?> m²</td>
                        <td><?= $box->available ? 'Disponible' : 'Indisponible'; ?></td>
                        <td>
                            <a href="<?= site_url('Vitrine_Controller/detail/' . $box->id_box); ?>" class="btn">Réserver</a>
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
