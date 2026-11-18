<?php
/**
 * app/Controllers/Controller.php
 * Contrôleur de base — fournit render() qui capture la vue et inclut layout.php.
 */
abstract class Controller
{
    /** Clé de la page courante, utilisée par la sidebar pour l'état actif */
    protected string $page  = 'dashboard';

    /** Titre affiché dans <title> et la navbar */
    protected string $title = 'AgriSmart';

    /**
     * Rend une vue dans le layout commun.
     *
     * @param string      $view         nom du fichier dans app/Views/pages/ (sans .php)
     * @param array       $data         variables à extraire dans la vue
     * @param string|null $scriptsView  nom d'un fichier de scripts spécifique (sans .php), ex: 'dashboard_scripts'
     */
    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        // Rendre le contenu de la page
        $content      = $this->capture(__DIR__ . '/../Views/pages/' . $view . '.php', $data);

        // Scripts spécifiques à la page (optionnel)
        $extraScripts = $scriptsView
            ? $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data)
            : '';

        $title       = $this->title;
        $currentPage = $this->page;

        require_once __DIR__ . '/../Views/layout.php';
    }

    /**
     * Exécute un fichier PHP et retourne son output sous forme de string.
     */
    private function capture(string $filePath, array $data = []): string
    {
        extract($data);
        ob_start();
        require $filePath;
        return ob_get_clean();
    }
}
