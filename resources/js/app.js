import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

$(document).ready(function () {
    $(".backup_picture_album").on('error', function () {
        $(this).attr('src', 'http://localhost:8000/images/albums/alternative.png');
    });

    $(".backup_picture_artist").on("error", function () {
        $(this).attr('src', 'http://localhost:8000/images/artists/alternative.png');
    });
});
