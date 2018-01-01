<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <title>Livre de recettes</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="img/toque.png">
    <link rel="shortcut icon" type="image/x-icon" href="img/toque.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <link rel="apple-touch-icon" href="img/toque.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="img/toque-72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="img/toque-114.png" />

    <link rel="stylesheet" type="text/css" href="index.css" />

    <style>
        @font-face {
            font-family: latoLight;
            src: url(font/latolight.woff);
        }
    </style>

    <script src="lib/jquery-3.2.1.min.js"></script>
    <script src="lib/canvas-to-blob.min.js"></script>
    <script src="lib/resize.js"></script>
    <script src="users.js"></script>
    <script src="displayBook.js"></script>
    <script src="recetteEdit.js"></script>


</head>
<?php
include 'connect.php';
$connection = connect();
logActivity($connection, "load page");
?>

<body style="font-family: Arial; font-size:12px;">
    <div id="topBar" style="padding:10px; font-size: 20px;top:0px; left:0px; right:0px;  text-align:center; ">Livre de recettes - APE Le Drennec 2018</div>

    <div id="backgroundBook" style="position:absolute;padding:20px; top:50px; left:0px; right:0px; bottom:0px; ">


        <div id="double" style="display:none;position:relative;   width:423mm; height:148mm; margin:auto;">

            <div style="position:absolute;top:0px;bottom:0px; " class="pageBook">
                <div id="doubleGaucheContent"></div>
            </div>

            <div style="position:absolute;top:0px;bottom:0px; left:213mm;" class="pageBook">
                <div id="doubleDroiteContent"></div>
            </div>
            <img src="img/spirale-2pages.png" alt="" style="position:absolute;height:100%;left:203mm;">
            <img src="img/suivant.png" alt="suivant" class="next">
            <img src="img/precedent.png" alt="precedent" class="prev">
            <img src="img/last.png" alt="dernier" class="last">
            <img src="img/first.png" alt="premier" class="first">

        </div>

        <div id="droite" style="display:none;position:relative; width:210mm; height:148mm;margin:auto;" class="pageBook">
            <img src="img/spirale-1page.png" alt="" style="position:absolute;height:100%;left:-20px;">
            <img src="img/suivant.png" alt="suivant" class="next">
            <img src="img/last.png" alt="dernier" class="last">
            <div id="droiteContent"></div>
        </div>

        <div id="gauche" style="display:none;position:relative; width:210mm; height:148mm;margin:auto; " class="pageBook">
            <img src="img/spirale-1page-d.png" alt="" style="position:absolute;height:100%;right:-20px;">
            <img src="img/precedent.png" alt="precedent" class="prev">
            <img src="img/first.png" alt="premier" class="first">
            <div id="gaucheContent"></div>
        </div>
    </div>

    <!-- ########################################################## -->
    <div id="fillForm" style="display:none;position:absolute; left:0px; top:0px; right:0px; bottom:0px; background-color: rgba(0,0,0,.9)">


        <div style="position:absolute; left:0px; top:40px; right:0px; bottom:0px; width:210mm; height:160mm; background-color:#FFFFFF;font-family:latoLight;margin:auto;">
            <div style="display:inline-block;margin:10px; font-size:16px;">Categorie:</div>
            <select id="edit_categorie" style="display:inline-block;margin:10px; font-size:16px;">
                <option value="-1">Sélectionnez la catégorie de recette</option>
                <option value="0">Entrée</option>
                <option value="1">Plat</option>
                <option value="2">Dessert</option>
                <option value="3">Divers</option>
            </select>
            <div id="page1">
                <div style="display:none;" id="edit_id"></div>
                <div style="display:none;" id="edit_userID"></div>
                <input id="edit_titre" style="position:absolute; text-align:center; left:1mm; top:10mm; width:208mm; font-size:30pt; color:#D2691E; "
                    class="fillArea" placeholder="Titre de la recette">
                <div style="position:absolute; text-align:left; left:10mm; top:25mm; width:120mm; font-size:16pt; color:#A2D21E; ">Présentation</div>

                <textarea id="edit_presentation" style="position:absolute; text-align:left; left:10mm; top:32mm; width:180mm; " class="fillArea" placeholder="Présentation de la recette"></textarea>
                <div style="position:absolute; text-align:left; left:10mm; top:50mm; width:50mm; font-size:16pt; color:#A2D21E; ">Ingrédients</div>
                <textarea id="edit_ingredients" style="position:absolute; text-align:left; left:10mm; top:57mm; width:50mm; height:60mm;"
                    class="fillArea" placeholder="Liste des ingrédients"></textarea>
                <div style="position:absolute; text-align:left; left:70mm; top:50mm; width:120mm; font-size:16pt; color:#A2D21E; ">Préparation</div>
                <textarea id="edit_preparation" style="position:absolute; text-align:left; left:70mm; top:57mm; width:120mm; height:60mm;"
                    class="fillArea"  placeholder="Détails de la préparation"></textarea>
                <div style="position:absolute; text-align:left; left:10mm; top:120mm; width:180mm; font-size:16pt; color:#A2D21E; ">Indications</div>
                <textarea id="edit_indications" style="position:absolute; text-align:left; left:10mm; top:127mm; width:180mm; height:7mm;"
                    class="fillArea" placeholder="Nombre de personnes, temps de préapration ..."></textarea>
            </div>
            <div id="page2" style="display:none;">
                <input id="edit_nom" style="position:absolute; text-align:right; left:0mm; top:20mm; width:120mm; font-size:30pt; color:#D2691E; "
                    class="fillArea" placeholder="Nom du ou des chefs">
                <div style="position:absolute; text-align:right; left:0mm; top:10mm; width:120mm; font-size:16pt; color:#A2D21E; ">Le chef</div>

                <img id="img_plat" src="img/plat.jpg" style="position:absolute; left:20mm; top:45mm; width:130mm;" onerror="this.src='img/plat.jpg'">
                <div id="file_plat" class="dropzone" style="position:absolute; left:23mm; top:49mm; width:116mm; height:82mm; line-height:82mm; vertical-align: middle;">
                        <span class="imageZoneText">Cliquez ici pour choisir la photo du plat !</span>
                </div>
                <img id="img_chef" src="img/chef.jpg" style="position:absolute; left:130mm; top:10mm; width:60mm;" onerror="this.src='img/chef.jpg'">
                <div id="file_chef" class="dropzone" style="position:absolute; left:133mm; top:13mm; width:48mm; height:48mm; line-height:48mm; vertical-align: middle; ">
                    <span class="imageZoneText">Cliquez ici pour choisir la photo du chef !</span>
                </div>

                <input type="file" id="img_plat_selector" name="img_platfile" style="display:none;" accept="image/*" />
                <input type="file" id="img_chef_selector" name="img_platfile" style="display:none;" accept="image/*" />

            </div>
            <div style="position:absolute; bottom:0px; margin:auto; padding:10px;">
                <div id="validate" class="button"> Valider </div>
                <div id="cancel" class="buttonCancel"> Annuler </div>
            </div>
            <div style="position:absolute; bottom:0px; right:10px; padding:10px;">
                <div id="pageToggle" class="button" >
                    <span id="txtPage2">Page 2 &gt;&gt;</span>
                    <span id="txtPage1" style="display:none">&lt;&lt; Page 1</span>
                </div>

            </div>
        </div>
    </div>
    <!-- ########################################################## -->
    <div id="fullModel"></div>
    <!-- ########################################################## -->
    <div id="modify" class="button" style="position: absolute; top:10px; left:130px;">Modifier la recette </div>
    <div id="adminButton" class="button" style="display:none; position:fixed;top:10px; right:10px;">Admin</div>

    <div id="userForm" class="button" style="position: absolute; top:10px; left:10px;">
    <div id="identify" style="display:inline-block;">Mon espace</div>
    
        <div id="closeUserForm" style="display:none;position:absolute;top:0px; left:0px; height:25px;width:100%;text-align:right;">
            <div style="padding:5px; font-Size:15px">X</div>
        </div>
        <!-- ++++++++++++++++++++++++++ -->
        <div id="loginform" class="innerForm" style="display:none;">

            <p>
                Les informations d'identifications vous ont été communiquées
                <br>dans le cahier de votre enfant.
                <br>
                <br> Identifiant
                <br>
                <input type="text" id="login" placeholder="Identifiant" name="uid" value="sferelloc">

                <p>
                    Mot de passe
                    <br>
                    <input type="password" id="password" name="upass" placeholder="******" value="19062010">

                    <br>
                    <input type="submit" id="dologin" value="Valider">

        </div>
        <div id="loggedUserForm" class="innerForm" style="display:none;" >
            <div class="childName"></div>
            <hr style="position: absolute; top:26px; width:370px" >
