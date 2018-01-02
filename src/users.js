var userData = Object;
var userAuthentificated = false;
var userFormOpen = false;
var userFormSize = Object;
var classText = ["CM2", "CM1", "CE2", "CE1", "CP", "GS", "MS", "PS", "TPS", "Enseignant"];



function checkCredentials() {
    data = new FormData();
    data.append('login', $("#login").val());
    data.append('password', $("#password").val());

    $.ajax({
        url: "login.php",
        type: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            returnedValue = JSON.parse(data);
            if (returnedValue.status) {
                userAuthentificated = true;
                $("#loginform").hide();
                $("#loggedUserForm").show();
                userData.userID = returnedValue.userID;
                userData.nom = returnedValue.nom;
                userData.email = returnedValue.email;
                userData.prenom_1 = returnedValue.prenom_1;
                userData.nom_1 = returnedValue.nom_1;
                userData.classe_1 = returnedValue.classe_1;
                userData.date_1 = returnedValue.date_1;
                userData.admin = returnedValue.admin;
                var displayName = "<b>" + userData.prenom_1 + " " + userData.nom_1 + "</b> ( " + classText[userData.classe_1] + " )";
                if (userData.admin == "1" || userData.admin == "2") {
                    displayName += "<span style='color:red; margin-left:10px; '>Relecteur</span>";
                }
                if (userData.admin == "2") {
                    $(".adminButton").show();
                }
                $(".childName").html(displayName);
                refreshRecetteList();
            } else {
                messageBox("Les informations fournies ne permettent pas de vous identifier<br> En cas de soucis vous pouvez contacter l'APE : <a href='mailto:apeledrennec@gmail.com'>apeledrennec@gmail.com</a>.");
            }
            console.log(data);
        },
        complete: function () {

        }
    });
}

function closeUserForm() {
    $("#closeUserForm").hide();
    $("#loginform").hide();
    $("#loggedUserForm").hide();
    $("#userForm").animate({ "height": userFormSize.height, "width": userFormSize.width });
    userFormOpen = false;
    enterKeyCallBack = null;
}

function refreshRecetteList(callBack) {
    $("#userRecetteList").html("");
    for (let index = 0; index < model.length; index++) {
        const element = model[index];
        if (element.recette_userID == userData.userID) {
            elList = $('<div>');
            elList.addClass("userRecette");

            elList.attr("recette_id", element.recette_id);
            elList.text(element.recette_titre + " >>");
            $("#userRecetteList").append(elList);
        }
    }
    $(".userRecette").click(function () {
        gotoRecette(undefined, parseInt($(this).attr('recette_id')));
        closeUserForm();
    });
    if (callBack != null) {
        callBack.call();
    }
}

$(document).ready(function () {

    $("#userForm").click(function () {
        if (!userFormOpen) {
            userFormSize = { "width": $("#identify").css("width"), "height": $("#identify").css("height") };
            $("#userForm").animate({ "height": "400px", "width": "400px" }, function () {
                $("#closeUserForm").show();
                if (userAuthentificated) {
                    $("#loggedUserForm").show();
                } else {
                    $("#loginform").show();
                    enterKeyCallBack = checkCredentials;
                }

            });
            userFormOpen = true;
        } else {
            closeUserForm();
        }
    });

    $(".innerForm").click(function (event) {
        event.stopPropagation();
    });

    $("#dologin").click(function () {
        checkCredentials();
    });

    $("#statsButton").click(function () {
        $.ajax({
            url: "stats.php?js",
            type: "GET",
            success: function (data) {
                stats = JSON.parse(data);
                message = "<u>Statistiques</u>:<br>";
                message += "<li><b>" + stats.nbRecettes.NB +"</b> recettes ("
                stats.nbRecettesPerCateg.forEach(element => {
                    message += "<b>" + element.NB +"</b> ";
                    switch (element.categorie){
                        case "0" :
                        message += " Entrées, ";
                        break;
                        case "1" :
                        message += " Plats, ";
                        break;
                        case "2" :
                        message += " Desserts, ";
                        break;
                        case "3" :
                        message += " Autres";
                        break;
                    }
                });
                message += ")<br> ";
                if ( stats.nbRecettesMissingPhoto.NB > 0 ){
                    message += "<li>Il manque au moins une photo sur <b>" + stats.nbRecettesMissingPhoto.NB +"</b> recettes </li>";
                }else{
                    message += "<li>Toutes les recettes ont des photos</li>";
                }
                message += "<li><b>" + stats.visitorsTotal.NB +"</b> personnes sont venues sur le site (" + stats.visitorsTotalToday.NB + " aujourd'hui, "+ stats.visitorsTotalAweek.NB +" cette semaine) </li>";
                message += "<li>Le volume occupé par les photos est <b>" + stats.photoFolderSize.size +"</b></li>";
                
                
                messageBox(message);
            }
        })
    });

    $("#PDFButton").click(function () {
        window.open("generatePDF.php","PDFWINDOW", "location=0,menubar=0,status=0,fullscreen=1");
    });



    messageBox("Si vous êtes parent d'élève veuillez vous identifier pour apporter votre contribution à ce livre, en cliquant sur le bouton 'Mon espace'.");
});
