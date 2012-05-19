<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Scholen met Succes Vragenplanner</title>
                <link type="text/css" href="http://www.scholenmetsucces.nl/templates/scholenmetsucces/css/reset.css" rel="stylesheet" />
        <link type="text/css" href="/webapp/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link type="text/css" href="/webapp/css/webapp.css" rel="stylesheet" />
        <!--[if IE 7]>
        <link type="text/css" href="/webapp/css/ie7.css" rel="stylesheet" />
        <script type="text/javascript" src="/webapp/js/json2.js"></script>
        <![endif]-->
        <link type="text/css" href="/webapp/css/basic.css" rel="stylesheet" />
        <script type="text/javascript" src="/webapp/js/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="/webapp/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="/webapp/js/config.js"></script>
        <script type="text/javascript" src="/webapp/js/jquery.simplemodal.js"></script>
        <script type="text/javascript" src="/webapp/js/basic.js"></script>
        <script type="text/javascript" src="/webapp/js/jquery.simpletip-1.3.1.min.js"></script>
        <script type="text/javascript" src="/webapp/js/sms-question-selector.js"></script>
    </head>
    <body>
        <div data-role="header" id="header">
        </div>
        <div data-role="page" id="login">
            <h1>Welkom bij de vragenplanner</h1>
            <form id="form_login" method="post">
                <h2>
                    Login om verder te gaan.
                </h2>
                    <div class="login_field">
                        <label for="login_name">E-mail adres</label>
                        <input name="login_name" id ="login_name">
                    </div>
                    <div class="login_field">
                        <label for="login_password">Wachtwoord</label>
                        <input name="login_password" id ="login_password" type = "password">
                    </div>
                    <div class="login_field">
                        <input type="checkbox" name="remember" value="1" id="remember" style="margin:0;padding:0"  />           
                    <label for="remember">Onthoud login</label>
                    </div>
                <p id="error"></p>
                <p>
                    <input name="login_submit" id="login_submit" type="submit" value="Inloggen">
                </p>
                    <ul id="login_options">
                        <li>           
                            <a href="http://smsview/index.php/auth/forgot_password">Wachtwoord vergeten</a>   
                        </li>
                        <li>     
                            <a href="http://smsview/index.php/auth/register">Registreer</a>
                        </li>
                    </ul>
            </form>
        </div>
        <div data-role="page" id="typechoice">
            <div class="list_container">
                <div class="container_head">
                    <span class="left"> Alle vragen </span>
                    <span id="list_controls"></span>
                    <form class="right" id="filter_wrapper">
                        <label for="filter_field" id="filter_field_label">Filter: </label>
                        <input name="filter_field" id="filter_field" type="text" value="" />
                    </form>
                    <div class="clear"></div>
                </div>
                <ul id="questions_container" class="">
                        <form id="survey_type" action="#">
                            <label id="select_type_label" for="select_type" class="error">Kies het type vragenlijst:</label>
                            <select name="select_type" id='select_type'>
                                <option value=''>Kies een type:</option>
                                <option value="otp">Ouders</option>
                                <option value="ltp">Leerlingen</option>
                                <option value="ptp">Personeel</option>
                            </select>
                        </form>
                    <!-- All available question will be loaded here -->
                </ul>
            </div>
            <div class="list_container">
                <div class="container_head">
                    Geselecteerde vragen <div id="questionnaire_controls"></div> <button id="save_question_list" class="right">Opslaan</button>
                </div>
                <div id="question_list_container" class="">
                    <li class="info error"></li>
                    <!-- All selected questions will be loaded here -->
                </div>
            </div>
        </div>
        <div data-role="footer" id="footer">
            <?php echo anchor('/auth/logout/', 'Logout'); ?>
        </div>
    </body>
</html>


