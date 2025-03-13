<?php include('includes/header.php'); ?>

<div class="container">
    <h2>État des Box</h2>

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
                    <?php
                    $sort_fields = ['num' => 'Numéro', 'size' => 'Taille', 'available' => 'Bâtiment'];
                    $current_sort = htmlspecialchars($_GET['sort'] ?? 'num');
                    $current_order = htmlspecialchars($_GET['order'] ?? 'ASC');

                    foreach ($sort_fields as $key => $label) :
                        $new_order = ($current_sort === $key && $current_order === 'ASC') ? 'DESC' : 'ASC';
                    ?>
                        <th><a href="<?= site_url("Vitrine_Controller/index?sort=" . urlencode($key) . "&order=" . urlencode($new_order)) ?>"><?= $label; ?></a></th>
                    <?php endforeach; ?>
                    <th>Disponibilité</th>
                    <th>Accès</th>
                    <th>Alarmes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td><?= htmlspecialchars($box->num); ?></td>
                        <td><?= htmlspecialchars($box->size); ?> m²</td>
                        <td><?= htmlspecialchars($box->warehouse_name ?? 'Indisponible'); ?></td>
                        <td><?= $box->available ? 'Disponible' : 'Occupée'; ?></td>
                        <td>
                            <a href="<?= site_url('admin/acces_box/' . $box->id_box); ?>" class="btn">Voir</a>
                        </td>
                        <td>
                            <a href="<?= site_url('admin/alarme_box/' . $box->id_box); ?>" class="btn">Voir</a>
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
