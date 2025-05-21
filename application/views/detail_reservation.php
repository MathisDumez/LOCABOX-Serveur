<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Détail de la Réservation</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_reservation') ?>" class="btn" style="margin-bottom: 20px;">Retour à la gestion des réservations</a>

    <?php if (!empty($reservation)) : ?>
        <div style="text-align: left;">
            <p><strong>Client :</strong> <?= htmlspecialchars($reservation->email) ?></p>
            <p><strong>Du :</strong> <?= date('d/m/Y H:i', strtotime($reservation->start_reservation_date)) ?></p>
            <p><strong>Au :</strong> <?= date('d/m/Y H:i', strtotime($reservation->end_reservation_date)) ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($reservation->status) ?></p>
        </div>

        <h2>Box</h2>
        <div style="text-align: left; margin-bottom: 30px;">
            <p><strong>Bâtiment :</strong> <?= htmlspecialchars($reservation->warehouse_name) ?></p>
            <p><strong>Numéro :</strong> <?= htmlspecialchars($reservation->num) ?></p>
            <p><strong>Taille :</strong> <?= htmlspecialchars($reservation->size) ?> m²</p>
            <p><strong>Disponibilité :</strong> <?= $reservation->available ? 'Disponible' : 'Occupé' ?></p>

            <a href="<?= site_url('admin/detail_box/' . $reservation->id_box) ?>" class="btn">Voir détail du box</a>
        </div>

        <div class="action-buttons" style="margin-bottom: 20px;">
            <a href="<?= site_url('admin/modifier_reservation/' . $reservation->rent_number); ?>" class="btn">Modifier</a>

            <?php if ($reservation->status === 'En Attente') : ?>
                <a href="#" class="btn" onclick="simpleConfirm('Valider la réservation ?', function(confirmé) {
                    if (confirmé) {
                        window.location.href = '<?= site_url('admin/valider_reservation/' . $reservation->rent_number); ?>';
                    }
                }); return false;">Valider</a>
            <?php endif; ?>

            <?php if (
                $reservation->status === 'En Attente' ||
                $reservation->status === 'Validée' ||
                $reservation->status === 'En Cours'
            ) : ?>
                <a href="#" class="btn" onclick="simpleConfirm('Confirmer l\'annulation ?', function(confirmé) {
                    if (confirmé) {
                        window.location.href = '<?= site_url('admin/annuler_reservation/' . $reservation->rent_number); ?>';
                    }
                }); return false;">Annuler</a>
            <?php endif; ?>

            <?php if (
                $reservation->status === 'Annulée' ||
                $reservation->status === 'Terminée'
            ) : ?>
                <a href="#" class="btn btn-cancel" onclick="simpleConfirm('Confirmer la suppression ?', function(confirmé) {
                    if (confirmé) {
                        window.location.href = '<?= site_url('admin/supprimer_reservation/' . $reservation->rent_number); ?>';
                    }
                }); return false;">Supprimer</a>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
