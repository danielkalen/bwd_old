function InsertCart(psItemNo){
	window.open('PlaceOrder.aspx?itemno=' + psItemNo, '', 'status=none,toolbar=none,width=650,height=350');
}
function CopyAddress(){
	window.document.frmAddress.txtSCompany.value = window.document.frmAddress.txtCompany.value;
	window.document.frmAddress.txtSAddr1.value = window.document.frmAddress.txtBAddr1.value;
	window.document.frmAddress.txtSAddr2.value = window.document.frmAddress.txtBAddr2.value;
	window.document.frmAddress.txtSCity.value = window.document.frmAddress.txtBCity.value;
	window.document.frmAddress.ddlSCountry.value = window.document.frmAddress.ddlBCountry.value;
	
	window.document.frmAddress.txtSZip.value = window.document.frmAddress.txtBZip.value;
	window.document.frmAddress.txtSPhone1.value = window.document.frmAddress.txtBPhone1.value;
	window.document.frmAddress.txtSPhone2.value = window.document.frmAddress.txtBPhone2.value;
	window.document.frmAddress.txtSFax.value = window.document.frmAddress.txtBFax.value;
	
	window.document.frmAddress.txtSState.value = window.document.frmAddress.txtBState.value;
	window.document.frmAddress.ddlSState.value = window.document.frmAddress.ddlBState.value;
}
function CopyAddress2(){
	window.document.frmAddress.txtSContact.value = window.document.frmAddress.txtBContact.value;
	window.document.frmAddress.txtSCompany.value = window.document.frmAddress.txtBCompany.value;
	window.document.frmAddress.txtSAddr1.value = window.document.frmAddress.txtBAddr1.value;
	window.document.frmAddress.txtSAddr2.value = window.document.frmAddress.txtBAddr2.value;
	window.document.frmAddress.txtSCity.value = window.document.frmAddress.txtBCity.value;
	window.document.frmAddress.ddlSCountry.value = window.document.frmAddress.ddlBCountry.value;
	
	window.document.frmAddress.txtSZip.value = window.document.frmAddress.txtBZip.value;
	window.document.frmAddress.txtSPhone1.value = window.document.frmAddress.txtBPhone1.value;
	window.document.frmAddress.txtSPhone2.value = window.document.frmAddress.txtBPhone2.value;
	window.document.frmAddress.txtSFax.value = window.document.frmAddress.txtBFax.value;
	
	window.document.frmAddress.txtSState.value = window.document.frmAddress.txtBState.value;
	window.document.frmAddress.ddlSState.value = window.document.frmAddress.ddlBState.value;
}
function ResetAddress(xpara){
	window.document.frmAddress.txtSCompany.value = '';
	window.document.frmAddress.txtSAddr1.value = '';
	window.document.frmAddress.txtSAddr2.value = '';
	window.document.frmAddress.txtSCity.value = '';
	
	window.document.frmAddress.txtSZip.value = '';
	window.document.frmAddress.txtSPhone1.value = '';
	window.document.frmAddress.txtSPhone2.value = '';
	window.document.frmAddress.txtSFax.value = '';
	
	if (xpara=='2'){
		window.document.frmAddress.txtSContact.value = '';
	}
}
function gotourl(url,sname,swidth,sheight,sreplace,sclose)
{
	var objNewWin;				
	//if (swidth=="") 
	swidth = "1020";
	//if (sheight=="") 
	sheight = "700";
	if (sreplace=="") sreplace = false;
	if (sclose=="") sclose = false;
	objNewWin = window.open(url,sname,"fullscreen=0,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=" + swidth + ",height=" + sheight + ",top=0,left=0",sreplace);	
	if (sclose) window.close();
	objNewWin.focus();	
}
function MassUpdate(){
	window.open('inventory_mupdate.aspx', '', 'status=none,toolbar=none,width=540,height=320');
}
function MarkAll(plChecked, psCtlID, pnColumn){
	var inputs; 
	var chkinput;
	inputs = document.getElementById("dgItemList").rows;
	for (var i=0; i < inputs.length; i++)
	{
		if (inputs[i].cells[pnColumn].getElementsByTagName("INPUT").length>0)
		{
			chkinput = inputs[i].cells[pnColumn].getElementsByTagName("INPUT")[0];
			if (chkinput.id.indexOf(psCtlID)>-1) {
				chkinput.checked = plChecked;
			}
		}
	}
}

