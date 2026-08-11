<?php
//require_once "Crud.php";
//require_once "BaseModel.php";

class FormModel //extends BaseModel
{
    // public function getFieldInfo($page_value)
    // {
    // if (in_array($page_value, ['login','register','contact','search'])) {
    //     $sql = "SELECT fi.name, fi.type
    //     FROM field_info fi
    //     JOIN fields_per_page fpp ON fpp.field_info_id = fi.id
    //     JOIN website_info wi ON wi.id = fpp.website_info_id
    //     WHERE wi.name = :page";
    //     $params = ["page" => $page_value];
    //     $stmt = $this->crudTemp-> db->prepare($sql);
    //     $stmt->execute($params);
    //     $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    //     return $result;
    // }
    // }
    public function getForm($page_value)
    {
        if ($page_value == "login") {
            return [
                "login" => '<form method="post">
                            <label for="username" class="">Naam:</label>
                            <div>
                            <input type="text" class="" name="uname" value = "" id ="username">
                            </div>
                            <label for="ww" class="">Wachtwoord:</label>
                            <div>
                            <input type="password" class="" name= "ww" value = """ id ="ww">
                            </div>
                            <a class="one" href="index.php?page=Register">Maak nieuw account aan</a>
                            <div>     
                            <button type="submit" class="" name="page" value="Login"> Login </button>
                            </div>
                            </form>'
            ];
        } elseif ($page_value == 'register') {
            return [
                'register' => '<form method="post">
                                <label for="username" class="">Naam:</label>
                                <div>
                                <input type="text" class="" name="uname" value = "" id ="username">
                                </div>
                                <label for="email" class="">Email:</label>
                                <div>
                                    <input type="email" class="" name="email" value="" id="email">
                                </div>
                                <label for="ww" class="">Wachtwoord:</label>
                                <div>
                                <input type="password" class="" name= "ww" value = """ id ="ww">
                                </div>
                                <label for="hww" class="">Herhaal Wachtwoord:</label>
                                <div>
                                <input type="password" class="" name="hww" value="" id="hww">
                                </div>
                                <div>
                                <button type="submit" class="" name="page" value="register"> Register </button>
                                </div>
                                </form>'
            ];
        } elseif ($page_value == 'contact') {
            return [
                'contact' => '<form method="post">
                            <label for="username" class="">Naam:</label>
                            <div>
                            <input type="text" class="" name="uname" value = "" id ="username">
                            </div>
                            <label for="email" class="">Email:</label>
                            <div>
                            <input type="email" class="" name= "email" value = "" id ="email">
                            </div>
                            <label for="bericht" class="">Bericht:</label>
                            <div>
                            <textarea class="" name= "bericht" value = "" id ="bericht"></textarea>
                            </div>
                            <div>     
                            <button type="submit" class="" name="page" value="message"> Verstuur bericht </button>
                            </div>
                            </form>'
            ];
        }
    }
}
$test = new FormModel();
print_r($test->getForm("contact"));