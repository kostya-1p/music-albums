import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

let typingTimer;
const doneTypingInterval = 2000;
const $input = $('#name');
const $imageLinkInput = $('#image');

$(document).ready(function () {
    if($('#albumId').length){
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
});

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


