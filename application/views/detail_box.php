<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Détail du Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_box') ?>" class="btn" style="margin-bottom: 20px;">Retour à la gestion des box</a>

    <div style="text-align: left; margin-bottom: 30px;">
        <p><strong>Bâtiment :</strong> <?= $box->warehouse_name ?></p>
        <p><strong>Numéro :</strong> <?= $box->num ?></p>
        <p><strong>Taille :</strong> <?= $box->size ?> m²</p>
        <p><strong>Disponibilité :</strong> <?= $box->available ? 'Disponible' : 'Occupé' ?></p>
    </div>

    <div style="text-align: left; margin-bottom: 30px;">
        <h4>État de connexion</h4>
        <p><strong>Dernière activité :</strong> <?= date('d/m/Y H:i:s', strtotime($box->state)) ?></p>
        <p>
            <strong>Statut :</strong>
            <?php if ($box->connection_status == 'Connecté'): ?>
                <span class="status-indicator connected"></span>
                <span style="color: #28a745; font-weight: bold;"><?= $box->connection_status ?></span>
            <?php else: ?>
                <span class="status-indicator disconnected"></span>
                <span style="color: #dc3545; font-weight: bold;"><?= $box->connection_status ?></span>
            <?php endif; ?>
        </p>
    </div>

    <div class="action-buttons" style="margin-bottom: 20px;">
        <a href="<?= site_url('admin/modifier_box/' . htmlspecialchars($box->id_box)); ?>" class="btn">Modifier</a>
        <a href="#" class="btn btn-cancel" onclick="simpleConfirm('Confirmer la suppression ?', function(confirmé) {
            if (confirmé) {
                window.location.href = '<?= site_url('admin/supprimer_box/' . htmlspecialchars($box->id_box)); ?>';
            }
        }); return false;">Supprimer</a>
    </div>
    
    <h3>Historique</h3>
    <div style="margin-bottom: 30px;">
        <div class="action-buttons">
            <a href="<?= site_url('admin/acces_box/' . htmlspecialchars($box->id_box)); ?>" class="btn">Accès</a>
            <a href="<?= site_url('admin/alarme_box/' . htmlspecialchars($box->id_box)); ?>" class="btn">Alarmes</a>
            <a href="<?= site_url('admin/historique_code/' . htmlspecialchars($box->id_box)); ?>" class="btn">Codes</a>
        </div>
    </div>

    <h2>Réservation</h2>
    <div style="text-align: left;">
        <?php if ($box->email): ?>
            <p><strong>Client :</strong> <?= $box->email ?></p>
            <p><strong>Du :</strong> <?= date('d/m/Y H:i', strtotime($box->start_reservation_date)) ?></p>
            <p><strong>Au :</strong> <?= date('d/m/Y H:i', strtotime($box->end_reservation_date)) ?></p>
            <p><strong>Statut :</strong> <?= $box->status ?></p>

            <a href="<?= site_url('admin/detail_reservation/' . $box->rent_number) ?>" class="btn" style="margin-bottom: 20px;">Voir détail de la réservation</a>
        <?php else: ?>
            <p>Aucune réservation en cours pour ce box.</p>
        <?php endif; ?>
        <a href="<?= site_url('admin/gestion_reservation') ?>" class="btn" style="margin-top: 20px;">Retour à la gestion des réservations</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>
