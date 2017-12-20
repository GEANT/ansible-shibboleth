// Array of regexp to match

var regExp = [
   /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð']+$/, // 0
   /^[0-9]{8}$/, // 1
   /^[A-z]+$/, // 2
   /^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[\W]).*$/, // 3
   /^[0-9]{5}$/, // 4
   /^([A-z]|[0-9]){16}$/, // 5
   /^[a-zA-Z][a-zA-Z.]*$/, //6
   /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ']+$/ // 7
];

// Array of error messages
var errorMex = [
   "Mandatory field. Insert a value", // 0
   "Invalid format, see hint for details", // 1
   "Username already existent. Specify a different username", // 2
   "The two passwords specified are not equal. Retry", // 3
   "Fiscal code format not valid", //4
   "Can't contact remote server. Retry later", // 5
   "Invalid format, special characters must not exist" // 6
];
var lblHint = 'Hint';
var lblTooFewChars5 = "At least five characters required";
var lblTooMuchChars50 = "At maximum fifty characters available";
var lblTooFewChars8 = "At least eight characters required";
var lblTooMuchChars20 = "At maximum twenty characters available";

if (userLang == "it_IT") {
   errorMex = [
      "Campo obbligatorio. Inserire un valore", // 0
      "Formato non valido, vedere suggerimento per dettagli", // 1
      "Username gi\u00E0 esistente. Inserire un altro username", // 2
      "Le password inserite non corrispondono. Riprovare", // 3
      "Formato codice fiscale non corretto", //4
      "Non riesco a comunicare con il server remoto. Provare pi\u00F9 tardi", // 5
      "Formato non valido, i caratteri speciali non possono essere usati" //6
   ];
   lblHint = 'Suggerimento';
   lblTooFewChars5 = "Necessari almeno cinque caratteri";
   lblTooMuchChars50 = "Consentiti al massimo cinquanta caratteri";
   lblTooFewChars8 = "Necessari almeno otto caratteri";
   lblTooMuchChars20 = "Consentiti al massimo venti caratteri";
} 

// Functions at loading
$(document).ready(function() {

   try {
      // Removing chyper field
      $('#enc_userpassword_0').parent().remove();
      $('#new_values_verify_userpassword_0').parent().parent().children().remove('td:last-child');
      $('tbody tr:last-child td small a').parent().parent().parent().remove();
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't remove chyper field");
   }
   
   try {
      // Removing default asteriscs
      $('td.value table tbody tr').each(function(){
         if($(this).children(':nth-child(3)').text()=="*"){
            $(this).children(':nth-child(3)').empty()
         }
      });
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't remove asteriscs");
   }
   
   try {
      // Transforming hint to value of lblHint
      $('acronym').text(lblHint);
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't translate hint.");
   }
   
   try {
      // Changes to schacPersonalUniqueID field
      var schacUniqueIdText = $('#new_values_schacpersonaluniqueid_0');
      if(schacUniqueIdText.val() && schacUniqueIdText.val().length > 16){
         $('#new_values_schacpersonaluniqueid_0').val(schacUniqueIdText.val().substr(schacUniqueIdText.length - 17));
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't transform field schacPersonalUniqueID");
   }
   // Live validation script starts
   
   // sn
   try {
      if($("#new_values_sn_0").length && $('#new_values_sn_0').attr('type')!='hidden'){
         var sn = new LiveValidation( 'new_values_sn_0', {onlyOnBlur: false, validMessage: "OK" } );
         sn.add( Validate.Presence, {failureMessage: errorMex[0]} );
         sn.add( Validate.Format, { pattern: regExp[0], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate sn");
   }
   
   // givenName
   try {
      if($("#new_values_givenname_0").length && $('#new_values_givenname_0').attr('type')!='hidden'){
         var givenname = new LiveValidation( 'new_values_givenname_0', {onlyOnBlur: false, validMessage: "OK" } );
         givenname.add( Validate.Presence, {failureMessage: errorMex[0]} );
         givenname.add( Validate.Format, { pattern: regExp[0], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate givenname");
   }
   
   // cn
   try {
      if($("#new_values_cn_0").length && $('#new_values_cn_0').attr('type')!='hidden'){
         var cn = new LiveValidation( 'new_values_cn_0', {onlyOnBlur: false, validMessage: "OK" } );
         cn.add( Validate.Presence, {failureMessage: errorMex[0]});
         cn.add( Validate.Format, { pattern: regExp[7], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate cn");
   }
   
   // uid
   try {
      if($("#new_values_uid_0").length && $('#new_values_uid_0').attr('type')!='hidden'){
         var uid = new LiveValidation( 'new_values_uid_0', {onlyOnBlur: true, validMessage: "OK" } );
         uid.add( Validate.Presence, {failureMessage: errorMex[0]} );
         uid.add( Validate.Length, { minimum: 5, maximum: 50, tooShortMessage: lblTooFewChars5, tooLongMessage: lblTooMuchChars50} );
         uid.add( Validate.Format, { pattern: regExp[6], failureMessage: errorMex[6] } );
         uid.add( Validate.Custom, { against: function(value,args){
            var esit = false;
            $.ajax({
               type: 'POST',
               url: 'checkValue.php',
               async:false,
               data: {
                  'field': 'uid',
                  'value': value
               },
               dataType: "json",
               timeout: 10000,
               success: function(result){
                  if (result['return'] == 0) esit = true;
                  else esit = false;
               },
               error: function (xhr, ajaxOptions, thrownError){
                  esit = false;
               }
            });
            return esit;
         },
         args: {
         },
         failureMessage:errorMex[2]
         });
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate uid");
   }
   
   // schacPersonalUniqueID
   try {
      if($("#new_values_schacpersonaluniqueid_0").length && $('#new_values_schacpersonaluniqueid_0').attr('type')!='hidden') {
         var schacpersonaluniqueid = new LiveValidation( 'new_values_schacpersonaluniqueid_0', {onlyOnBlur: false, validMessage: "OK" } );
         schacpersonaluniqueid.add( Validate.Presence, {failureMessage: errorMex[0]} );
         schacpersonaluniqueid.add( Validate.Format, { pattern: regExp[5], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate schacpersonaluniqueid");
   }
   
   // mail
   try {
      if($("#new_values_mail_0").length && $('#new_values_mail_0').attr('type')!='hidden'){
         var mail = new LiveValidation( 'new_values_mail_0', {onlyOnBlur: false, validMessage: "OK" } );
         mail.add( Validate.Presence, {failureMessage: errorMex[0]} );
         mail.add( Validate.Email, { failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate mail");
   }

   // eduPersonAffiliation
   try {
      if($("#new_values_edupersonaffiliation_0").length && $('#new_values_edupersonaffiliation_0').attr('type')!='hidden'){
        var edupaff = new LiveValidation( 'new_values_edupersonaffiliation_0', {onlyOnBlur: false, validMessage: "OK" } );
        edupaff.add(Validate.Presence, {failureMessage: errorMex[0]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate eduPersonAffiliation");
   }
   
});
