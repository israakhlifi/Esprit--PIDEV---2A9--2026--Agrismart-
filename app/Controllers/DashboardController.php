<?php
require_once __DIR__ . '/Controller.php';

class DashboardController extends Controller
{
    protected string $page  = 'dashboard';
    protected string $title = 'Dashboard | AgriSmart';

    public function index(): void
    {
        $stats = [
            'experts'                  => 3,
            'consultations_en_attente' => 2,
            'rdv_a_venir'              => 2,
            'conseils_donnes'          => 3,
        ];
        $lastConsultations = [
            ['agriculteur'=>'Mohamed Ali',   'expert'=>'Dr. Sami Ben',   'sujet'=>'Maladie du blé',          'date_souhaitee'=>'2026-11-20','statut'=>'en_attente'],
            ['agriculteur'=>'Fatma Zahra',   'expert'=>'Ing. Leila K.',  'sujet'=>'Irrigation goutte-à-goutte','date_souhaitee'=>'2026-11-18','statut'=>'acceptee'],
            ['agriculteur'=>'Hichem Mrad',   'expert'=>'Dr. Sami Ben',   'sujet'=>'Fertilisation organique', 'date_souhaitee'=>'2026-11-15','statut'=>'terminee'],
            ['agriculteur'=>'Amira Bensalem','expert'=>'Ing. Youcef T.', 'sujet'=>'Rotation des cultures',   'date_souhaitee'=>'2026-11-22','statut'=>'en_attente'],
            ['agriculteur'=>'Khalil Mansour','expert'=>'Ing. Leila K.',  'sujet'=>'Traitement parasites',    'date_souhaitee'=>'2026-11-19','statut'=>'refusee'],
        ];
        $this->render('dashboard', compact('stats', 'lastConsultations'));
    }
}
