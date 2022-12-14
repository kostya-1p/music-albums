import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

let typingTimer;
const doneTypingInterval = 2000;
const $input = $('#name');
const $imageInput = $('#image');
let artistsInfo;

$(document).ready(function () {
    $input.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTypingArtist, doneTypingInterval);
    });

    $input.on('keydown', function () {
        clearTimeout(typingTimer);
    });

    $imageInput.on('change', function () {
        loadInputFieldToPreview($('#artist_image_preview'));
    });

    $(".backup_picture_artist").on("error", function () {
        $(this).attr('src', 'http://localhost:8000/images/artists/alternative.png');
        if($(this).attr('id') === 'artist_image_preview') {
            loadURLToInputFile($('#artist_image_preview').attr('src'));
        }
    });

    if($('#artist_image_preview').attr('src') !== '') {
        loadURLToInputFile($('#artist_image_preview').attr('src'));
    }
});

function getImageLink(artistName, artists) {
    for (let i = 0; i < artists.length; i++) {
        if (artists[i]['name'] === artistName) {
            return artists[i]['image'];
        }
    }

    return '';
}

function loadInputFieldToPreview(imgElement) {
    const reader = new FileReader();
    reader.onload = function (e) {
        imgElement.attr('src', e.target.result);
    }
    reader.readAsDataURL(document.querySelector('#image').files[0]);
}

function doneTypingArtist() {
    const artistName = $input.val();

    $.ajax({
        url: `http://localhost:8000/api/artist_lastfm/${artistName}`,
        type: "GET",
        success: function (artists) {
            artistsInfo = artists;
            setDataToDataList('#nameList', getArrayOfNames(artists));
            const artistName = $input.val();
            let imageLink = getImageLink(artistName, artists);

            if (imageLink !== '')
                loadURLToInputFile(imageLink);
        }
    });
}

function getArrayOfNames(artists) {
    const names = [];
    artists.forEach(artist => {
        names.push(artist['name']);
    });
    return names;
}

function setDataToDataList(dataListId, data) {
    const $dataList = $(dataListId);
    $dataList.empty();
    data.forEach(element => {
        $dataList.append(`<option>${element}</option>`);
    });
}

function loadURLToInputFile(url) {
    getImgURL(url, (imgBlob) => {
        let fileName = 'image.' + imgBlob.type.split("/").pop();
        let file = new File([imgBlob], fileName, {type: imgBlob.type, lastModified: new Date().getTime()}, 'utf-8');
        let container = new DataTransfer();

        container.items.add(file);
        $imageInput[0].files = container.files;
    })
}

function getImgURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        callback(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}