<!-- 
            <div style="position: absolute; top:30px; width:370px; padding-top:10px;">
                    <div>Adresse mail :</div><input type="text" id="email" size="40">
                    <div style="margin-top:5px;" >Précommandes de livres :</div><input type="text" id="Preco" size="4" >
                </div>
                 <hr style="position: absolute; top:40px; width:370px" >
            -->       
           
            <div id="create" class="button" style="position: absolute; top :40px; left:10px;"> Créer une recette &gt;&gt;</div>
            <div id="myRecette" style="position: absolute; top:90px; width:370px">Voir mes recettes : </div>
            <div id="userRecetteList" style="position: absolute; top:120px; width:370px">

            </div>
            
            
            

        </div>
        <!-- ++++++++++++++++++++++++++ -->
    </div>
    <!-- ########################################################## -->
    <div id="message" style="display:none;position:absolute; left:0px; top:0px; right:0px; bottom:0px; background-color: rgba(0,0,0,.9)">
        <div style="position:absolute; left:0px; top:0px; right:0px; bottom:0px; width:150mm; height:100mm; background-color:#FFFFFF;margin:auto;border-radius: 5px;">
            <div class="" style="position:absolute; width:150mm; height:100mm; line-height:100mm; text-align: center; ;vertical-align: middle; ">
                <span id="message_text" style="display: inline-block; font-size:14px; text-align: center;vertical-align: middle; line-height: 25px;padding:10mm;">Message</span>
            </div>
            <div id="message_ok" class="button" style="position: absolute; bottom:10px; left:10px;"> Ok </div>
            <div id="message_cancel" class="buttonCancel" style="position: absolute; bottom:10px; left:50px;"> Annuler </div>
        </div>
    </div>


    <div id="loading" style="display:none;position:absolute; left:0px; top:0px; right:0px; bottom:0px; background-color: rgba(255,255,255,.9)">
        <img src="img/wait.gif" style=" position: absolute; margin: auto; top: 0; left: 0; right: 0; bottom: 0;">
    </div>
    <!-- ########################################################## -->



    <!-- ########################################################## -->


</body>

</html>