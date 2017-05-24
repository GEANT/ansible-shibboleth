// Array of regexp to match

var regExp = [
   /^[A-z]+(\s[A-z]+)*$/, // 0
   /^[0-9]{8}$/, // 1
   /^[A-z]+$/, // 2
   /^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[\W]).*$/, // 3
   /^[0-9]{5}$/, // 4
   /^([A-z]|[0-9]){16}$/ // 5
];

// Array of error messages
var errorMex = [
   "Mandatory field. Insert a value", // 0
   "Invalid format, see suggestion for details", // 1
   "Username already existent. Specify a different username", // 2
   "The two passwords specified are not equal. Retry", // 3
   "Fiscal code format not valid", //4
   "Can't contact remote server. Retry later" // 5
];
var lblHint = 'Hint';
var lblTooFewChars5 = "At least five characters required";
var lblTooMuchChars50 = "At maximum fifty characters available";
var lblTooFewChars8 = "At least eight characters required";
var lblTooMuchChars20 = "At maximum twenty characters available";

// Functions at loading
$(document).ready(function() {

   try {
      // Removing chyper field
      $('#enc_userpassword_0').parent().remove();
      $('#new_values_verify_userpassword_0').parent().parent().children().remove('td:last-child');
      $('tbody tr:last-child td small a').parent().parent().parent().remove();
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't remove cyper field");
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
      // Transforming hint to Suggerimento
      $('acronym').text(lblHint);
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't translate hint.");
   }
   
   try {
      // Changes to SCHAC personalUniqueID field (fiscal code)
      var schacUniqueIdText = $('#new_values_schacpersonaluniqueid_0');
      if(schacUniqueIdText.val() && schacUniqueIdText.val().length > 16){
         $('#new_values_schacpersonaluniqueid_0').val(schacUniqueIdText.val().substr(schacUniqueIdText.length - 17));
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't transform field personalUniqueID (fiscal code)");
   }
   
   try {
      // Changes to personalPosition field
      var personalPositionText = $('#new_values_schacpersonalposition_0');
      var ppPrefixLen = "urn:schac:personalPosition:it:{{ pla['ldap']['domain'] }}:".length;
      if(personalPositionText.val() && personalPositionText.val().length > ppPrefixLen){
         $('#new_values_schacpersonalposition_0').val(personalPositionText.val().substr(ppPrefixLen));
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't transform field personalPosition");
   }
   
   // Live validation script starts
   
   // sn
   try {
      if($("#new_values_sn_0").length && $('#new_values_sn_0').attr('type')!='hidden'){
         var sn = new LiveValidation( 'new_values_sn_0', {onlyOnBlur: false, validMessage: "OK" } );
         sn.add( Validate.Presence, {failureMessage: errorMex[0]} );
         sn.add(Validate.Format, { pattern: regExp[0], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate sn");
   }
   
   // givenname
   try {
      if($("#new_values_givenname_0").length && $('#new_values_givenname_0').attr('type')!='hidden'){
         var givenname = new LiveValidation( 'new_values_givenname_0', {onlyOnBlur: false, validMessage: "OK" } );
         givenname.add( Validate.Presence, {failureMessage: errorMex[0]} );
         givenname.add(Validate.Format, { pattern: regExp[0], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate givenname");
   }
   
   // cn
   try {
      if($("#new_values_cn_0").length && $('#new_values_cn_0').attr('type')!='hidden'){
         var cn = new LiveValidation( 'new_values_cn_0', {onlyOnBlur: false, validMessage: "OK" } );
         cn.add(Validate.Presence, {failureMessage: errorMex[0]});
         cn.add(Validate.Format, { pattern: regExp[0], failureMessage: errorMex[1] } );
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
   
   // password
   try {
      if($("#new_values_userpassword_0").length && $('#new_values_userpassword_0').attr('type')!='hidden'){
         var userpassword = new LiveValidation( 'new_values_userpassword_0', {onlyOnBlur: false, validMessage: "OK" } );
         userpassword.add( Validate.Presence, {failureMessage: errorMex[0]} );
         userpassword.add( Validate.Length, { minimum: 8, maximum: 20, tooShortMessage: lblTooFewChars8, tooLongMessage: lblTooMuchChars20} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate password");
   }

   // verify password
   try {
      if($('#new_values_verify_userpassword_0').length && $('#new_values_verify_userpassword_0').attr('type')!='hidden'){
         var userpasswordverify = new LiveValidation( 'new_values_verify_userpassword_0', {onlyOnBlur: false, validMessage: "OK" } );
         userpasswordverify.add( Validate.Confirmation, { match: 'new_values_userpassword_0', failureMessage: errorMex[3]});
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate verify password");
   }
   
   // schacdateofbirth
   try {
      if($("#new_values_schacdateofbirth_0").length && $('#new_values_schacdateofbirth_0').attr('type')!='hidden'){
         var schacdateofbirth = new LiveValidation( 'new_values_schacdateofbirth_0', {onlyOnBlur: false, validMessage: "OK" } );
         schacdateofbirth.add( Validate.Presence, {failureMessage: errorMex[0]} );
         schacdateofbirth.add( Validate.Numericality, {onlyInteger: true, notANumberMessage: errorMex[1], notAnIntegerMessage: errorMex[1]} );
         schacdateofbirth.add( Validate.Length, { maximum: 8, tooLongMessage: errorMex[1]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate schacdateofbirth");
   }
   
   // schacplaceofbirth
   try {
      if($("#new_values_schacplaceofbirth_0").length && $('#new_values_schacplaceofbirth_0').attr('type')!='hidden'){
         var schacplaceofbirth = new LiveValidation( 'new_values_schacplaceofbirth_0', {onlyOnBlur: false, validMessage: "OK" } );
         schacplaceofbirth.add(Validate.Presence, {failureMessage: errorMex[0]} );
         schacplaceofbirth.add(Validate.Exclusion, { within: [ 'Seleziona una provincia' ], caseSensitive: false, failureMessage: errorMex[0] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate schacplaceofbirth");
   }
   
   // schacpersonaluniqueid
   try {
      if($("#new_values_schacpersonaluniqueid_0").length && $('#new_values_schacpersonaluniqueid_0').attr('type')!='hidden') {
         var schacpersonaluniqueid = new LiveValidation( 'new_values_schacpersonaluniqueid_0', {onlyOnBlur: false, validMessage: "OK" } );
         schacpersonaluniqueid.add( Validate.Presence, {failureMessage: errorMex[0]} );
         schacpersonaluniqueid.add( Validate.Format, { pattern: regExp[5], failureMessage: errorMex[1] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate schacpersonaluniqueid");
   }
   
   // schacHomeOrganization
   try {
      if($("#new_values_schachomeorganization_0").length && $('#new_values_schachomeorganization_0').attr('type')!='hidden'){
        var schacHomeOrg = new LiveValidation( 'new_values_schachomeorganization_0', {onlyOnBlur: false, validMessage: "OK" } );
        schacHomeOrg.add(Validate.Presence, {failureMessage: errorMex[0]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate schacHomeOrganization");
   }
   
   // PAGE 2

   // street
   try {
      if($("#new_values_street_0").length && $('#new_values_street_0').attr('type')!='hidden'){
         var street = new LiveValidation( 'new_values_street_0', {onlyOnBlur: false, validMessage: "OK" } );
         street.add( Validate.Presence, {failureMessage: errorMex[0]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate street");
   }
   
   // l
   try {
      if($("#new_values_l_0").length && $('#new_values_l_0').attr('type')!='hidden'){
         var l = new LiveValidation( 'new_values_l_0', {onlyOnBlur: false, validMessage: "OK" } );
         l.add( Validate.Presence, {failureMessage: errorMex[0]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate l");
   }
   
   // postalcode
   try {
      if($("#new_values_postalcode_0").length && $('#new_values_postalcode_0').attr('type')!='hidden'){
         var postalcode = new LiveValidation( 'new_values_postalcode_0', {onlyOnBlur: false, validMessage: "OK" } );
         postalcode.add( Validate.Presence, {failureMessage: errorMex[0]} );
         postalcode.add( Validate.Numericality, {onlyInteger: true, notANumberMessage: errorMex[1], notAnIntegerMessage: errorMex[1]} );
         postalcode.add( Validate.Length, { minimum: 5, maximum: 5, tooShortMessage: errorMex[1], tooLongMessage: errorMex[1]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate postalcode");
   }
   
   // st
   try {
      if($("#new_values_st_0").length && $('#new_values_st_0').attr('type')!='hidden'){
         var st = new LiveValidation( 'new_values_st_0', {onlyOnBlur: false, validMessage: "OK" } );
         st.add(Validate.Presence, {failureMessage: errorMex[0]} );
         st.add(Validate.Exclusion, { within: [ 'Seleziona una provincia' ], caseSensitive: false, failureMessage: errorMex[0] } );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate st");
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

   // telephone
   try {
      if($("#new_values_telephonenumber_0").length && $('#new_values_telephonenumber_0').attr('type')!='hidden'){
         var telephone = new LiveValidation( 'new_values_telephonenumber_0', {onlyOnBlur: false, validMessage: "OK" } );
         telephone.add( Validate.Presence, {failureMessage: errorMex[0]} );
         telephone.add( Validate.Numericality, {onlyInteger: true, notANumberMessage: errorMex[1], notAnIntegerMessage: errorMex[1]} );
         telephone.add( Validate.Length, { maximum: 20, tooLongMessage: errorMex[1]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate telephone");
   }

   // o
   try {
      if($("#new_values_o_0").length && $('#new_values_o_0').attr('type')!='hidden'){
         var o = new LiveValidation( 'new_values_o_0', {onlyOnBlur: false, validMessage: "OK" } );
         o.add(Validate.Presence, {failureMessage: errorMex[0]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate o");
   }
   
   // ou
   try {
      if($("#new_values_ou_0").length && $('#new_values_ou_0').attr('type')!='hidden'){
         var o = new LiveValidation( 'new_values_ou_0', {onlyOnBlur: false, validMessage: "OK" } );
         o.add(Validate.Presence, {failureMessage: errorMex[0]} );
      }
   } catch (err) {
      console.log("ERROR in formJavascript.js: Can't validate ou");
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
