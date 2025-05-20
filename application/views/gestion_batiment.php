<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Bâtiments</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/dashboard'); ?>" class="btn">Retour au tableau de bord</a>
    <a href="<?= site_url('admin/ajouter_batiment'); ?>" class="btn">Ajouter un Bâtiment</a>

    <?php if (!empty($warehouses)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($warehouses as $w) : ?>
                    <tr>
                        <td><?= htmlspecialchars($w->name); ?></td>
                        <td><?= htmlspecialchars($w->address); ?></td>
                        <td>
                            <a href="<?= site_url('admin/modifier_batiment/' . $w->id_warehouse); ?>" class="btn">Modifier</a>
                            <a href="#" class="btn btn-cancel" onclick="simpleConfirm('Confirmer la suppression ?', function(confirmé) {
                                if (confirmé) {
                                    window.location.href = '<?= site_url('admin/supprimer_batiment/' . $w->id_warehouse); ?>';
                                }
                            }); return false;">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun bâtiment enregistré pour l'instant.</p>
    <?php endif; ?>

    <?= $pagination_links ?? ''; ?>
    
</div>

<?php include('includes/footer.php'); ?>
