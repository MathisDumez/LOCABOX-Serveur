<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/dashboard'); ?>" class="btn btn-secondary">Retour au tableau de bord</a>

    <form method="GET" action="<?= site_url('admin/etat_box'); ?>">
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
            <option value="0" <?= isset($_GET['available']) && $_GET['available'] == '0' ? 'selected' : ''; ?>>Occupée</option>
        </select>

        <button type="submit" class="btn">Filtrer</button>
        <a href="<?= site_url('admin/etat_box'); ?>" class="btn btn-reset">Réinitialiser</a>
    </form>

    <h2>État des Box</h2>

    <?php if (!empty($boxes)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Bâtiment</th>
                    <th>Numéro</th>
                    <th>Taille</th>
                    <th>Disponibilité</th>
                    <th>État</th>
                    <th>Accès</th>
                    <th>Alarmes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td><?= htmlspecialchars($box->warehouse_name ?? 'Indisponible'); ?></td>
                        <td><?= htmlspecialchars($box->num); ?></td>
                        <td><?= htmlspecialchars($box->size); ?> m²</td>
                        <td><?= $box->available ? 'Disponible' : 'Occupée'; ?></td>
                        <td>
                            <span style="color: <?= empty($box->state) || $box->state === 'Indisponible' ? 'red' : 'black'; ?>">
                                <?= empty($box->state) || $box->state === 'Indisponible' ? 'Indisponible' : htmlspecialchars($box->state); ?>
                            </span>
                        </td>
                        <td><a href="<?= site_url('admin/acces_box/' . $box->id_box); ?>" class="btn">Voir</a></td>
                        <td><a href="<?= site_url('admin/alarme_box/' . $box->id_box); ?>" class="btn">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun box disponible.</p>
    <?php endif; ?>

    <h2>Ajouter un Box</h2>

    <form method="POST" action="<?= site_url('admin/ajouter_box'); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label for="num">Numéro du Box :</label>
        <input type="number" name="num" id="num" required min="1">

        <label for="size">Taille (m²) :</label>
        <select name="size" id="size" required>
            <option value="7">7 m²</option>
            <option value="40">40 m²</option>
        </select>

        <label for="id_warehouse">Bâtiment :</label>
        <select name="id_warehouse" id="id_warehouse" required>
            <?php if (!empty($warehouses)) : ?>
                <?php foreach ($warehouses as $warehouse) : ?>
                    <option value="<?= $warehouse->id_warehouse; ?>">
                        <?= htmlspecialchars($warehouse->name); ?>
                    </option>
                <?php endforeach; ?>
            <?php else : ?>
                <option value="">Aucun bâtiment disponible</option>
            <?php endif; ?>
        </select>

        <label for="available">Disponibilité :</label>
        <select name="available" id="available">
            <option value="1">Disponible</option>
            <option value="0">Occupée</option>
        </select>

        <button type="submit" class="btn">Ajouter</button>
    </form>

</div>

<?php include('includes/footer.php'); ?>
