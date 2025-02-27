<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Connexion</h2>

    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <form action="<?php echo site_url('Identification_Controller/login'); ?>" method="post">
        <label>Email :</label>
        <input type="email" name="email" required>
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        
        <button type="submit" class="btn">Se connecter</button>
    </form>

    <p>Pas encore inscrit ? <a href="<?php echo site_url('Identification_Controller/inscription_client'); ?>">Créer un compte</a></p>
</div>

<?php include('includes/footer.php'); ?>
