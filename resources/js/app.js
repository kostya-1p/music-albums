import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

let typingTimer;
const doneTypingInterval = 2000;
const $input = $('#name');
const $artistInput = $('#artist');
const $imageLinkInput = $('#image');

$(document).ready(function () {
    if ($('#albumId').length) {
        doneTyping();
    }

    $input.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $input.on('keydown', function () {
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
});

function setDescription() {
    const albumName = $input.val();
    const artistName = $artistInput.val();

    $.ajax({
        url: `http://localhost:8000/search_description/${albumName}/${artistName}`,
        type: "GET",
        success: function (descriptionJSON) {
            const descriptionArray = JSON.parse(descriptionJSON);
            const $descriptionTag = $('#description');
            $descriptionTag.empty();
            $descriptionTag.append(descriptionArray['description']);
        }
    });
}

function doneTyping() {
    const albumName = $input.val();

    $.ajax({
        url: "http://localhost:8000/search/" + albumName,
        type: "GET",
        success: function (artistsImagesJson) {
            const artistImagesArray = JSON.parse(artistsImagesJson);
            setDataToDataList('#artistList', artistImagesArray['artists']);
            setDataToDataList('#imageList', artistImagesArray['images']);
        }
    });
}

function setDataToDataList(dataListId, data) {
    const $dataList = $(dataListId);
    $dataList.empty();
    data.forEach(element => {
        $dataList.append(`<option>${element}</option>`);
    });
}


