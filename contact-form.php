<?php
/*
*Plugin Name: contact-form
 * Description: Ajoute un formulaire de contact à votre site WordPress.
 * Author: YOUNESS EL KHAMLICHI
 * Version: 1.0
*/

     
function contact_form_add_menu_item() {
    add_menu_page(
        'Contact Form',
        'Contact Form',
        'manage_options',
        'contact-form',
        'contact_form_display_page'
    
    );
}
 add_action( 'admin_menu', 'contact_form_add_menu_item' );
 function contact_form_display_page() {
    echo '<h2>My WordPress Plugin</h2>Hello world!';
}
function mon_formulaire_shortcode() {


    $form_html = '
        <form>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email">
            <label for="message">Message :</label>
            <textarea id="message" name="message"></textarea>
            <input type="submit" value="Envoyer">
        </form>
    ';

    // Retourne le code HTML du formulaire
    return $form_html;
}
add_shortcode( 'formulaire', 'mon_formulaire_shortcode' );


function contact_form_create_table() {
    global $wpdb;
    $wp_contact_form = $wpdb->prefix . 'contact_form';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $wp_contact_form (
        id INT(9) NOT NULL AUTO_INCREMENT,
        sujet VARCHAR(200) NOT NULL,
        nom VARCHAR(200) NOT NULL,
        prenom VARCHAR(200) NOT NULL,
        email VARCHAR(200) NOT NULL,
        message VARCHAR(300) NOT NULL,
        date_envoi DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta( $sql );
}
// CREER UN TAble
register_activation_hook( FILE, 'contact_form_create_table' );
// Register deactivation hook
register_deactivation_hook( FILE, 'my_plugin_deactivation' );
    function my_plugin_deactivation() {
        global $wpdb;
        $wp_contact_form = $wpdb->prefix . 'contact_form';
        $wpdb->query( "DROP TABLE IF EXISTS $wp_contact_form" );
    }


    function execute_on_init_event(){
        if(isset($_POST["submitcontact"])){
            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $email = $_POST["email"];
            $sujet = $_POST["sujet"];
            $message = $_POST["message"];
    
            global $wpdb;
    $date= current_time('mysql');
    $table = $wpdb->prefix . 'contact_form';
    $data = array('id' => NULL,'date_envoi' => $date, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'sujet' => $sujet, 'message' => $message );
    $wpdb->insert($table,$data);
    $result = $wpdb->insert($table, $data);
    
    if ($result) {
        echo '<script>alert("Data inserted successfully.")</script>';
    } else {
        echo '<script>alert("There was an error inserting the data.")</script>';
    }
        }
    }
    // add the action
    add_action( "init", "execute_on_init_event");
?>

