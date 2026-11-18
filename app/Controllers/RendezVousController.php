<?php
require_once __DIR__ . '/Controller.php';

class RendezVousController extends Controller
{
    protected string $page  = 'rendez_vous';
    protected string $title = 'Rendez-vous | AgriSmart';

    public function index(): void
    {
        $rdvs = [
            ['id'=>1,'consultation'=>'Maladie du blé',  'agriculteur'=>'Mohamed Ali','expert'=>'Dr. Sami Ben', 'date_heure'=>'2026-11-25 10:00','mode'=>'en_ligne', 'statut'=>'planifie'],
            ['id'=>2,'consultation'=>'Irrigation',       'agriculteur'=>'Fatma Zahra','expert'=>'Ing. Leila K.','date_heure'=>'2026-11-28 14:30','mode'=>'sur_place','statut'=>'planifie'],
            ['id'=>3,'consultation'=>'Fertilisation',    'agriculteur'=>'Hichem Mrad','expert'=>'Dr. Sami Ben', 'date_heure'=>'2026-11-10 09:00','mode'=>'en_ligne', 'statut'=>'termine'],
        ];
        $consultationsAcceptees = [
            ['id'=>2,'label'=>'[#2] Irrigation — Fatma Zahra'],
        ];
        $errors = []; $old = [];
        $this->render('rendez_vous', compact('rdvs', 'consultationsAcceptees', 'errors', 'old'));
    }
}
