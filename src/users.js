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
                if (userData.admin == "1") {
                    displayName += "<span style='color:red; margin-left:10px; '>Relecteur</span>";
                }
                $(".childName").html(displayName);
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
}

$(document).ready(function () {

    $("#identify").click(function () {
        if (!userFormOpen) {
            userFormSize = { "width": $("#identify").css("width"), "height": $("#identify").css("height") };
            $("#userForm").animate({ "height": "400px", "width": "400px" }, function () {
                $("#closeUserForm").show();
                if (userAuthentificated) {
                    $("#loggedUserForm").show();
                } else {
                    $("#loginform").show();
                }

            });
            userFormOpen = true;

        }
    });
    $("#closeUserForm").click(function () {
        closeUserForm();
    });


    $("#dologin").click(function () {
        checkCredentials();
    });

    messageBox("Si vous êtes parent d'élève veuillez vous identifier pour apporter votre contribution à ce livre, en cliquant sur le bouton 'Mon espace'.");
});
