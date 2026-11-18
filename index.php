<?php
/**
 * index.php — Point d'entrée unique AgriSmart Back Office
 * URL : http://localhost/agrismart/index.php?page=dashboard
 */

session_start();

/* Simuler session admin pour la démo (remplacé par AuthController Membre 1) */
$_SESSION['username'] = $_SESSION['username'] ?? 'Admin AgriSmart';
$_SESSION['role']     = $_SESSION['role']     ?? 'admin';

/* Charger le contrôleur de base */
require_once __DIR__ . '/app/Controllers/Controller.php';

/* Charger tous les contrôleurs */
require_once __DIR__ . '/app/Controllers/DashboardController.php';
require_once __DIR__ . '/app/Controllers/ExpertsController.php';
require_once __DIR__ . '/app/Controllers/ConsultationsController.php';
require_once __DIR__ . '/app/Controllers/RendezVousController.php';
require_once __DIR__ . '/app/Controllers/ConseilsController.php';

/* Charger les routes */
$routes = require_once __DIR__ . '/app/routes.php';

/* Lire la page demandée */
$page = $_GET['page'] ?? 'dashboard';

/* Dispatcher */
if (isset($routes[$page])) {
    [$controllerClass, $method] = $routes[$page];
    $controller = new $controllerClass();
    $controller->$method();
} else {
    /* 404 */
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">
    <title>404</title>
    <link rel="stylesheet" href="app/Views/assets/css/agrismart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head><body style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;gap:1rem">
    <h1 style="font-size:4rem;color:#17352A">404</h1>
    <p>Page introuvable.</p>
    <a href="index.php?page=dashboard"
       style="background:#2C7A46;color:#fff;padding:.6rem 1.4rem;border-radius:8px;font-weight:600;text-decoration:none">
       ← Dashboard</a>
    </body></html>';
}
