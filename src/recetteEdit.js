var sequence = [];
var sequenceID = 0;
var loggedIn = false;

// Initialise resize library
var resize = new window.resize();
resize.init();

function uploadAll() {
    if (sequenceID == -1) {
        $("#loading").show();
    }
    if (sequenceID >= sequence.length - 1) {
        $("#loading").hide();
        return;
    }
    sequenceID++;
    sequence[sequenceID][0].call(undefined, uploadAll, sequence[sequenceID][1], sequence[sequenceID][2], sequence[sequenceID][3]);
}

function checkRecette() {
    var recetteID = 0;
    var action = "none";
    var idPhotoPlat = (new Date).getTime() - 1513379407458;
    var idPhotoChef = (new Date).getTime() - 1513379407458;

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


    if (!uploadOK) {
        messageBox(errorMessage);
        return false;
    }




    message_text = "Votre recette a été sauvegardée. <br><br>";
    upload_img_plat = false;
    upload_img_chef = false;

    if ($('#img_plat_selector')[0].files.length == 1) {

        ratio = $("#img_plat").width() / $("#img_plat").height();
        if (ratio < (4 / 3)) {
            message_text += "La photo du plat n'a pas été sauvegardée car elle n'est pas en mode paysage.";
        } else {
            upload_img_plat = true;
        }
    } else {
        idPhotoPlat = "";
    }
    if ($('#img_chef_selector')[0].files.length == 1) {
        upload_img_chef = true;
    } else {
        idPhotoChef = "";
    }


    if ($('#img_plat_selector')[0].files.length == 0 && $("#img_plat").attr('src') == "img/plat.jpg" &&
        $('#img_chef_selector')[0].files.length == 0 && $("#img_chef").attr('src') == "img/chef.jpg") {
        message_text += "Pensez à y ajouter les photos ! ";
    } else if ($('#img_plat_selector')[0].files.length == 0 && $("#img_plat").attr('src') == "img/plat.jpg") {
        message_text += "Pensez à y ajouter la photo du plat ! ";
    } else if ($('#img_chef_selector')[0].files.length == 0 && $("#img_chef").attr('src') == "img/chef.jpg") {
        message_text += "Pensez à y ajouter la photo du chef ! ";
    }

    messageBox(message_text);
    sequenceID = -1;
    sequence = [
        [uploadRecette, recetteID, idPhotoPlat, idPhotoChef, action]
    ]
    if (upload_img_plat) sequence.push([uploadPhoto, "img_plat", recetteID + "_" + idPhotoPlat]);
    if (upload_img_chef) sequence.push([uploadPhoto, "img_chef", recetteID + "_" + idPhotoChef]);
    sequence.push([loadModel]);
    sequence.push([gotoRecette, recetteID]);


    return true;
}

function messageBox(text, okCallBack, cancelCallBack) {
    $("#message_ok").unbind("click");
    $("#message_cancel").unbind("click");
    if (okCallBack == undefined) {
        $("#message_ok").click(function () {
            $("#message").hide();
        });
    } else {
        $("#message_ok").click(function () {
            okCallBack.call();
            $("#message").hide();
        });
    }

    if (cancelCallBack == undefined) {
        $("#message_cancel").hide();
    } else {
        $("#message_cancel").click(function () {
            cancelCallBack.call();
            $("#message").hide();
        });
    }

    $("#message_text").html(text);
    $("#message").show();
}

function uploadRecette(callBack, recetteID, idPhotoPlat, idPhotoChef, action) {

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
    postData += "&idPhotoPlat=" + idPhotoPlat;
    postData += "&idPhotoChef=" + idPhotoChef;


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

    resize.photo($('#' + imgID + '_selector')[0].files[0], 1600, 'file', function (resizedFile) {
        data = new FormData();
        data.append('name', imgID);
        data.append('recetteID', recetteID);
        data.append('file', resizedFile);
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
    });
}

function loadPhoto(evt, imgID) {
    var tgt = evt.target || window.event.srcElement,
        files = tgt.files;

    // FileReader support
    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            $("#" + imgID + "").attr("src", fr.result);

        }
        fr.readAsDataURL(files[0]);
    }

    // Not supported
    else {
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
    }
}


