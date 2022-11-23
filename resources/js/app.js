import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

let typingTimer;
const doneTypingInterval = 2000;
const $input = $('#name');
const $artistInput = $('#artist');
const $imageLinkInput = $('#image');
let artistsInfo;

$(document).ready(function () {
    if ($('#albumId').length) {
        doneTypingAlbum();
    }

    $input.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTypingAlbum, doneTypingInterval);
    });

    $input.on('keydown', function () {
        clearTimeout(typingTimer);
    });

    $artistInput.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTypingArtist, doneTypingInterval);
    });

    $artistInput.on('keydown', function () {
        clearTimeout(typingTimer);
    });

    $imageLinkInput.on('change', function () {
        const link = $imageLinkInput.val();
        $('#albumImagePreview').attr('src', link);
    });

    $('#name, #artist').on('blur', function () {
        if ($input.val().length !== 0 && $artistInput.val().length !== 0) {
            setDescription();
        }
    });

    $(".backup_picture_album").on("error", function () {
        $(this).attr('src', 'http://localhost:8000/images/albums/alternative.png');
    });

    $(".backup_picture_artist").on("error", function () {
        $(this).attr('src', 'http://localhost:8000/images/artists/alternative.png');
    });
});

function setDescription() {
    const albumName = $input.val();
    const artistName = $artistInput.val();

    $.ajax({
        url: `http://localhost:8000/api/album_lastfm/description/${albumName}/${artistName}`,
        type: "GET",
        success: function (descriptionJSON) {
            const $descriptionTag = $('#description');
            $descriptionTag.empty();
            $descriptionTag.append(descriptionJSON.description);
        }
    });
}

function doneTypingAlbum() {
    const albumName = $input.val();

    $.ajax({
        url: `http://localhost:8000/api/album_lastfm/${albumName}`,
        type: "GET",
        success: function (artistsImagesJson) {
            artistsInfo = artistsImagesJson;
            setDataToDataList('#artistList', Object.keys(artistsImagesJson));
        }
    });
}

function doneTypingArtist() {
    const artistName = $artistInput.val();
    if (artistName in artistsInfo) {
        $input.val(artistsInfo[artistName].album);
    }
}

function setDataToDataList(dataListId, data) {
    const $dataList = $(dataListId);
    $dataList.empty();
    data.forEach(element => {
        $dataList.append(`<option>${element}</option>`);
    });
}