function MarkAllNew(plChecked, psCtlID, pnColumn, psGridName){
	var inputs; 
	var chkinput;
	inputs = document.getElementById(psGridName).rows;
	for (var i=0; i < inputs.length; i++)
	{
		if (inputs[i].cells[pnColumn].getElementsByTagName("INPUT").length>0)
		{
			chkinput = inputs[i].cells[pnColumn].getElementsByTagName("INPUT")[0];
			if (chkinput.id.indexOf(psCtlID)>-1) {
				chkinput.checked = plChecked;
			}
		}
	}
}

function CheckInput(){
	if(event.keyCode<47 || event.keyCode>57)
		event.returnValue=false;
}

var Tarrys = new Array("Dsp","Dis");
var OFF_arrys = new Array("image/Dsp_off.gif","image/Dis_off.gif");
var ON_arrys = new Array("image/Dsp_on.gif","image/Dis_on.gif");
function Checkname(Pname)
{
 for(var i=0;i<Tarrys.length;i++)
 {
 var obj = document.getElementById(Tarrys[i]);
 var obj_s = document.getElementById(Tarrys[i]+"_s");
 if(Tarrys[i]==Pname)
 {
  obj.src = ON_arrys[i];
  obj_s.style.display="";
 }
 else
 {
  obj.src = OFF_arrys[i];
  obj_s.style.display="none";
 }
 }
}

function chkInput(oThis) {
	if (oThis.value.length < oThis.maxLength)
		oThis.value = (oThis.value + String.fromCharCode(event.keyCode)).toUpperCase();
	event.returnValue=false;
}

function ApplyOptChanged(source, dest1, dest2, dest3){
	var objOpt0, objOpt1, objOpt2, objSource;
	objSource = document.getElementById(source);
	objOpt0 = document.getElementById(dest1);
	objOpt1 = document.getElementById(dest2);
	objOpt2 = document.getElementById(dest3);
	
	if (objSource.selectedIndex == 0){
		objOpt0.style.display = "";
		objOpt1.style.display = "none";
		objOpt2.style.display = "none";
	}
	else if (objSource.selectedIndex == 1){
		objOpt0.style.display = "none";
		objOpt1.style.display = "";
		objOpt2.style.display = "none";
	}
	else{
		objOpt0.style.display = "none";
		objOpt1.style.display = "none";
		objOpt2.style.display = "";
	}
}
function SelectSOClick(tsPage, tsCtlName)
{
		lcKey = eval("window.document." + tsCtlName + ".value");
		winModalWindow = window.open(tsPage + "?ctlname=" + tsCtlName + "&key=" + lcKey,"ModalChild1","dependent=yes,width=600,height=400,scrollbars=yes")
		winModalWindow.focus();

}

function javaTrim(str){
		var i=0;
		var j;
		var len=str.length;
		
		trimstr="";
		if(j<0) return trimstr;
		flagbegin= true;
		flagend= true;
		
        while (flagbegin== true)
        {
			if (str.charAt(i)==" ")
			{
				i++;
				flagbegin=true;
			}
			else
			{
                flagbegin=false;
			}
		} 
        
        j=len-1;
		var k=0;
        while (flagend==true)
        {
			if (str.charAt(j)==" ")
			{
				j--;
                flagend=true;
                k++;
			}
			else
			{
				flagend=false;
			}
		}
        
        if (str.length==i)
        {
         trimstr="";
         return trimstr;
        }

		trimstr=str.substring(i,j+1);
        return trimstr;
}