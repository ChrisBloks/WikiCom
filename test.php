<?php
// dont delete!
require_once "./src/tools/utils/HtmlUtils.php";
require_once "./src/views/fields/InputField.php";
require_once "./src/views/fields/TextAreaField.php";
require_once "./src/controllers/FormFactory.php";
require_once "./src/views/AtomicElement.php";
require_once "./src/views/BasePage.php";
require_once "./src/views/ContainerElement.php";
require_once "./src/views/Header.php";
require_once "./src/views/Footer.php";
require_once "./src/views/HtmlDoc.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/views/Form.php";

require_once "./src/views/fields/select.php";
require_once "./src/views/fields/CheckBox.php";


 $page = new BasePage();
 // head elements
 $title_element = new AtomicElement("<title> Testpagina </title>");
 $page->addToHeadContent($title_element);
 // head 
 $page->addToBodyContent(new Header("website"));
 // body
 $body_element = new AtomicElement("<p> Dit is een testpagina </p>");
 $page->addToBodyContent($body_element);



// forms
// ToDo:
// instantiate FormController
// page (contact, login, register)

//examples 
// start form
// get Field info from database
// $form_element = new Form(
//                         action: "test.php",
//                         method: "POST",
//                         submit_caption: "Submit"
//                     );



// $options = [
//   "dog"         =>"Dog",             
//   "cat"         =>"Cat",             
//   "hamster"     =>"Hamster",     
//   "parrot"      =>"Parrot",       
//   "spider"      =>"Spider",       
//   "goldfish"    =>"Goldfish"   
// ];


// // checkbox text
// $checkbox_field = new CheckBoxGroup(
//                                 name:"animals",
//                                 label: "checkbox_animal",
//                                 class: "checkboxes",
//                                 options: $options
// );
// $checkbox_field->setValue(["dog"]);
// $form_element->addElement($checkbox_field);



// // search test
// $search_field = new InputField(
//                         type: "submit", 
//                         name: "Upload_image",
//                         class: "search_button",
//                         label: "Zoeke:");
// $search_field->setValue("Zoek een bestand");
// $form_element->addElement($search_field);
// // select dropdown
// $select_field = new select(
//                             name: "animals",
//                             label: "Choose to kill",
//                             class: "death-roulettte",
//                             options: $options
// );
// $form_element->addElement($select_field);




// formfactory testing
$form_info = [
            "action"=> "contact.php",
            "method" => "POST",
            "submit_caption" => "Send message"
            ];
$form_fields = [
            [
              "type"=> "text",
              "name"=> "name",
              "class"=> "text-input",
              "label"=> "Your name"
            ],
            [
              "type"=> "email",
              "name"=> "email",
              "class"=> "text-input",
              "label"=> "Your email"
            ],
            [
              "type"=> "textarea",
              "name"=> "message",
              "class"=> "text-input",
              "label"=> "Your message"
            ],
            [
              "type"=> "checkboxgroup",
              "name"=> "contact-by",
              "class"=> "checkbox_group",
              "label"=> "Contact-methode:",
              "options"=> [
                          "mail"=> "per email",
                          "post"=> "per brief",
                          "tel" => "telefonish",
                          "pidgeon" => "per postduif"
              ]
            ],
            [

            ]
];
$formFactory = new FormFactory();
$form = $formFactory->createForm($form_info, $form_fields);

$page->addToBodyContent($form);





//$field_factory = new FieldFactory();
// foreach ($field_info as $field_definition){
//     $form_element->addElement($field_factory->createField($field_definition));

// }
// instantiate FieldFactory(
// $page->addToBodyContent($form_element);

 // foot
 $footer = new Footer("footer text");
 $page->addToBodyContent($footer);
 $page->show();