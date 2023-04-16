

var errorCounter = 0;

function detectError(inputId, errors) {
	var inputNode = document.getElementById(inputId);
	var parent = inputNode.parentNode;

	removeError(inputNode, parent);

	if(inputNode.value == '' && inputNode.hasAttribute('required'))
		errors.unshift([true, 'Il campo è obbligatorio'])

	var hasError = !errors.some(function(error) {
		if(error[0]) 
			addError(inputNode, inputId, parent, error[1]);

		return error[0];
	})

	if(hasError)
		validationOk(parent);

}

function addError(inputNode, inputId, parent, errText) {

	var errId = inputId + 'Error';

	var errNode = document.createElement('p');
	errNode.innerText = errText;
	
	errNode.setAttribute("id", errId);
	errNode.setAttribute("aria-live", 'assertive');
	inputNode.setAttribute('aria-describedby', errId);

	parent.appendChild(errNode);
	
	parent.classList.remove('validationSuccess');
	parent.classList.add('validationError');


	if(errorCounter == 0)
		focusId = inputId;

	++errorCounter;
}

function removeError(inputNode, parent) {

	var hasError = parent.lastElementChild.tagName.toLowerCase() == 'p';
	if(hasError) {
		parent.removeChild(parent.lastElementChild);
		parent.classList.remove('validationSuccess');
		inputNode.removeAttribute('aria-describedby');
	}

}

function validationOk(parent) {

	parent.classList.add('validationSuccess');
	
}

function validate_form_submit() {

	if(errorCounter > 0)
		document.getElementById(focusId).focus();

	return errorCounter <= 0;
}

function validate_checkout() {

	errorCounter = 0;

	validate_checkout_seat_type();
	validate_checkout_ticket_amount();

	return validate_form_submit();
}

function validate_add_match() {

	errorCounter = 0;

	validate_match_day();
	validate_match_date();
	validate_match_time();
	validate_match_stadium();
	validate_match_home_team();
	validate_match_guest_team();
	validate_match_price_platea();
	validate_match_price_tribuna();
	validate_match_price_spalti();
	validate_match_price_curva();

	return validate_form_submit();
}

function validate_edit_match() {

	errorCounter = 0;

	validate_match_date();
	validate_match_time();
	validate_match_stadium();
	validate_match_home_team();
	validate_match_guest_team();

	return validate_form_submit();
}

function validate_edit_result() {

	errorCounter = 0;

	validate_match_result_host();
	validate_match_result_guest();;

	return validate_form_submit();
}

function validate_login() {

	errorCounter = 0;

	validate_login_username();
	validate_login_password();

	return validate_form_submit();
}


function validate_news() {

	if(document.getElementById('form').parentNode.getElementsByClassName('successinfo')[0] != null)
		document.getElementById('form').parentNode.removeChild(document.getElementById('form').parentNode.getElementsByClassName('successinfo')[0])

	errorCounter = 0;

	validate_news_title();
	validate_news_body();
	validate_news_bg_img();

	return validate_form_submit();
}

function validate_signup() {

	errorCounter = 0;

	validate_name();
	validate_lastName();
	validate_birthDate();
	validate_birthNation();
	validate_address();
	validate_city();
	validate_cap();
	validate_phone();
	validate_email();
	validate_pw();
	validate_confirm_pw();

	return validate_form_submit();
}

function validate_pw_edit() {

	errorCounter = 0;

	validate_pw_edit_username();
	validate_pw_edit_pw();
	validate_pw_edit_confirm_pw();

	return validate_form_submit();
}



