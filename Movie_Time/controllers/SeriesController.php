<?php
require_once __DIR__ . '/../models/SeriesModel.php';

class SeriesController {

    // Genre filtering (e.g. Action, Horror, etc.)
    public function filter($genre) {
        $seriesModel = new SeriesModel();
        
        $series = $seriesModel->filterByGenre($genre);
        $filteredSeries = (object)['results' => $series];

        include __DIR__ . '/../views/series.php';
    }

    // Displaying series details
    public function details($id) {
        $seriesModel = new SeriesModel();
        $details = $seriesModel->getDetails($id);
        $credits = $seriesModel->getCredits($id);
        include __DIR__ . '/../views/details.php';
    }

    public function createListWithSeries($idSeries, $nameList) {
        $seriesModel = new SeriesModel();
        $seriesModel->createListWithSeries($idSeries, 1, $nameList); // Utilisation de l'ID utilisateur 1 par défaut
    }

    public function displayLists() {
        $seriesModel = new SeriesModel();
        $lesListes = $seriesModel->displayLists();
        echo json_encode($lesListes);
    }

    public function getSeries($idList) {
        $seriesModel = new SeriesModel();
        $lesSeries = $seriesModel->getSeries($idList);
        echo json_encode($lesSeries);
    }

    public function deleteList($idList) {
        $seriesModel = new SeriesModel();
        $seriesModel->deleteList($idList);
    }

    public function addSeriesToList($idList, $idSeries) {
        $seriesModel = new SeriesModel();
        $seriesModel->addSeriesToList($idList, $idSeries);
    }
}
?>
