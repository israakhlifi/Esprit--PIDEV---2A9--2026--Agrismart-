<?php
require_once __DIR__ . '/Controller.php';

class ConseilsController extends Controller
{
    protected string $page  = 'conseils';
    protected string $title = 'Conseils | AgriSmart';

    public function index(): void
    {
        $conseils = [
            ['id'=>1,'consultation'=>'Maladie du blé','expert'=>'Dr. Sami Ben','contenu'=>'Appliquez un fongicide à base de triazole. Ventilez bien les rangées et évitez l\'excès d\'humidité.','created_at'=>'2026-11-16 09:12','consultationId'=>1],
            ['id'=>2,'consultation'=>'Irrigation',    'expert'=>'Ing. Leila K.','contenu'=>'Installez des goutteurs à 40 cm. Irriguez 2h le matin pour limiter l\'évapotranspiration.',          'created_at'=>'2026-11-17 14:00','consultationId'=>2],
            ['id'=>3,'consultation'=>'Fertilisation', 'expert'=>'Dr. Sami Ben','contenu'=>'Apportez 30 kg/ha de compost enrichi en azote avant le labour de printemps.',                         'created_at'=>'2026-11-14 11:30','consultationId'=>3],
        ];
        $consultations = [
            ['id'=>1,'label'=>'[#1] Maladie du blé — Mohamed Ali'],
            ['id'=>2,'label'=>'[#2] Irrigation — Fatma Zahra'],
            ['id'=>3,'label'=>'[#3] Fertilisation — Hichem Mrad'],
        ];
        $errors = []; $old = [];
        $this->render('conseils', compact('conseils', 'consultations', 'errors', 'old'));
    }
}
