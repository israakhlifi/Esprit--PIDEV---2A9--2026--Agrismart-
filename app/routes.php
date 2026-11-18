<?php
/**
 * app/routes.php — Table de routage
 * Associe une clé de page au contrôleur et à la méthode à appeler.
 */
return [
    'dashboard'    => ['DashboardController',     'index'],
    'experts'      => ['ExpertsController',        'index'],
    'consultations'=> ['ConsultationsController',  'index'],
    'rendez_vous'  => ['RendezVousController',     'index'],
    'conseils'     => ['ConseilsController',       'index'],
];
