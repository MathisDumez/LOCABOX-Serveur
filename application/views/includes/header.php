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
            <li><a href="<?php echo site_url('vitrine/index'); ?>" 
                   class="<?php echo ($this->uri->segment(1) == 'Vitrine_Controller') ? 'active' : ''; ?>">
                   Accueil
                </a>
            </li>

            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <!-- Utilisateur NON connecté -->
                <li><a href="<?php echo site_url('id/identification'); ?>"
                       class="<?php echo ($this->uri->segment(2) == 'identification') ? 'active' : ''; ?>">
                       Connexion
                    </a>
                </li>
            <?php else : ?>
                <!-- Utilisateur CONNECTÉ mais pas admin -->
                <?php if (!$this->session->userdata('admin')) : ?>
                    <li><a href="<?php echo site_url('user/dashboard'); ?>"
                           class="<?php echo ($this->uri->segment(2) == 'dashboard_user') ? 'active' : ''; ?>">
                           Mes Réservations
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($this->session->userdata('admin')) : ?>
                    <!-- Admin uniquement -->
                    <li><a href="<?php echo site_url('admin/dashboard'); ?>"
                           class="<?php echo ($this->uri->segment(2) == 'dashboard') ? 'active' : ''; ?>">
                           Tableau de bord
                    </a></li>
                <?php endif; ?>

                <li><a href="<?php echo site_url('id/logout'); ?>">Déconnexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
