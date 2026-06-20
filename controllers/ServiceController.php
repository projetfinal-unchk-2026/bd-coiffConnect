<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/ServiceModel.php';
require_once ROOT_PATH . '/models/SalonModel.php';

class ServiceController {
    private ServiceModel $model;
    private SalonModel   $salonModel;

    public function __construct() {
        $this->model      = new ServiceModel();
        $this->salonModel = new SalonModel();
    }

    public function index(): void {
        $salonId  = (int) ($_GET['salon_id'] ?? 0);
        $services = $this->model->getBySalon($salonId);
        jsonResponse(['services' => $services]);
    }

    public function getForSalon(): void {
        $salonId  = (int) ($_GET['salon_id'] ?? 0);
        $services = $this->model->getBySalon($salonId);
        jsonResponse(['services' => $services]);
    }
}
