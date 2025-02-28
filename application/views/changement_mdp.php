<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Changer de Mot de Passe</h2>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"> <?= htmlspecialchars($this->session->flashdata('error')) ?> </p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"> <?= htmlspecialchars($this->session->flashdata('success')) ?> </p>
    <?php endif; ?>

    <form action="<?= site_url('User_Controller/update_password'); ?>" method="post">
        <label>Ancien Mot de Passe :</label>
        <input type="password" name="old_password" required>

        <label>Nouveau Mot de Passe :</label>
        <input type="password" name="new_password" required>

        <label>Confirmer le Nouveau Mot de Passe :</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn">Changer le mot de passe</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
