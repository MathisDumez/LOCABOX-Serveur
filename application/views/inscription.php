<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Inscription Client</h2>

    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <form action="<?php echo site_url('Identification_Controller/register'); ?>" method="post">
        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="<?php echo site_url('Identification_Controller/identification'); ?>">Se connecter</a></p>
</div>

<?php include('includes/footer.php'); ?>
