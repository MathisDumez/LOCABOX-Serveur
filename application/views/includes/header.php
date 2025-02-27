<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocaBox</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>

<header>
    <nav>
        <ul class="menu">
            <li><a href="<?php echo site_url('Vitrine_Controller/index'); ?>" 
                   class="<?php echo ($this->uri->segment(1) == 'Vitrine_Controller') ? 'active' : ''; ?>">
                   Accueil
                </a>
            </li>

            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <!-- Utilisateur NON connecté -->
                <li><a href="<?php echo site_url('Identification_Controller/identification'); ?>"
                       class="<?php echo ($this->uri->segment(2) == 'identification') ? 'active' : ''; ?>">
                       Connexion
                    </a>
                </li>
            <?php else : ?>
                <!-- Utilisateur CONNECTÉ -->
                <li><a href="<?php echo site_url('User_Controller/changement_mdp'); ?>"
                       class="<?php echo ($this->uri->segment(2) == 'changement_mdp') ? 'active' : ''; ?>">
                       Changer de mot de passe
                    </a>
                </li>

                <?php if ($this->session->userdata('admin')) : ?>
                    <!-- Admin uniquement -->
                    <li><a href="<?php echo site_url('Admin_Controller/dashboard'); ?>"
                           class="<?php echo ($this->uri->segment(2) == 'dashboard') ? 'active' : ''; ?>">
                           Tableau de bord Admin
                        </a>
                    </li>
                <?php endif; ?>

                <li><a href="<?php echo site_url('Identification_Controller/logout'); ?>">Déconnexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
