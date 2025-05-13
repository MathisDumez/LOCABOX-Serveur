<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Détail du Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_box') ?>" class="btn" style="margin-bottom: 10px;">Retour à la gestion des box</a><br>

    <p><strong>Bâtiment :</strong> <?= $box->warehouse_name ?></p>
    <p><strong>Numéro :</strong> <?= $box->num ?></p>
    <p><strong>Taille :</strong> <?= $box->size ?> m²</p>
    <p><strong>État :</strong> <?= $box->available ? 'Disponible' : 'Occupé' ?></p><br>

    <a href="<?= site_url('admin/modifier_box/' . $box->id_box); ?>" class="btn" style="margin-bottom: 10px;">Modifier Box</a>
    <a href="#" class="btn btn-cancel" onclick="simpleConfirm('Confirmer la suppression ?', function(confirmé) {
        if (confirmé) {
            window.location.href = '<?= site_url('admin/supprimer_box/' . $box->id_box); ?>';
        }
    }); return false;">Supprimer</a>

    <table>
        <thead>
            <th>Historique</th>
        </thead>
        <tbody>
            <td>
                <a href="<?= site_url('admin/acces_box/' . $box->id_box); ?>" class="btn">Accès</a>
                <a href="<?= site_url('admin/alarme_box/' . $box->id_box); ?>" class="btn">Alarmes</a>
            </td>
        </tbody>
    </table>
    
    <h2>Réservation</h2>

    <?php if ($box->email): ?>
        <p><strong>Client :</strong> <?= $box->email ?></p>
        <p><strong>Du :</strong> <?= date('d/m/Y H:i', strtotime($box->start_reservation_date)) ?></p>
        <p><strong>Au :</strong> <?= date('d/m/Y H:i', strtotime($box->end_reservation_date)) ?></p>
        <p><strong>Statut :</strong> <?= $box->status ?></p>
    <?php else: ?>
        <p>Aucune réservation en cours pour ce box.</p>
    <?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>
