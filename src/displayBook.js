var currentPage = 0;
var model;
var nbPage = 0;
var mode = 2;
var HTMLModel;
var enterKeyCallBack = null;



var gotoPage = function (page) {
    currentPage = page;
    
    $("#droiteContent").html("");
    $("#gaucheContent").html("");
    $("#doubleGaucheContent").html("");
    $("#doubleDroiteContent").html("");

    if (currentPage != 0 && currentPage != nbPage - 1 && mode == 2 && currentPage % 2 == 0) {
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
    $("#" + id).html(HTMLModel[ number ]);
    $(".pageNumber").click(function () {
        gotoPage(parseInt($(this).text()));
    });
    $(".listIndex").click(function () {
        gotoRecette(undefined, parseInt($(this).attr("attribute")));
    });
    var divs = $("#" + id).find("div,img");
    for (idxDiv = 0; idxDiv < divs.length; idxDiv++) {
        divId = $(divs[idxDiv]).attr('id');
        if (divId!= undefined && divId.indexOf("recette_") == 0 ) {
            $(divs[idxDiv]).attr('id', "current_" + divId);
        }
    }


    if ($("#current_recette_id").text() != "" && (userData.admin == "1" || userData.admin == "2" || $("#current_recette_userID").text() == userData.userID )) {
        $("#modify").show();
    } else {
        $("#modify").hide();
    }
}
function handleImgError(img) {
    if ($(img).attr("id") == "current_recette_img_plat") {
        $("#current_recette_img_plat").attr('src', 'img/plat.jpg');
    }
    if ($(img).attr("id") == "current_recette_img_chef") {
        $("#current_recette_img_chef").attr('src', 'img/chef.jpg');
    };
}


function loadModel(callBack) {
    $.ajax({
        method: "GET",
        url: "page.php?" + Math.random(),
        success: function (data) {
            HTMLModel = data.split("BOUNDARY--MODEL");
            $("#fullModel").html(HTMLModel[HTMLModel.length-1]);
            nbPage = model.length;
        },
        complete: function () {
            if (callBack != null) {
                callBack.call();
            }
        }
    });
}


function messageBox(text, okCallBack, cancelCallBack) {
    $("#message_ok").unbind("click");
    $("#message_cancel").unbind("click");
    if (okCallBack == undefined) {
        $("#message_ok").click(function () {
            $("#message").hide();
            enterKeyCallBack = null;
        });
    } else {
        $("#message_ok").click(function () {
            okCallBack.call();
            $("#message").hide();
            enterKeyCallBack = null;
        });
    }

    if (cancelCallBack == undefined) {
        $("#message_cancel").hide();
    } else {
        $("#message_cancel").show();
        $("#message_cancel").click(function () {
            cancelCallBack.call();
            $("#message").hide();
            enterKeyCallBack = null;
        });
    }
    enterKeyCallBack = function (){
        $("#message_ok").click();
        enterKeyCallBack = null;
    }
    $("#message_text").html(text);
    $("#message").show();
}


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


    $("#pageToggle").click(function () {
        $("#page1").toggle();
        $("#page2").toggle();
        $("#txtPage2").toggle();
        $("#txtPage1").toggle();

    });

    $(window).on('resize', function () {
        calcZoom();
    });

    $(window).keyup( function(event) {
        if (event.keyCode === 13 && enterKeyCallBack!=null) {
            enterKeyCallBack.call();
        }
    });

    calcZoom();

    // START
    loadModel(function () { gotoPage(0); });

});