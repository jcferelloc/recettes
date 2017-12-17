var currentPage = 0;
var model;
var nbPage = 0;
var mode = 2;

var sequence = [];
var sequenceID = 0;

var gotoPage = function (page) {
    currentPage = page;
    if ( currentPage != 0 && currentPage != nbPage - 1 && mode == 2 && currentPage % 2 == 0 ){
        currentPage -= 1;
    }
    if (currentPage == 0 || (mode == 1 && currentPage % 2 == 0)) {

        $("#double").hide();
        $("#gauche").hide();

        $("#droite").show();

        loadPage(currentPage, "droiteContent");
    }
    else if (currentPage == nbPage - 1 || mode == 1 && currentPage % 2 == 1) {
        $("#droite").hide();
        $("#double").hide();

        $("#gauche").show();

        loadPage(currentPage, "gaucheContent");
    }
    else if (mode == 2) {
        $("#droite").hide();
        $("#gauche").hide();

        $("#double").show();

        loadPage(currentPage, "doubleGaucheContent");
        loadPage(currentPage + 1, "doubleDroiteContent");
    }
}

function gotoRecette(callBack, recetteID) {
    for (idxPage = 0; idxPage < model.length; idxPage++) {
        if (model[idxPage].recette_id == recetteID) {
            gotoPage(idxPage);
        }
    }
}

var calcZoom = function () {
    var w = 210;
    var h = 149;
    if (mode == 2) {
        w *= 2;
    }
    var bodyWidth = ($('#backgroundBook').width() - 140) * 0.264583333333334;
    var bodyHeight = ($('#backgroundBook').height() - 20) * 0.264583333333334;
    var zoom = "" + Math.min(bodyWidth / w, bodyHeight / h) * 100 + "%";
    console.log("zoom:" + zoom);
    $("#double").css({ zoom: zoom });
    $("#gauche").css({ zoom: zoom });
    $("#droite").css({ zoom: zoom });
}

var loadPage = function (number, id) {
    $("#" + id).html("");
    $.ajax({
        method: "GET",
        url: "page.php?format=html&number=" + number + "&" + Math.random(),
        success: function (data) {
            $("#" + id).html(data);
            $(".pageNumber").click(function () {
                gotoPage($(this).text());
            });
            if ($("#recette_id").text() != "") {
                $("#modify").show();
            } else {
                $("#modify").hide();
            }
        }
    });
};

function uploadAll() {
    if (sequenceID == -1) {
        $("#loading").show();
    }
    if (sequenceID >= sequence.length - 1) {
        $("#loading").hide();
        return;
    }
    sequenceID++;
    sequence[sequenceID][0].call(undefined, uploadAll, sequence[sequenceID][1], sequence[sequenceID][2]);

}

function checkRecette() {
    var recetteID = 0;
    var action = "none";

    if ($("#edit_id").text() == "") {
        recetteID = (new Date).getTime() - 1513379407458;
        action = "new";
    } else {
        recetteID = $("#edit_id").text();
        action = "update";
    }
    var uploadOK = true;
    var errorMessage = "Merci de renseigner : <br>";
    if ($("#edit_nom").val() == "") {
        uploadOK = false;
        errorMessage += "- Le nom du chef<br>"
    }

    if ($("#edit_titre").val() == "") {
        uploadOK = false;
        errorMessage += "- Le titre  de la recette<br>"
    }

    if ($("#edit_presentation").val() == "") {
        uploadOK = false;
        errorMessage += "- La présentation de la recette<br>"
    }

    if ($("#edit_ingredients").val() == "") {
        uploadOK = false;
        errorMessage += "- Les ingrédients<br>"
    }

    if ($("#edit_preparation").val() == "") {
        uploadOK = false;
        errorMessage += "- Les explications de préparation<br>"
    }

    if ($("#edit_indications").val() == "") {
        uploadOK = false;
        errorMessage += "- Les indications complémentaire<br>"
    }

    if ($("#edit_categorie").val() == "-1") {
        uploadOK = false;
        errorMessage += "- La catégorie dans laquelle la recette doit se trouver<br>"
    }

    if (uploadOK) {
        message_text = "Votre recette a été sauvegardée. <br>"

        if ($('#img_plat_selector')[0].files.length == 1 || $('#img_chef_selector')[0].files.length == 1) {
            if ($('#img_plat_selector')[0].files.length == 1 && $('#img_plat_selector')[0].files[0].size > 2 * 1024 * 1024) {
                message_text += "La photo du plat n'a pas été sauvegardée car elle est trop volumineuse (max 2 Mega octets)<br>";
            }
            if ($('#img_chef_selector')[0].files.length == 1 && $('#img_chef_selector')[0].files[0].size > 2 * 1024 * 1024) {
                message_text += "La photo du chef n'a pas été sauvegardée car elle est trop volumineuse (max 2 Mega octets)<br>";
            }
        }

        if ($('#img_plat_selector')[0].files.length == 0 && $("#img_plat").attr('src') == "img/plat.jpg" &&
            $('#img_chef_selector')[0].files.length == 0 && $("#img_chef").attr('src') == "img/chef.jpg") {
            message_text += "Pensez à y ajouter les photos ! ";
        } else if ($('#img_plat_selector')[0].files.length == 0 && $("#img_plat").attr('src') == "img/plat.jpg") {
            message_text += "Pensez à y ajouter la photo du plat ! ";
        } else if ($('#img_chef_selector')[0].files.length == 0 && $("#img_chef").attr('src') == "img/chef.jpg") {
            message_text += "Pensez à y ajouter la photo du chef ! ";
        }
    }

    // if ( $('#img_plat_selector')[0].files[0].size > 2 * 1024 * 1024 ){}





    if (!uploadOK) {
        message_text = errorMessage;
    }

    $("#message_text").html(message_text);
    $("#message").show();

    if (!uploadOK) {
        return false;
    }

    sequenceID = -1;
    sequence = [
        [uploadRecette, recetteID, action],
        [uploadPhoto, "img_plat", recetteID],
        [uploadPhoto, "img_chef", recetteID],
        [loadModel],
        [gotoRecette, recetteID]
    ];

    return true;
}

