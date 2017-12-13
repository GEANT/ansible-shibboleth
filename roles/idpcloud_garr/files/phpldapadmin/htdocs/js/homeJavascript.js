// Global variables

var lblTitle = "Create new user";
var lblLoading = "Loading...";
var lblNew = "new";
var lblCF = "Fiscal code *";
var lblRole = "Institutional role";

if (userLang == "it_IT") {
   lblTitle = "Crea nuovo utente";
   lblLoading = "Caricamento in corso...";
   lblNew = "nuovo";
   lblCF = "Fiscal code *";
   lblRole = "Qualifica ricoperta";
}

function returnBaseDN(){
   try {
      var basedn = $('meta[name=peoplebase]').attr('value').replace(/=/g,"%3D").replace(/,/g,"%2C");
      return basedn;
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't return baseDN");
   }
}

// Functions at loading
$(document).ready(function() {

   //Changing logo href
   try {
      $('img.logo').parent().attr("href", "index.php");
      $('img.logo').parent().attr("target", "_self");
      $('img.logo').parent().attr("onclick", "target='_self';");
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't change logo href");
   }
   
   //Creating new user button on the left menu
   try {
      var newButtonTd = document.createElement("td");
      newButtonTd.setAttribute("class", "server_links");
      var newButtonA = document.createElement("a");
      newButtonA.setAttribute("title", lblTitle);
      newButtonA.setAttribute("onclick", "return ajDISPLAY('BODY','cmd=template_engine&server_id=1&container=" + returnBaseDN() + "','" + lblLoading + "');");
      newButtonA.setAttribute("href", "cmd.php?cmd=template_engine&server_id=1&container=" + returnBaseDN());
      var nuovoButtonImg = document.createElement("img");
      nuovoButtonImg.setAttribute("src", "images/default/create.png");
      nuovoButtonImg.setAttribute("class", "imgs");
      nuovoButtonImg.setAttribute("alt", lblTitle);
      nuovoButtonImg.setAttribute("style", "border: 0px; vertical-align:text-top;");
      var nuovoButtonBr = document.createElement("br");
      var nuovoButtonTextNode = document.createTextNode(lblNew);
      newButtonA.appendChild(nuovoButtonImg);
      newButtonA.appendChild(nuovoButtonBr);
      newButtonA.appendChild(nuovoButtonTextNode);
      newButtonTd.appendChild(newButtonA);
      $('#ajSID_1 table tbody tr:nth-child(2) td.links table tbody tr:first-child').prepend(newButtonTd);
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't create the button \"new\" in the menu");
   }
   
   // ---- schacPersonalUniqueID
   // If I'm creating a new user and I'm in last page I add SCHAC Personal Unique ID prefix value
   try {
      if($('input').length && $('input').eq(0).attr('value')=='create'){
         $('#new_values_schacpersonaluniqueid_0').attr('value', 'urn:schac:personalUniqueID:IT:CF:' + $('#new_values_schacpersonaluniqueid_0').attr('value').toUpperCase());
         $('table.result_table tr').each(function(){
            if($(this).children().eq(0).children().eq(0).text() == lblCF){
               $(this).children().eq(1).children().eq(0).text('urn:schac:personalUniqueID:IT:CF:' + $(this).children().eq(1).children().eq(0).text().toUpperCase());
            }
         });
      }
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't add SCHAC Personal Unique ID prefix value 01 to store correctly the user in db, when user is created.");
   }
   
   // If I'm modyfing a user let me see just my schacPersonalUniqueID and not the prefix
/*
   if($("#new_values_schacpersonaluniqueid_0").length && $('#new_values_schacpersonaluniqueid_0').attr('type')!='hidden' && $('#new_values_schacpersonaluniqueid_0').val().length > 16) {
      $("#new_values_schacpersonaluniqueid_0").val($("#new_values_schacpersonaluniqueid_0").val().split(':')[$("#new_values_schacpersonaluniqueid_0").val().split(':').length - 1]);      
   }
*/
   
   // If I'm updating a new user and I'm in last page I add SCHAC Personal Unique ID prefix value
   try {
      if($('input').length && $('input').eq(0).attr('value')=='update'){
         $('#new_values_schacpersonaluniqueid_0').attr('value', 'urn:schac:personalUniqueID:IT:CF:' + $('#new_values_schacpersonaluniqueid_0').attr('value').toUpperCase());
         $('table.result_table tr').each(function(){
            if($(this).children().eq(0).children().eq(0).text() == lblCF){
               $(this).children().eq(2).children().eq(0).text('urn:schac:personalUniqueID:IT:CF:' + $(this).children().eq(2).children().eq(0).text().toUpperCase());
            }
         });
      }
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't add SCHAC Personal Unique ID prefix value 01 to store correctly the user in db, when user is created.");
   }
   
   // ---- PersonalPosition
   // If I'm creating a new user and I'm in last page personalPosition prefix value
/*
   try {
      if($('input').length && $('input').eq(0).attr('value')=='create'){
         if ($('#new_values_schacpersonalposition_0').attr('value').length > 0)
            $('#new_values_schacpersonalposition_0').attr('value', 'urn:schac:personalPosition:it:{{ pla['ldap']['domain'] }}:' + $('#new_values_schacpersonalposition_0').attr('value'));
         $('table.result_table tr').each(function(){
            if($(this).children().eq(0).children().eq(0).text() == lblRole){
               if ($(this).children().eq(1).children().eq(0).text().length > 0 && !$(this).children().eq(1).children().eq(0).text().charAt(0)!='[')
                  $(this).children().eq(1).children().eq(0).text('urn:schac:personalPosition:it:{{ pla['ldap']['domain'] }}:' + $(this).children().eq(1).children().eq(0).text());
            }
         });
      }
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't add personalPosition 01 prefix value to store correctly the user in db, when user is created.");
   }
*/  
 
   // If I'm updating an user and I'm in last page I add personalposition prefix value
/*
   try {
      if($('input').length && $('input').eq(0).attr('value')=='update'){
         if ($('#new_values_schacpersonalposition_0').attr('value').length > 0)
            $('#new_values_schacpersonalposition_0').attr('value', 'urn:schac:personalPosition:it:{{ pla['ldap']['domain'] }}:' + $('#new_values_schacpersonalposition_0').attr('value'));
         $('table.result_table tr').each(function(){
            if($(this).children().eq(0).children().eq(0).text() == lblRole){
               if ($(this).children().eq(2).children().eq(0).text().length > 0 && !$(this).children().eq(2).children().eq(0).text().charAt(0)!='[')
                  $(this).children().eq(2).children().eq(0).text('urn:schac:personalPosition:it:{{ pla['ldap']['domain'] }}:' + $(this).children().eq(2).children().eq(0).text());
            }
         });
      }
   } catch (err) {
      console.log("ERROR in homeJavascript.js: Can't add personalPosition 02 prefix value 01 to store correctly the user in db, when user is created.");
   }
*/  
 
   // If I'm modyfing a user let me see just my personalPosition and not the prefix
/*
   if($("#new_values_schacpersonalposition_0").length && $('#new_values_schacpersonalposition_0').attr('type')!='hidden' && $('#new_values_schacpersonalposition_0').val().length > 16) {
      $("#new_values_schacpersonalposition_0").val($("#new_values_schacpersonalposition_0").val().split(':')[$("#new_values_schacpersonalposition_0").val().split(':').length - 1]);      
   }
*/

});