function isEditRecetteModified() {
    if ($("#edit_id").text() != "") {
        return ($("#edit_titre").val() != $("#current_recette_titre").text() ||
            $("#edit_presentation").val() != $("#current_recette_presentation").text() ||
            $("#edit_ingredients").val() != $("#current_recette_ingredients").text() ||
            $("#edit_preparation").val() != $("#current_recette_preparation").text() ||
            $("#edit_indications").val() != $("#current_recette_indications").text() ||
            $("#edit_nom").val() != $("#current_recette_nom").text() ||
            $("#edit_categorie").val() != $("#current_recette_categorie").text() ||
            $("#img_plat").attr("src") != $("#current_recette_img_plat").attr('src') ||
            $("#img_chef").attr("src") != $("#current_recette_img_chef").attr('src'));
    } else {
        return ($("#edit_titre").val() != "" ||
            $("#edit_presentation").val() != "" ||
            $("#edit_ingredients").val() != "" ||
            $("#edit_preparation").val() != "" ||
            $("#edit_indications").val() != "" ||
            $("#edit_nom").val() != "" ||
            $("#edit_categorie").val() != -1);
    }
}


function checkEditRecetteChanges() {
    if (isEditRecetteModified()) {
        $("#validate").removeClass("buttonUnactive");
        $("#validate").addClass("button");
        $("#validate").unbind("click");
        $("#validate").click(function () {
            if (!checkRecette()) {
                return;
            }

            uploadAll();

            $("#fillForm").hide();
        });
    } else {
        $("#validate").addClass("buttonUnactive");
        $("#validate").removeClass("button");
        $("#validate").unbind("click");
    }
}

function checkImagePlatRatio() {
    ratio = $("#img_plat").width() / $("#img_plat").height();
    if (ratio < (4 / 3)) {
        messageBox("Veuillez utiliser une photo en mode paysage pour la photo du plat.");
    }
}

function checkLoggedIn() {
    if (!loggedIn) {
        var text = prompt("Veuillez entrer le mot de passe");
        if (text != null) {
            if (text == "123") {
                loggedIn = true;
            } else {
                alert("Mauvais mot de passe.");
            }
        }
    }
    return loggedIn;
}

$(document).ready(function () {
    $("#create").click(function () {
        if (!checkLoggedIn()) {
            return;
        }
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
        $("#img_plat").attr("src", "img/plat.jpg");
        $("#img_chef").attr("src", "img/chef.jpg");
        $("#img_plat_selector").val("");
        $("#img_chef_selector").val("");

        $("#fillForm").show();
    });

    $("#modify").click(function () {
        if (!checkLoggedIn()) {
            return;
        }
        $("#edit_id").text($("#current_recette_id").text());
        $("#edit_userID").text($("#current_recette_userID").text());
        $("#edit_titre").val($("#current_recette_titre").text());
        $("#edit_presentation").val($("#current_recette_presentation").text());
        $("#edit_ingredients").val($("#current_recette_ingredients").text());
        $("#edit_preparation").val($("#current_recette_preparation").text());
        $("#edit_indications").val($("#current_recette_indications").text());
        $("#edit_nom").val($("#current_recette_nom").text());
        $("#edit_categorie").val($("#current_recette_categorie").text());
        $("#img_plat").attr("src", $("#current_recette_img_plat").attr('src'));
        $("#img_chef").attr("src", $("#current_recette_img_chef").attr('src'));
        $("#img_plat_selector").val("");
        $("#img_chef_selector").val("");
        checkEditRecetteChanges();

        $("#page1").show();
        $("#page2").hide();
        $("#txtPage2").show();
        $("#txtPage1").hide();
        $("#fillForm").show();
    });

    $("#cancel").click(function () {
        if (isEditRecetteModified()) {
            messageBox("En poursuivant vous allez perdre vos modifications.<br> Voulez-vous continuer ?", function () {
                $("#fillForm").hide();
            }, function () { });
        } else {
            $("#fillForm").hide();
        }

    });

    $("#file_plat").click(function () {
        $("#img_plat_selector").trigger('click');
    });
    $("#file_chef").click(function () {
        $("#img_chef_selector").trigger('click');
    });

    $("#img_plat_selector").change(function (evt) {
        loadPhoto(evt, "img_plat");
        checkEditRecetteChanges();
    });
    $("#img_chef_selector").change(function (evt) {
        loadPhoto(evt, "img_chef");
        checkEditRecetteChanges();
    });

    $("#edit_titre").keyup(checkEditRecetteChanges);
    $("#edit_presentation").keyup(checkEditRecetteChanges);
    $("#edit_ingredients").keyup(checkEditRecetteChanges);
    $("#edit_preparation").keyup(checkEditRecetteChanges);
    $("#edit_indications").keyup(checkEditRecetteChanges);
    $("#edit_nom").keyup(checkEditRecetteChanges);
    $("#edit_categorie").keyup(checkEditRecetteChanges);
    $("#img_plat").on('load', checkEditRecetteChanges);
    $("#img_plat").on('load', checkImagePlatRatio);
    $("#img_chef").on('load', checkEditRecetteChanges);
    $("#img_chef").on('load', checkEditRecetteChanges);

});