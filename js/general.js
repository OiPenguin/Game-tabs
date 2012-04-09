function checkForPresenceInSelect2(optVal, text, selList) {
 for (i = 0; i < selList.length; i++) {
   if (selList.options[i].value == optVal) return true;
   if (selList.options[i].text == text) return true;
 }
 return false;
}
// Logic for moving a multi-select from element1 to element2
function gGetElementById(s) {
  var o = (document.getElementById ? document.getElementById(s) : document.all[s]);
  return o == null ? false : o;
}

function selectAllOptions(oblCmb)
{
var len = oblCmb.length; 
for (var i = 0; i < len; ++i) {
 	 oblCmb.options[i].selected = true;     
   }
  return;
}

function one2two(element1, element2) {
 var memberList = gGetElementById(element1);
 var selectedList = gGetElementById(element2);
 var len = memberList.length;

 // Ignore any selections that are made to "------"
 for (var i = 0; i < len; ++i) {
   if (memberList.options[i].selected == true) {
	 memberList.options[i].selected = false;
	 if (memberList.options[i].text == "------") {
	   continue;
	 }
	 
	 if (checkForPresenceInSelect2(memberList.options[i].value, memberList.options[i].text, selectedList) == false) {

	   selectedList.options[selectedList.length] = new Option(memberList.options[i].text, memberList.options[i].value);
	   
	 }
   }
 }
}

// moving a multi-select from element2 to element1.
// Actually this assumes that the values being moved are
// already present in element1 and ends up deleting
// the selected values in element2.
function two2one(element1, element2) {
 var selectedList = gGetElementById(element2);
 for (i = selectedList.length -1; i >= 0; i--) {
   if (selectedList.options[i].selected == true) {
	 selectedList.options[i] = null;
   }
 }
}

function one2twoSingle(element1, element2) {
 var memberList = gGetElementById(element1);
 var selectedList = gGetElementById(element2);
 var len = memberList.length;
 for (var i = 0; i < len; i++) {
    if (memberList.options[i].selected == true) {
		memberList.options[i].selected = false;
	 if (checkForPresenceInSelect2(memberList.options[i].value, memberList.options[i].text, selectedList) == false) {
	   selectedList.options[selectedList.length] = new Option(memberList.options[i].text, memberList.options[i].value);
	 }
	}
 }
}

function two2oneSingle(element1, element2) {
	var selectedList = gGetElementById(element2);
	for (i = selectedList.length -1; i >= 0; i--) {
		if(selectedList.options[i].selected==true) {
			selectedList.options[i].selected=false;
			selectedList.options[i] = null;
		}
	}
}

function check_data_list(element2)
{
    var List = gGetElementById(element2);
    if(List.length==0)
    {
        alert('Please select one or more items from the left side list!')
        return false;
    }
    else
    {return true;}
}

function check_data(element2)
{
    var data = gGetElementById(element2);    
    if(data.value=='')
    {  alert('Please Enter URL')
       return false;
    }
    else
    {return true;}
}


function doYouWantTo(){
  
  doIt=confirm('do you wish to proceed? Its remove all details.');
  if(doIt){
    return true;
  }
  else{
    return false; 
  }
}

function doYouWantToAll(){
  
  doIt=confirm('do you wish to proceed? Its remove all configuration setup.');
  if(doIt){
    return true;
  }
  else{
    return false; 
  }
}