function uploadRecette(callBack, recetteID, action) {

    var postData = "action=" + action;
    postData += "&id=" + recetteID;
    postData += "&nom=" + $("#edit_nom").val();
    postData += "&userID=" + $("#edit_userID").text();
    postData += "&titre=" + $("#edit_titre").val();
    postData += "&presentation=" + $("#edit_presentation").val();
    postData += "&ingredients=" + $("#edit_ingredients").val();
    postData += "&preparation=" + $("#edit_preparation").val();
    postData += "&indications=" + $("#edit_indications").val();
    postData += "&categorie=" + $("#edit_categorie").val();


    $("#loading").toggle();
    $.ajax({
        method: "POST",
        url: "updateRecette.php",
        data: postData,
        success: function (data) {

        },
        complete: function () {
            if (callBack != null) {
                callBack.call();
            }
        }
    });
}

function uploadPhoto(callBack, imgID, recetteID) {
    data = new FormData();
    data.append('name', imgID);
    data.append('recetteID', recetteID);
    data.append('file', $('#' + imgID + '_selector')[0].files[0]);
    $.ajax({
        url: "uploadPhoto.php", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData: false,        // To send DOMDocument or non processed data file it is set to false
        success: function (data)   // A function to be called if request succeeds
        {
            console.log(data);
        },
        complete: function () {
            if (callBack != null) {
                callBack.call();
            }
        }
    });
}

function loadPhoto(evt, imgID) {
    var tgt = evt.target || window.event.srcElement,
        files = tgt.files;

    // FileReader support
    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            document.getElementById(imgID).src = fr.result;
        }
        fr.readAsDataURL(files[0]);
    }

    // Not supported
    else {
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
    }
}

function loadModel(callBack) {
    $.ajax({
        method: "GET",
        url: "page.php?summary&" + Math.random(),
        success: function (data) {
            model = JSON.parse(data);
            nbPage = model.length;

        },
        complete: function () {
            if (callBack != null) {
                callBack.call();
            }
        }
    });
}

// START
loadModel();

$("document").ready(function () {

    $(".next").click(function () {
        if (currentPage >= nbPage - 1) {
            return;
        }
        if (currentPage == 0 || mode == 1) {
            currentPage++;
        } else {
            currentPage += 2;
        }
        gotoPage(currentPage);
    });
    $(".prev").click(function () {
        if (currentPage > 0) {
            if (currentPage == 1 || mode == 1) {
                currentPage--;
            } else {
                currentPage -= 2;
            }
            gotoPage(currentPage);
        }
    });

    $(".first").click(function () {
        gotoPage(0);
    });
    
    $(".last").click(function () {
        gotoPage(nbPage);
    });

    $("#create").click(function () {
        $("#edit_id").text("");
        $("#edit_userID").text(0);
        $("#edit_titre").val("");
        $("#edit_presentation").val("");
        $("#edit_ingredients").val("");
        $("#edit_preparation").val("");
        $("#edit_indications").val("");
        $("#edit_nom").val("");
        $("#edit_categorie").val(-1);
        $("#page1").show();
        $("#page2").hide();
        $("#txtPage2").show();
        $("#txtPage1").hide();
        document.getElementById("img_plat").src = "img/plat.jpg";
        document.getElementById("img_chef").src = "img/chef.jpg";

        $("#fillForm").show();
    });

    $("#modify").click(function () {
        $("#edit_id").text($("#recette_id").text());
        $("#edit_userID").text($("#recette_userID").text());
        $("#edit_titre").val($("#recette_titre").text());
        $("#edit_presentation").val($("#recette_presentation").text());
        $("#edit_ingredients").val($("#recette_ingredients").text());
        $("#edit_preparation").val($("#recette_preparation").text());
        $("#edit_indications").val($("#recette_indications").text());
        $("#edit_nom").val($("#recette_nom").text());
        $("#edit_categorie").val($("#recette_categorie").text());
        document.getElementById("img_plat").src = $("#recette_img_plat").attr('src');
        document.getElementById("img_chef").src = $("#recette_img_chef").attr('src');

        $("#page1").show();
        $("#page2").hide();
        $("#txtPage2").show();
        $("#txtPage1").hide();
        $("#fillForm").show();
    });

    $("#cancel").click(function () {
        $("#fillForm").hide();
    });

    $("#pageToggle").click(function () {
        $("#page1").toggle();
        $("#page2").toggle();
        $("#txtPage2").toggle();
        $("#txtPage1").toggle();

    });


    $("#validate").click(function () {
        if (!checkRecette()) {
            return;
        }

        uploadAll();

        $("#fillForm").hide();
    });

    $("#file_plat").click(function () {
        $("#img_plat_selector").trigger('click');
    });
    $("#file_chef").click(function () {
        $("#img_chef_selector").trigger('click');
    });

    $("#message_ok").click(function () {
        $("#message").hide();
    });

        
    document.getElementById('img_plat_selector').addEventListener('change', function (evt) { loadPhoto(evt, "img_plat") }, false);
    document.getElementById('img_chef_selector').addEventListener('change', function (evt) { loadPhoto(evt, "img_chef") }, false);


    $(window).on('resize', function () {
        calcZoom();
    })
    calcZoom();
    gotoPage(0);
});