$(document).ready(function() {
    // Initialize carousel with options
    $('#carouselExampleControls').carousel({
        interval: 3000,  
        pause: "hover",  
        wrap: true       
    });
    
    // Optional: Add event listeners for debugging
    $('#carouselExampleControls').on('slide.bs.carousel', function () {
        console.log('Slide transition started');
    });
    
    $('#carouselExampleControls').on('slid.bs.carousel', function () {
        console.log('Slide transition completed');
    });
});


/// Function for recherche resulta search




