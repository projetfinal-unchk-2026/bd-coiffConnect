<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/SalonModel.php';
require_once ROOT_PATH . '/models/AvisModel.php';

class HomeController {
    private SalonModel $salonModel;
    private AvisModel  $avisModel;

    public function __construct() {
        $this->salonModel = new SalonModel();
        $this->avisModel  = new AvisModel();
    }

    public function index(): void {
        $salonsPopulaires = $this->salonModel->getPopulaires(4);
        $avisRecents      = $this->avisModel->getAll();
        $avisRecents      = array_slice($avisRecents, 0, 3);

        view('shared/home', compact('salonsPopulaires', 'avisRecents'));
    }
}
