<?php
require_once __DIR__ . '/Controller.php';

class ExpertsController extends Controller
{
    protected string $page  = 'experts';
    protected string $title = 'Experts | AgriSmart';

    public function index(): void
    {
        $experts = [
            ['id'=>1,'nom'=>'Dr. Sami Ben Amor',   'specialite'=>'Phytopathologie',    'region'=>'Tunis',  'disponible'=>1],
            ['id'=>2,'nom'=>'Ing. Leila Khelifi',  'specialite'=>'Irrigation',         'region'=>'Sfax',   'disponible'=>1],
            ['id'=>3,'nom'=>'Ing. Youcef Trabelsi','specialite'=>'Agronomie générale', 'region'=>'Sousse', 'disponible'=>0],
        ];
        $this->render('experts', compact('experts'));
    }
}