function validate_name() {
	var inputId = 'nome';
	var errText = 'Il nome può contenere solo lettere maiuscole, minuscole, accentare e il carattere \'';

	var name_value = document.getElementById(inputId).value;
	var errCond = !name_value.match(/^[a-z][a-z' àèìòù]*$/i);

	detectError(inputId, [[errCond, errText]]);
}

function validate_lastName() {
	var inputId = 'cognome';
	var errText = 'Il cognome può contenere solo lettere maiuscole, minuscole, accentare e il carattere \'';

	var lastName_value = document.getElementById(inputId).value;
	var errCond = !lastName_value.match(/^[a-z][a-z' àèìòù]*$/i);

	detectError(inputId, [[errCond, errText]]);
}

function validate_birthDate() { 
	var inputId = 'calDataInput';
	var errText = 'La data di nascita deve essere antecedente a quella odierna';

	var birthDate_value = Date.parse(document.getElementById(inputId).value);
	var errCond = birthDate_value >= Date.now();

	detectError(inputId, [[errCond, errText]]);

	// TODO: #8 fix weirdness with date selector
}

function validate_birthNation() {
	var inputId = 'nazione';
	var errText = 'La nazione può contenere solo lettere maiuscole, minuscole, accentare, numeri e i caratteri \' - ,';

	var birthNation_value = document.getElementById(inputId).value;
	var errCond = !birthNation_value.match(/^[a-z][A-Za-z' àèìòù]*$/i);

	detectError(inputId, [[errCond, errText]]);
}

function validate_address() {
	var inputId = 'indirizzo';
	var errText = 'L\'indirizzo può contenere solo lettere maiuscole, minuscole, accentare, numeri e i caratteri \' - ,';

	var address_value = document.getElementById(inputId).value;
	var errCond = !address_value.match(/^[a-z][a-z0-9'-, àèìòù]*$/i);

	detectError(inputId, [[errCond, errText]]);
}

function validate_city() {
	var inputId = 'citta';
	var errText = 'La città può contenere solo lettere maiuscole, minuscole, accentare e il carattere \'';

	var city_value = document.getElementById(inputId).value;
	var errCond = !city_value.match(/^[a-z][a-z' àèìòù]*$/i);

	detectError(inputId, [[errCond, errText]]);
}

function validate_cap() {
	var inputId = 'cap';
	var errText = 'Il CAP dev\'essere composto da 5 ncaratteri numerici';

	var cap_value = document.getElementById(inputId).value;
	var errCond = !cap_value.match(/^[0-9]{5}$/);

	detectError(inputId, [[errCond, errText]]);

		// TODO: #9 user and admin should not be valid in registration
}

function validate_phone() { 
	var inputId = 'telefono';
	var errText = 'Il numero di telefono dev\'essere composto da 10 caratteri numerici';

	var cap_value = document.getElementById(inputId).value;
	var errCond = !cap_value.match(/^([ ]*[0-9][ ]*){10}$/);

	detectError(inputId, [[errCond, errText]]);
}

function validate_email() { 
	var inputId = 'email';
	var errText = 'L\'indirizzo email dovrebbe essere nel formato corretto, esempio: utente@esempio.com ';

	var email_value = document.getElementById(inputId).value;
	var errCond = !email_value.match(/^(([^<>()[\]\\.,;:\s@']+(\.[^<>()[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);

	detectError(inputId, [[errCond, errText]]);
}

function validate_pw() {
	var inputId = 'password';
	var errText = 'La password deve contenere almeno un carattere maiuscolo, uno minuscolo, un numero, un carattare speciale tra #?!@$%^&* - ed essere compresa tra 8 e 25 caratteri';

	var pw_value = document.getElementById(inputId).value;
	var errCond = !pw_value.match(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&* -]).{8,25}$/);

	detectError(inputId, [[errCond, errText]]);
}

function validate_confirm_pw() {
	var inputId = 'conferma_password';
	var errText = 'Le due passsword edevono coincidere';	

	var pw_value = document.getElementById('password').value;
	var pw_confirm_value = document.getElementById(inputId).value;
	var errCond = pw_value != pw_confirm_value;

	detectError(inputId, [[errCond, errText]]);
}



function validate_login_username() {
	var inputId = 'username';
	var errText = 'L\'indirizzo email dovrebbe essere nel formato corretto, esempio: utente@esempio.com ';

	var email_value = document.getElementById(inputId).value;
	var errCond = !email_value.match(/^(([^<>()[\]\\.,;:\s@']+(\.[^<>()[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) && email_value != "admin" && email_value != "utente";

	detectError(inputId, [[errCond, errText]]);
}

function validate_login_password() {
	var inputId = 'password';

	detectError(inputId, []);
}



function validate_news_title() {
	var inputId = 'newsTitleInput';
	var title_value = document.getElementById(inputId).value;
	
	var errCond = title_value.length > 50;
	var errText = 'Il titolo non può essere più lungo di 50 caratteri';

	detectError(inputId, [[errCond, errText]]);
}

function validate_news_body() {
	var inputId = 'newsBodyInput';

	detectError(inputId, []);
}

function validate_news_bg_img() {
	var inputId = 'newsBgPicInput';
	var pic_value = document.getElementById(inputId);

	var errors = [];

	var errCond = !['jpg', 'jpeg', 'png', 'gif'].includes(pic_value.files[0].name.split('.').at(-1));
	console.log(pic_value.files[0].name);
	console.log(pic_value.files[0].name.split('.'));
	console.log(pic_value.files[0].name.split('.').at(-1));
	console.log(!['jpg', 'jpeg', 'png', 'gif'].includes(pic_value.files[0].name.split('.').at(-1)));
	
	var errText = 'Estensione non supportata, prova con .jpg, .jpeg, .png o .gif';
	errors.push([errCond, errText]);
	
	var errCond = pic_value.files[0].size > 2000000;
	var errText = 'Il file è troppo grande, scegli un file di dimensione minore di 2MB.';
	errors.push([errCond, errText]);

	detectError(inputId, errors);
}



function validate_match_day() {
	var inputId = 'calGiornataInput';
	var day_value = document.getElementById(inputId).value;

	var errText = 'La giornata deve essere un numero intero maggiore di 0';
	var errCond = !Number.isInteger(Number(day_value)) || parseInt(day_value) <= 0.

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_date() {
	var inputId = 'calDataInput';
	var errText = 'La data deve essere successiva a quella odierna';

	var birthDate_value = Date.parse(document.getElementById(inputId).value);
	var errCond = birthDate_value <= Date.now();

	detectError(inputId, [[errCond, errText]]);

	// TODO: #8 fix weirdness with date selector
}

function validate_match_time() {
	var inputId = 'calOraInput';

	detectError(inputId, []);
}

function validate_match_stadium() {
	var inputId = 'calStadioInput';

	detectError(inputId, []);
}

function validate_match_home_team() {
	var inputId = 'calSqCasaInput';

	detectError(inputId, []);
}

function validate_match_guest_team() {
	var inputId = 'calSqOspiteInput';
	var homeTeam_value = document.getElementById('calSqCasaInput').value;
	var guestTeam_value = document.getElementById('calSqOspiteInput').value;

	var errCond = guestTeam_value != '' && homeTeam_value == guestTeam_value;
	var errText = "Le due squadre devono essere diverse";

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_price_platea() {
	var inputId = 'pricePlateaInput';
	var day_value = document.getElementById(inputId).value;

	var errText = 'Il prezzo deve essere un numero maggiore o uguale a 0';
	var errCond = !Number.isFinite(Number(day_value)) || parseFloat(day_value) < 0.

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_price_tribuna() {
	var inputId = 'priceTribunaInput';
	var day_value = document.getElementById(inputId).value;

	var errText = 'Il prezzo deve essere un numero maggiore o uguale a 0';
	var errCond = !Number.isFinite(Number(day_value)) || parseFloat(day_value) < 0.

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_price_spalti() {
	var inputId = 'priceSpaltiInput';
	var day_value = document.getElementById(inputId).value;

	var errText = 'Il prezzo deve essere un numero maggiore o uguale a 0';
	var errCond = !Number.isFinite(Number(day_value)) || parseFloat(day_value) < 0.

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_price_curva() {
	var inputId = 'priceCurvaInput';
	var day_value = document.getElementById(inputId).value;

	var errText = 'Il prezzo deve essere un numero maggiore o uguale a 0';
	var errCond = !Number.isFinite(Number(day_value)) || parseFloat(day_value) < 0.

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_result_host() {
	var inputId = 'result_host';
	var errText = 'Il risultato deve essere un numero non negativo (>= 0)';

	var result_value = document.getElementById(inputId).value;
	var errCond = !result_value.match(/^[0-9]+$/);

	detectError(inputId, [[errCond, errText]]);
}

function validate_match_result_guest() {
	var inputId = 'result_guest';
	var errText = 'Il risultato deve essere un numero non negativo (>= 0)';

	var result_value = document.getElementById(inputId).value;
	var errCond = !result_value.match(/^[0-9]+$/);

	detectError(inputId, [[errCond, errText]]);
}



function validate_pw_edit_pw() {
	var inputId = 'password';
	var errText = 'La password deve contenere almeno un carattere maiuscolo, uno minuscolo, un numero, un carattare speciale tra #?!@$%^&* - ed essere compresa tra 8 e 25 caratteri';

	var pw_value = document.getElementById(inputId).value;
	var errCond = !pw_value.match(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&* -]).{8,25}$/);

	detectError(inputId, [[errCond, errText]]);
}

function validate_pw_edit_confirm_pw() {
	var inputId = 'conferma_password';
	var errText = 'Le due passsword edevono coincidere';	

	var pw_value = document.getElementById('password').value;
	var pw_confirm_value = document.getElementById(inputId).value;
	var errCond = pw_value != pw_confirm_value;

	detectError(inputId, [[errCond, errText]]);
}

function validate_pw_edit_username() {
	var inputId = 'email';
	var errText = 'L\'indirizzo email dovrebbe essere nel formato corretto, esempio: utente@esempio.com ';

	var email_value = document.getElementById(inputId).value;
	var errCond = !email_value.match(/^(([^<>()[\]\\.,;:\s@']+(\.[^<>()[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) && email_value != "admin" && email_value != "utente";

	detectError(inputId, [[errCond, errText]]);
}



function validate_checkout_seat_type() {
	var inputId = 'tipoposto';

	detectError(inputId, []);
}

function validate_checkout_ticket_amount() {
	var inputId = 'nbiglietti';
	var errText = 'È possibile ordinare fino a 5 biglietti.';

	var value = document.getElementById(inputId).value;
	var errCond = !value.match(/^[0-5]$/);

	detectError(inputId, [[errCond, errText]]);
}