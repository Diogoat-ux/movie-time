<link rel="stylesheet" href="../public/displayList.css">


<?php include 'header.php'; 

require_once '../controllers/MovieController.php';
require_once __DIR__ . '/../models/MovieModel.php'; 

$movieModel = new movieModel(); 
$controller = new MovieController(); 
?>

<script type="module">
    // Import the fetchLists function from the fetchLists.js file
    import { fetchLists } from '../Javascript/fetchLists.js';

    // Wait for the DOM to be fully loaded before running the function
    document.addEventListener('DOMContentLoaded', function() {
       
        fetchLists();
    });
</script>


<div id="lists-container" style="color: white;"></div>

<div id="movies-container" style="color: white;"></div>

<?php include 'footer.php'; ?>
