<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Connexion</h2>

    <?php include('includes/message.php'); ?>

    <form action="<?= site_url('id/login'); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="redirect" value="<?= isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : ''; ?>">
        
        <label>Email :</label>
        <input type="email" name="email" value="<?= set_value('email'); ?>" required>
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        
        <button type="submit" class="btn">Se connecter</button>
    </form>

    <p>Pas encore inscrit ? <a href="<?= site_url('id/inscription'); ?>">Créer un compte</a></p>
</div>

<?php include('includes/footer.php'); ?>