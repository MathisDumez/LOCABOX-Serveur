<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Codes d'Accès</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/dashboard'); ?>" class="btn">Retour au tableau de bord</a>

    <table>
        <thead>
            <tr>
                <th>Batiment</th>
                <th>Box</th>
                <th>Code Actuel</th>
                <th>Action</th>
                <th>Historique</th>
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
                            <a href="#" class="btn" onclick="simpleConfirm('Confirmer la génération d\'un code ?', function(confirmé) {
                                if (confirmé) {
                                    window.location.href = '<?= site_url('admin/generer_code/' . htmlspecialchars($box->id_box)); ?>';
                                }
                            }); return false;">Générer</a>
                        </td>
                        <td>
                            <a href="<?= site_url('admin/historique_code/' . htmlspecialchars($box->id_box)); ?>" class="btn">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucun box enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?= $pagination_links ?? ''; ?>

</div>

<script>
    setInterval(function() {
        location.reload();
    }, 10000); // toutes les 10 secondes
</script>

<?php include('includes/footer.php'); ?>
