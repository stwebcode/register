<?php
require_once 'database_manager.php';

header('Content-Type: application/json; charset=utf-8');

abstract Class ErrorType{

    // Abstrakta klase, kas simulē Enum objektu priekš error tipiem, kas ir attiecināmi JS definētajiem error tipiem

    // Error tips lietotājvārdu kļūdām
    const USERNAME = "username_error";

    // Error tips paroļu kļūdām
    const PASSWORD = "password_error";

    // Error tips attēlu kļūdām
    const IMAGE = "image_error";
    
    // Error tips nenoteiktām kļūdām
    const NONE = "none";
}

function out($message, bool $is_error=false, string $type=ErrorType::NONE){

    /*
                            out() - izvades funkcija

    Šo funkciju jālieto gadījumos ja serveris izvada informāciju klientam.
    Katru reizi kad kaut kas tiek izvadīts ar šo funkciju, skripts apstājas, tādā
    veidā samazinot if/else statement lietošanu.

    @author: CracX
    @example: out("Invalid username", $is_error=true, ErrorType::USERNAME);

    */

    switch($is_error){
        
        // Ja izvade ir kļūda
        case true:

            // Sūtam jebkādu error kodu lai frontend AJAX funkcija nofeilo
            http_response_code(404);

            // izveidojam ziņojumu attiecīgā formātā
            $out = array(
                "error" => true,
                "message" => $message,
                "type" => $type
            );
            break;
        
        // Ja izvade ir parasts ziņojums
        case false:

            // Sūtam success kodu
            http_response_code(200);

            // izveidojam ziņojumu attiecīgā formātā
            $out = array(
                "success" => true,
                "message" => $message
            );
            break;

        default:
            
            // Noklusējuma rezultātā izvadām parastu ziņojumu
            http_response_code(200);
            $out = array(
                "success" => true,
                "message" => $message
            );
    }

    // Liekam skriptam "nomirt" un neturpināt tālāk, izvadot ziņojumu
    die(json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// Ja action neeksistē, tad php izvada faila pilno lokāciju, tāpēc nosūtīsim lietotājam 404 error lapu.
if (!isset($_POST['action'])){
    http_response_code(404);
    die();
}

// Iniciējam klase
$db = new DatabaseManager();

// Selecto kursus no datubāzes un ieliek tos masīvā
if($_POST['action'] == "get_courses"){
    // $courses = $db->get_courses();
    // echo json_encode($courses);
    echo json_encode($db->get_courses()); // Kā ir labāk darīt? Tā, kā 91. un 92. rinda? Vai tā, kā šajā rindā? Vai tam nav nozīmes, jo šādi sanāk īsāk?
}

// Ja klients vēlas pievienot lietotāju
if($_POST['action'] == "insert_user"){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $courseID = $_POST['courseID'];
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_ARGON2I);

    // Pārbaudam, vai lietotājvārds ir derīgs. Saglabājam funkcijas izvadi $res mainīgajā un skatamies kas pa error tiek izmests.
    if(!($res = $db->check_username($username))['success']){
        out($res['error_id'] == 0 ? "Lietotājvārdam jābūt vismaz 5 simbolus garam" : "Lietotājvārds jau ir aizņemts", $is_error=true, $type=ErrorType::USERNAME);
    }

    // Pārbaudam, vai parole ir derīga. Šeit mums nevajag saglabāt izvadi, jo kļūdas gadījumā tiks izvadīts tikai viens ziņojums.
    if(!$db->check_password($password)['success']){
        out("Parolei jābūt vismaz 5 simbolus garai", $is_error=true, $type=ErrorType::PASSWORD);
    }

    // Pārbauda vai lietotājs ir piekritis attēla augšupielādēšanai datubāzē
    if($_POST['defaultPicture'] == 'false') {
        
        $basename = "";
        $supported_ext = array('jpg','jpeg','png','webp'); // atbalstītie failu extensions
        // ja image masīvā atrodas vismaz 1 bilde, tad augšupielādējam to
        if(!empty($_POST['image'])){
            $time = time(); //laicīgi piefiksējam timestamp, lai visiem bildes izmēriem būtu vienāds basename
            foreach ($_POST['image'] as $data){
                switch ($data["size"]){
                    case "30":
                        $folderPath = "images/30x30/";
                        break;
                    case "200":
                        $folderPath = "images/200x200/";
                        break;
                    default:
                        out("Neatbalstīts bildes izmērs!", $is_error=true, $type=ErrorType::IMAGE);
                }
                $extension = explode('/', mime_content_type($data["image"]))[1]; // bildes extension. .png .jpg utt.
                if(!in_array($extension,$supported_ext)){
                    out("Neatbalstīts faila formāts!", $is_error=true, $type=ErrorType::IMAGE);
                }
                $image_parts = explode(";base64,", $data["image"]); //$image_parts[1] būs datu daļa
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]); //$image_parts[1] būs datu daļa
                //base64_decode Decodes data encoded with MIME base64, tātad te kļūst par reālu bildi
                $basename = $time . "." . $extension;
                $file = $folderPath . $basename;
                if (file_exists($file)) { // gadījumā, ja brīnumainā kārtā bilde ar šādu timestamp jau eksistē
                    out("Šis attēls jau eksistē. Mēģiniet vēlreiz.", $is_error=true, $type=ErrorType::IMAGE);
                }else{
                    file_put_contents($file, $image_base64); //šī funkcija ievieto failu mapē uz servera
                    //1. arguments ir faila nosaukums (tā ceļs, priekšā ir mape) un 2. ir faila saturs
                }
            }
        } else {
            out("Pievienojiet attēlu vai izvēlieties lietot anonīmu iepriekšējā solī", $is_error=true, $type=ErrorType::IMAGE);
        }

        // Ja viss kārtībā, reģistrējam lietotāju.
        if($db->register($firstname, $lastname, $courseID, $username, $password, $basename)){
            out("Lietotājs veiksmīgi reģistrēts");
        }

    } else if ($_POST['defaultPicture'] == 'true') {
        if($db->register($firstname, $lastname, $courseID, $username, $password, $basename = "default.png")){
            out("Lietotājs veiksmīgi reģistrēts");
        }
    }


    // Ja tomēr reģistrācija nebija veiksmīga, nosūtam 503. kodu signalozējot, ka kļūda ar datubāzi (šai līnijai nevajadzētu tikt sasniegtai).
    http_response_code(503);
}

?>