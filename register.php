<?php
require_once 'config.php';
if(isset($_SESSION['user_id']))
{
	header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="croppie.js"></script>
    <link rel="stylesheet" href="croppie.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body style="background-color: rgb(17, 17, 17);">

    <div class="register-form">

        <div class="basic-data register-box tab">
            <h1>Reģistrācija</h1>
            <input type="text" id="firstname" placeholder="Vārds" autocomplete="off">
            <span id="firstname_msg"></span>
            <input type="text" id="lastname" placeholder="Uzvārds" autocomplete="off">
            <span id="lastname_msg"></span>
            <select id="courses">
                <option value='' disabled selected>Kurss</option>
            </select>
            <span id="course_msg"></span>
            <span id="privacy_terms_msg"></span>
            <div class="privacy-policy">
                <input type="checkbox" id="acceptPrivacy">Piekrītu <span id="privacy">privātuma politikai</span><br><br>
                <input type="checkbox" id="acceptTerms" required>Piekrītu <span id="terms">noteikumiem</span><br><br>
                <input type="checkbox" id="defaultPicture" required><span id="defaultPicture">Izlaist profila attēla ievietošanu</span><br><br>
            </div>
            <div class="next skipImage">Nākamais</div>
        </div>

        <div id="croppieWindow" class="shadowbox center-content" style="display:none;">
            <div id="myform">
                <input type="file" name="fileToUpload" id="fileToUpload" style="display:none;" accept="image/jpeg,image/png,image/webp">
                <div id="vanilla-demo"><label for="fileToUpload" id="warn" class="center-content">Lūdzu, pievienojiet attēlu.</label></div>
                <div id="buttons-container">
                    <label for="fileToUpload" class="button">Izvēlēties Attēlu</label>
                    <input id="doneButton" class="button" type="button" value="Gatavs" class="vanilla-result" disabled="">
                </div>
            </div>
        </div>

        <div class="image-data register-box tab">
            <h1>Attēla pievienošana</h1>
            <div id="imageContainer">
                <img id="croppieImg" src="placeholder.png">
                <div id="addImg">+</div>
            </div>
            <span id="image_msg"></span>
            <div class="next">Nākamais</div>
            <div class="previous">Atpakaļ</div>
        </div>

        <div class="register-box login-data tab">
            <h1>Reģistrācija</h1>
            <input type="text" id="username" placeholder="Lietotājvārds" autocomplete="off">
            <span id="username_msg"></span>
            <input type="password" id="password" placeholder="Parole" autocomplete="off">
            <span id="password_msg"></span>
            <input type="password" id="verify_password" placeholder="Apstipriniet paroli" autocomplete="off">
            <span id="verify_password_msg"></span>
            <div id="register">Reģistrēties</div>
            <div class="previous" id="registerPrevious">Atpakaļ</div>
            <div id="msg"></div>
        </div>

    </div>

    <div class="popup" id="popup">
        <div class="popup-container">
            <h1>Noteikumi un privātuma politika</h1>
            <p>Privātuma politika: Lorem ipsum dolor, sit amet consectetur adipisicing elit. Similique dolor quis ipsum deserunt libero dignissimos corrupti rem labore tempore. Odit soluta nesciunt, facere a nihil optio numquam in quam dolore?</p>
            <p>Noteikumi: Lorem ipsum dolor sit, amet consectetur adipisicing elit. Labore nam consequuntur praesentium at facilis, molestiae, reprehenderit quam doloribus quo quisquam a autem alias necessitatibus corporis ducimus tempora. Hic, mollitia? Earum?</p>
            <div class="popup-buttons">
                <button class="btn-close" id="btn-close">Aizvērt</button>
            </div>
        </div>
    </div>

    <script>

        // Šeit tiek definēti Error tipi t.i. vietas, kur parādās errori
        const ErrorType = {

            // Error tips vārda kļūdām
            FIRSTNAME: "firstname",

            // Error tips uzvārda kļūdām
            LASTNAME: "lastname",

            // Error tips kursa kļūdām
            COURSE: "course",

            // Error tips lietotājvārdu kļūdām
            USERNAME: "username",

            // Error tips paroļu kļūdām
            PASSWORD: "password",

            // Error tips atkārtotas paroles kļūdām
            VER_PASSWORD: "password_verify",

            // Error tips attēla pievienošanas kļūdām
            IMAGE: "image",

            // Error tips privātuma politikas un noteikumu nepiekrišanas vai neizlasīšanas kļūdām
            CHECKBOX: "privacy_terms",

            // Ziņojuma tips veiksmīgiem ziņojumiem
            SUCCESS: "success"
        }

        // Funckija, kas iztīra error laukus
        const errorClear = () => {
            $('#firstname_msg').text('')
            $('#lastname_msg').text('')
            $('#course_msg').text('')
            $('#image_msg').text('')
            $('#username_msg').text('')
            $('#password_msg').text('')
            $('#verify_password_msg').text('')
            $('#privacy_terms_msg').text('')
            $('#msg').text('')
        }

        // Funkcija, kas parāda <message> vietā <type> (ErrorType)
        const errorOut = (type, message) => {

            // Skatoties pēc tipa, izvadam ziņojumu/kļūdu
            switch(type){

                case ErrorType.FIRSTNAME:
                    $('#firstname_msg').text(message)
                    break

                case ErrorType.LASTNAME:
                    $('#lastname_msg').text(message)
                    break

                case ErrorType.COURSE:
                    $('#course_msg').text(message)
                    break

                case ErrorType.USERNAME:
                    $('#username_msg').text(message)
                    break
                
                case ErrorType.PASSWORD:
                    $('#password_msg').text(message)
                    break
                
                case ErrorType.VER_PASSWORD:
                    $('#verify_password_msg').text(message)
                    break
                
                case ErrorType.IMAGE:
                    $('#image_msg').text(message)
                    break

                case ErrorType.CHECKBOX:
                    $('#privacy_terms_msg').text(message)
                    break

                case ErrorType.SUCCESS:
                    $('#msg').text(message)
                    break
                
                default:
                    break
            }
        }

        // Sākuma funkcija - tiek palaista lapas ielādes beigās.
        $( document ).ready(function() {
            fetchCourses();
            $('#addImg').click(function(){
                $('#croppieWindow').show();
            });

            $('#fileToUpload').on("input",function(){readFile(this);});
            $("#doneButton").click(cropImage);
            $("#firstname").focus()
            $('#register').click(registerUser)
        });

        // Funkcija, kas reģistrē lietotāju
        function registerUser(){
            var firstname = $('#firstname').val()
            var lastname = $('#lastname').val()
            var courseID = $('#courses').val()
            var username = $('#username').val()
            var password = $('#password').val()
            var verify_password = $('#verify_password').val()
            var termsCheckbox = document.getElementById('acceptTerms')
            var privacyCheckbox = document.getElementById('acceptPrivacy')

            // iztīram ziņojumus
            errorClear();

            // Pārbaudam ievadīto lietotājvārdu
            if(username.length < 5) {
                errorAnim('#username')
                errorOut(ErrorType.USERNAME, "Lietotājvārdam jābūt vismaz 5 simbolus garam")
                return
            }

            // Pārbaudam ievadīto paroli
            if(password.length < 5) {
                errorAnim('#password')
                errorOut(ErrorType.PASSWORD, "Parolei jābūt vismaz 5 simbolus garai")
                return
            }

            // Pārbaudam abu ievadīto paroļu līdzību
            if (verify_password != password) {
                errorAnim('#verify_password')
                errorOut(ErrorType.VER_PASSWORD, "Paroles nesakrīt")
                return
            }

            // Ja neviens no erroriem netika triggerots, sūtam pieprasījumu serverim
            $.post("server.php", {
                    action: "insert_user",
                    firstname: firstname,
                    lastname: lastname,
                    courseID: courseID,
                    username: username,
                    password: password,
                    image: images,
                    defaultPicture: defaultPicture

                // Ja serveris atbild ar 200 (Success)
                }, (data) => {
                    errorOut(ErrorType.SUCCESS, data.message)
                    return;

                // Ja serveris atbild ar 404, 500 u.c. (Not found / Failed)
                }).fail((data) => {

                    // Skatamies kāda tipa error serveris atsūta, uz to arī reaģējam
                    switch(data.responseJSON.type){
                        case "username_error":
                            errorAnim('#username')
                            errorOut(ErrorType.USERNAME, data.responseJSON.message)
                            break
                        
                        case "password_error":
                            errorOut(ErrorType.PASSWORD, data.responseJSON.message)
                            break
                        
                        case "image_error":
                            nextPrevious(-1)
                            errorOut(ErrorType.IMAGE, data.responseJSON.message)
                            break

                        default:
                            // Ja nav definēts servera errors tad klientam izvadīsies atbildes dump konsolē (response dump)
                            console.log(data)
                            break
                    }
                    return;
                })
            }

        var images = [];
        var el = document.getElementById('vanilla-demo');
        var vanilla = new Croppie(el, {
            viewport: { width: 200, height: 200, type: 'circle'},
            boundary: { width: 250, height: 250 },
            showZoomer: true,
        });

        function cropImage(){
            vanilla.result({
                type: 'base64',
                size: { width: 30, height: 30 },
                circle: false
                }).then(function(base64) {
                    images.push({image:base64,size:"30"});
            });
            vanilla.result({
                type: 'base64',
                size: { width: 200, height: 200 },
                circle: false
                }).then(function(base64) {
                    images.push({image:base64,size:"200"});
                    $("#croppieImg").attr("src",base64);
                    $('#croppieWindow').hide();
            });
        }

        function readFile(input) {
 			if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
					// $('.upload-demo').addClass('ready');
	            	vanilla.bind({
	            		url: e.target.result
	            	}).then(function(){
	            		console.log('jQuery bind complete');
                        $("#doneButton").prop("disabled",false);
                        $("#warn").css("visibility","hidden");
	            	});
	            }
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
		        console.log("Sorry - your browser doesn't support the FileReader API");
		    }
		}

        // Funkcija, kas pievieno un noņem klasi 'bounce' (error animāciju)
        function errorAnim(input) { // input vietā liek attiecīgo input field, piemēram, '#username'
            $(input).addClass('bounce')
            setTimeout(() => {
                $(input).removeClass('bounce')
            }, 1000);
        }

        // Uznirstošais logs par noteikumiem un privātuma politiku

        const popup = document.getElementById('popup')
        const btnClose = document.getElementById('btn-close')
        const btnDecline = document.getElementById('btn-decline')
        const privacyPolicy = document.getElementById('privacy')
        const terms = document.getElementById('terms')
        var privacyCheckbox = document.getElementById('acceptPrivacy')
        var termsCheckbox = document.getElementById('acceptTerms')
        var defaultPictureCheckbox = document.getElementById('defaultPicture')
        var defaultPicture = false
        var isRead = false

        privacyCheckbox.disabled = true
        termsCheckbox.disabled = true
        
        // Pārbauda vai ir atzīmēts checkbox par anonīmas profila bildes lietošanu
        function checkIfDefaultPicture() {
            if(defaultPictureCheckbox.checked == true) {
                defaultPicture = true
            } else {
                defaultPicture = false
            }
        }

        // Parāda uznirstošo logu ar privātuma politiku un noteikumiem
        function showPopup() {
            setTimeout(() => {
                popup.classList.add('popup-visible')
            }, 250 );
        }

        // Pievieno eventListener(click), kas palaiž funkciju showPopup()
        function setPopup(element) {
            element.addEventListener("click", ()=>{
                isRead = true
                showPopup()
            })
        }

        //  Kad uzspiež pogu 'Aizvērt', tad aizver uznirstošo logu un atbloķē checkboxus
        btnClose.addEventListener("click", ()=>{
            popup.classList.remove('popup-visible')
            $('#privacy_terms_msg').text('')
            privacyCheckbox.disabled = false
            termsCheckbox.disabled = false
        })

        setPopup(privacyPolicy)
        setPopup(terms)

        // Reģistrēšanās pa soļiem

        var tabs = document.querySelectorAll(".tab")
        var currentTab = 0
        showTab(currentTab)

        nextButton = document.querySelectorAll(".next")
        previousButton = document.querySelectorAll(".previous")
        
        for(var i = 0; i < nextButton.length; i++) {
            nextButton[i].onclick = function() {
                nextPrevious(1)
            }
        }

        for(var i = 0; i < previousButton.length; i++) {
            previousButton[i].onclick = function() {
                nextPrevious(-1)
            }
        }

        function showTab(n) {
            tabs[n].style.display = "grid"
        }

        function nextPrevious(n) {
            if(!validateForm()) {
                return;
            }
            console.log('click')
            tabs[currentTab].style.display = "none"
            currentTab = currentTab + n
            showTab(currentTab)
        }

        function validateForm() {
            var firstname = $('#firstname').val()
            var lastname = $('#lastname').val()
            var courses = $('#courses').val()

            errorClear();

            if(firstname < 3) {
                errorOut(ErrorType.FIRSTNAME, "Ievadiet savu vārdu")
                errorAnim('#firstname')
                return
            }

            if(lastname < 3) {
                errorOut(ErrorType.LASTNAME, "Ievadiet savu uzvārdu")
                errorAnim('#lastname')
                return
            }

            if(courses < 1) {
                errorOut(ErrorType.COURSE, "Izvēlieties kursu")
                errorAnim('#courses')
                return
            }

            if(privacyCheckbox.checked == false || termsCheckbox.checked == false) {
                if(isRead == false) {
                    errorOut(ErrorType.CHECKBOX, "Izlasiet privātuma politiku un noteikumus")
                    return
                } else if (privacyCheckbox.checked == false) {
                    errorOut(ErrorType.CHECKBOX, "Lai turpinātu, piekrītiet privātuma politikai")
                    return
                } else if (termsCheckbox.checked == false) {
                    errorOut(ErrorType.CHECKBOX, "Lai turpinātu, piekrītiet noteikumiem")
                    return
                }  
            }

            // Tad ja ieķeksēts checkbox par attēla ievades izlaišanu
            if(defaultPictureCheckbox.checked == true) {
                currentTab++;// Izlaiž bildes ievades soli, ja atzīmēts, ka nevēlas to izmantot
                document.getElementById('registerPrevious').onclick = function() {
                    tabs[currentTab].style.display = "none"
                    showTab(0) // Aizmet uz pirmo soli, ja nospiež 'Atpakaļ', ja bija izvēlēts izlaist attēla ievades soli
                    currentTab = 0
                }
            } else {
                document.getElementById('registerPrevious').onclick = function() {
                    nextPrevious(-1)
                }
            }

            // Pārbauda vai ir atzīmēts checkbox, par savas bildes neizmantošanu (defaultPicture sūta uz serveri)
            checkIfDefaultPicture()

            return true;
        }

        // Ielāde kursus no datubāzes, server.php faila
        function fetchCourses() {
            $.post("server.php",{
                action: 'fetch_courses'
            }, function(data) {
                for(var i = 0; i < data.length; i++){
                    var id = data[i]["id"]
                    var course = data[i]["course"]

                    $('#courses').append("<option value='"+id+"'>"+course+"</option>");
                }
            }, "json")
        }

    </script>
</body>
</html>
