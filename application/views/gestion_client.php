<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Clients</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/dashboard'); ?>" class="btn">Retour au tableau de bord</a>
    <a href="<?= site_url('admin/ajouter_client'); ?>" class="btn">Ajouter un Client</a>

    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($user_box)) : ?>
                <?php foreach ($user_box as $user) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user->email); ?></td>
                        <td><?= $user->admin ? 'Administrateur' : 'Client'; ?></td>
                        <td>
                            <a href="<?= site_url('admin/modifier_client/' . $user->id_user_box); ?>" class="btn">Modifier</a>
                            <a href="#" class="btn btn-cancel" onclick="simpleConfirm('Confirmer la suppression ?', function(confirmé) {
                                if (confirmé) {
                                    window.location.href = '<?= site_url('admin/supprimer_client/' . $user->id_user_box); ?>';
                                }
                            }); return false;">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="2">Aucun client trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
