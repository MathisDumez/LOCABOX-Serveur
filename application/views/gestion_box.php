<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/dashboard'); ?>" class="btn">Retour au tableau de bord</a>
    <a href="<?= site_url('admin/ajouter_box'); ?>" class="btn">Ajouter un Box</a>
    
    <form method="GET" action="<?= site_url('admin/gestion_box'); ?>">
        <label for="size">Taille :</label>
        <select name="size" id="size">
            <option value="">Toutes</option>
            <?php foreach ([7, 40] as $s) : ?>
                <option value="<?= $s; ?>" <?= isset($_GET['size']) && $_GET['size'] == $s ? 'selected' : ''; ?>><?= $s; ?> m²</option>
            <?php endforeach; ?>
        </select>

        <label for="warehouse">Bâtiment :</label>
        <select name="id_warehouse" id="id_warehouse">
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

        <label for="connection_status">État :</label>
        <select name="connection_status" id="connection_status">
            <option value="">Tous</option>
            <option value="connected" <?= isset($_GET['connection_status']) && $_GET['connection_status'] == 'connected' ? 'selected' : ''; ?>>Connecté</option>
            <option value="disconnected" <?= isset($_GET['connection_status']) && $_GET['connection_status'] == 'disconnected' ? 'selected' : ''; ?>>Non connecté</option>
        </select>

        <button type="submit" class="btn">Filtrer</button>
        <a href="<?= site_url('admin/gestion_box'); ?>" class="btn">Réinitialiser</a>
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
                    <th>Détails</th>
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
                            <?php
                                if ($box->connection_status === "Connecté") {
                                    echo '<span style="color:green">Connecté</span>';
                                } else {
                                    echo '<span style="color:red">Non connecté</span>';
                                }
                            ?>
                        </td>
                        <td><a href="<?= site_url('admin/detail_box/' . $box->id_box); ?>" class="btn">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun box disponible.</p>
    <?php endif; ?>
    
    <?= $pagination_links ?? ''; ?>

</div>

<script>
    setInterval(function() {
        location.reload();
    }, 10000); // toutes les 10 secondes
</script>

<?php include('includes/footer.php'); ?>
