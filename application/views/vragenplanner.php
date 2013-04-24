<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Scholen met Succes Vragenplanner</title>
                <link type="text/css" href="http://www.scholenmetsucces.nl/templates/scholenmetsucces/css/reset.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url();?>webapp/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url();?>webapp/css/webapp.css" rel="stylesheet" />
        <!--[if IE 7]>
        <link type="text/css" href="/webapp/css/ie7.css" rel="stylesheet" />
        <script type="text/javascript" src="/webapp/js/json2.js"></script>
        <![endif]-->
        <link type="text/css" href="<?php echo base_url();?>webapp/css/basic.css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/config.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/jquery.simplemodal.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/basic.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/jquery.simpletip-1.3.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>webapp/js/sms-question-selector.js?v=1"></script>
    </head>
    <body>
        <div data-role="header" id="header">
        <div id="logo"><img alt="Scholen met Succes" src="/templates/scholenmetsucces-frontpage/images/logo.png"><span style="font-size:80px; color:#fff; float:right; margin-top:17px; text-shadow: 0.1em 0.1em 0.2em grey;">Vragenplanner</span></div>
        </div>
        <div data-role="page" id="login">
            <h1>Welkom bij de Vragenplanner</h1>
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
                            <?php echo anchor('/auth/forgot_password', 'Wachtwoord vergeten'); ?>
                        </li>
                        <li>     
                            <?php echo anchor('/auth/register', 'Registreer'); ?>
                        </li>
                    </ul>
            </form>
        </div>
        <div id="print_area" data-role="page" class="hide"></div>
        <div data-role="page" id="typechoice">
            <div class="list_container">
                <div class="container_head">
                	<div class="head_title">
                    <span class="left"> Alle vragen </span>
                    <form class="right" id="filter_wrapper">
                        <label for="filter_field" id="filter_field_label">Filter: </label>
                        <input name="filter_field" id="filter_field" type="text" value="" />
                    </form>
                    <div class="clear"></div>
                    </div>
                    <div id="list_controls"></div>
                </div>
                <ul id="questions_container" class="">
                        <form id="survey_type" action="#">
                            <label id="select_type_label" for="select_type" class="">Stel uw lijst volledig zelf samen, voor:</label><br />
                            <select name="select_type" id='select_type' class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                                <option value=''>Kies een type:</option>
                                <option value="otp">Ouders</option>
                                <option value="ltp">Leerlingen</option>
                                <option value="ptp">Personeel</option>
                            </select>
                        </form>
                        <form id="survey_standard" action="#">
                            <label id="select_standard_label" for="select_standard" class="">Gebruik onze standaard lijst en pas deze naar uw wensen aan, voor:</label>
                            <select name="select_standard" id='select_standard' class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                                <option value=''>Kies een type:</option>
                                <option value="otp">Ouders</option>
                                <option value="ltp">Leerlingen</option>
                                <option value="ptp">Personeel</option>
                            </select>
                    </form> 
                        <form id="survey_saved" action="#">
                            <label id="select_saved_label" for="select_saved" class="">Ga verder met een eerder opgeslagen vragenlijst:</label>
                            <select name="select_saved" id='select_saved' class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                                <option value=''>Kies een type:</option>
                            </select>
                        </form>
                    <!-- All available question will be loaded here -->
                </ul>
            </div>
            <div class="list_container">
                <div class="container_head">
                    <div  class="head_title">Geselecteerde vragen</div> <div id="questionnaire_controls"></div> <button id="print_question_list"
                    class="right">Printen</button><button id="save_question_list" class="right">Opslaan</button>
                </div>
                <div id="question_list_container" class="">
                    <li class="info error"><span class='errorhead'></span></li>
                    <!-- All selected questions will be loaded here -->
                </div>
            </div>
        </div>
        <div data-role="footer" id="footer">
            <?php echo anchor('/auth/logout/', 'Logout'); ?>
        </div>
    </body>
<div id="save_questionaire" title="Sla vragenlijst op">
    <p class="validateTips">geef de vragenlijst een naam.</p>
 
    <form>
    <fieldset>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
    </fieldset>
    </form>
</div>
</html>


