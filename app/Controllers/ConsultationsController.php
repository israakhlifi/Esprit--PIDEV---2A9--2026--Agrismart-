<?php
require_once __DIR__ . '/Controller.php';

class ConsultationsController extends Controller
{
    protected string $page  = 'consultations';
    protected string $title = 'Consultations | AgriSmart';

    public function index(): void
    {
        $consultations = [
            ['id'=>1,'agriculteur'=>'Mohamed Ali',   'expert'=>'Dr. Sami Ben',  'sujet'=>'Maladie du blé',           'description'=>'Taches brunes sur les feuilles.','date_souhaitee'=>'2026-11-20','statut'=>'en_attente'],
            ['id'=>2,'agriculteur'=>'Fatma Zahra',   'expert'=>'Ing. Leila K.', 'sujet'=>'Irrigation goutte-à-goutte','description'=>'Optimiser pour 3 ha de tomates.','date_souhaitee'=>'2026-11-18','statut'=>'acceptee'],
            ['id'=>3,'agriculteur'=>'Hichem Mrad',   'expert'=>'Dr. Sami Ben',  'sujet'=>'Fertilisation organique',  'description'=>'Sol sableux déficient en azote.','date_souhaitee'=>'2026-11-15','statut'=>'terminee'],
            ['id'=>4,'agriculteur'=>'Amira Bensalem','expert'=>'Ing. Youcef T.','sujet'=>'Rotation des cultures',    'description'=>'Blé / légumineuses.','date_souhaitee'=>'2026-11-22','statut'=>'en_attente'],
            ['id'=>5,'agriculteur'=>'Khalil Mansour','expert'=>'Ing. Leila K.', 'sujet'=>'Traitement parasites',     'description'=>'Acariens sur vigne.','date_souhaitee'=>'2026-11-19','statut'=>'refusee'],
        ];
        $filtre = $_GET['filtre'] ?? '';
        $this->render('consultations', compact('consultations', 'filtre'));
    }
}
