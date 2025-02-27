<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Changer de Mot de Passe</h2>

    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>

    <form action="<?php echo site_url('User_Controller/update_password'); ?>" method="post">
        <label>Ancien Mot de Passe :</label>
        <input type="password" name="old_password" required>

        <label>Nouveau Mot de Passe :</label>
        <input type="password" name="new_password" required>

        <label>Confirmer le Nouveau Mot de Passe :</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn">Changer le mot de passe</button>
        <a href="<?php echo site_url('Vitrine_Controller/vitrine_box'); ?>" class="btn-cancel">Annuler</a>
    </form>

</div>

<?php include('includes/footer.php'); ?>
