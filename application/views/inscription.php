<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Inscription</h2>

    <?php include('includes/message.php'); ?>

    <form action="<?= site_url('id/process_inscription'); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label>Email :</label>
        <input type="email" name="email" value="<?= set_value('email'); ?>" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="<?= site_url('id/identification'); ?>">Se connecter</a></p>
</div>

<?php include('includes/footer.php'); ?>
