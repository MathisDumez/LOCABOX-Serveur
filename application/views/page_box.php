<?php include('includes/header.php'); ?>

<h2>Détails du Box</h2>

<?php if (!empty($box)) : ?>
    <p><strong>Numéro :</strong> <?= htmlspecialchars($box->num ?? 'Indisponible'); ?></p>
    <p><strong>Taille :</strong> <?= htmlspecialchars($box->size ?? 'Indisponible'); ?> m²</p>
    <p><strong>Bâtiment :</strong> <?= htmlspecialchars($box->id_warehouse ?? 'Indisponible'); ?></p>
    <p><strong>Disponibilité :</strong> <?= isset($box->available) ? ($box->available ? 'Oui' : 'Non') : 'Indisponible'; ?></p>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"> <?= htmlspecialchars($this->session->flashdata('error')) ?> </p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"> <?= htmlspecialchars($this->session->flashdata('success')) ?> </p>
    <?php endif; ?>

    <?php if (!empty($box->available)) : ?>
        <h3>Réserver ce box</h3>
        <form method="post" action="<?= site_url('Vitrine_Controller/reserver'); ?>">
            <input type="hidden" name="box_id" value="<?= htmlspecialchars($box->id_box); ?>">

            <label for="start_date">Date de début :</label>
            <input type="date" id="start_date" name="start_date" required min="<?= date('Y-m-d'); ?>">

            <label for="end_date">Date de fin :</label>
            <input type="date" id="end_date" name="end_date" required min="<?= date('Y-m-d', strtotime('+1 day')); ?>">

            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <button type="submit" name="reserver" onclick="window.location.href='<?= site_url('Identification_Controller/identification'); ?>'; return false;">Se connecter pour réserver</button>
            <?php else : ?>
                <button type="submit" name="reserver">Confirmer la réservation</button>
            <?php endif; ?>
        </form>
    <?php else : ?>
        <p class="error"><strong>Ce box est actuellement indisponible.</strong></p>
    <?php endif; ?>
<?php else : ?>
    <p>Aucun détail disponible.</p>
<?php endif; ?>

<a href="<?= site_url('Vitrine_Controller/index'); ?>">Retour à la liste</a>

<?php include('includes/footer.php'); ?>
