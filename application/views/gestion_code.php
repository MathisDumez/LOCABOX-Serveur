<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Codes d'Accès</h2>

    <?php include('includes/message.php'); ?>

    <table>
        <thead>
            <tr>
                <th>Batiment</th>
                <th>Box</th>
                <th>Code Actuel</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($boxes)) : ?>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td><?= htmlspecialchars($box->warehouse_name); ?></td>
                        <td>Box <?= htmlspecialchars($box->num); ?></td>
                        <td><?= htmlspecialchars($box->current_code ?? 'Aucun'); ?></td>
                        <td>
                            <a href="<?= site_url('Code_Controller/generate_code/' . htmlspecialchars($box->num)); ?>" class="btn">Générer un Nouveau Code</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucun box enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
