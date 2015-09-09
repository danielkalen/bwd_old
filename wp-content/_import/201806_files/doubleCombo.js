
//-------------------- mContentLoader.js
var net = new Object();

net.READY_STATE_UNINITIALIZED= 0;
net.READY_STATE_LOADING      = 1;
net.READY_STATE_LOADED       = 2;
net.READY_STATE_INTERACTIVE  = 3;
net.READY_STATE_COMPLETE     = 4;

net.ContentLoader = function( component, url, method, requestParams ) {
   this.component     = component;
   this.url           = url;
   this.requestParams = requestParams;
   this.method        = method;
}

net.ContentLoader.prototype = {

   getTransport: function() {
      var transport;
      if ( window.XMLHttpRequest )
         transport = new XMLHttpRequest();
      else if ( window.ActiveXObject ) {
         try {
            transport = new ActiveXObject('Msxml2.XMLHTTP');
         }
         catch(err) {
            transport = new ActiveXObject('Microsoft.XMLHTTP');
         }
      }
      return transport;
   },

   sendRequest: function() {

      //if ( window.netscape && window.netscape.security.PrivilegeManager.enablePrivilege)
      //   netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');

      var requestParams = []
      for ( var i = 0 ; i < arguments.length ;  i++ )
         requestParams.push(arguments[i]);

      var oThis = this;
      var request = this.getTransport();
      request.open( this.method, this.url, true );
      request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded');
      request.onreadystatechange = function() { oThis.handleAjaxResponse(request) };
      request.send( this.queryString(requestParams) );
  },

  queryString: function(args) {

     var requestParams = [];
     for ( var i = 0 ; i < this.requestParams.length ; i++ )
        requestParams.push(this.requestParams[i]);
     for ( var j = 0 ; j < args.length ; j++ )
        requestParams.push(args[j]);

     var queryString = "";
     if ( requestParams && requestParams.length > 0 ) {
        for ( var i = 0 ; i < requestParams.length ; i++ )
           queryString += requestParams[i] + '&';
        queryString = queryString.substring(0, queryString.length-1);
     }
     return queryString;
  },

  handleAjaxResponse: function(request) {
     if ( request.readyState == net.READY_STATE_COMPLETE ) {
        if ( this.isSuccess(request) )
           this.component.ajaxUpdate(request);
        else
           this.component.handleError(request);
     }
  },

  isSuccess: function(request) {
    return  request.status == 0 
        || (request.status >= 200 && request.status < 300);
  }

};


//-------------------- mDoubleCombo.js

function DoubleCombo( masterId, slaveId, url, options, paraxval ) {
   this.master     = document.getElementById(masterId);
   this.slave      = document.getElementById(slaveId);
   this.options    = options;
   this.ajaxHelper = new net.ContentLoader( this, url, "POST", options.requestParameters || [] );
   this.chkbox     = document.getElementById("chkSameAddr");
   this.parax      = paraxval

   this.initializeBehavior();
}

DoubleCombo.prototype = {

   initializeBehavior: function() {
      var oThis = this;
      this.master.onchange = function() { oThis.masterComboChanged(); };
      if (oThis.parax == '1' || oThis.parax == '2')
      this.chkbox.onclick = function() { if(oThis.chkbox.checked){ document.getElementById("ddlSCountry").value = document.getElementById("ddlBCountry").value; oThis.masterComboChanged();} else {ResetAddress(oThis.parax)}};
   },

   masterComboChanged: function() {
      var query = this.master.options[this.master.selectedIndex].value;
      this.ajaxHelper.sendRequest( 'query=' + query );
   },

   ajaxUpdate:  function(request) {
		//alert(request.responseXML);
      var slaveOptions = this.createOptions(request.responseXML.documentElement);
      this.slave.length = 0;
      var optionsObj = this.slave.options;
      for ( var i = 0 ; i < slaveOptions.length ; i++ )
         optionsObj.add( slaveOptions[i] );
         
      if (this.parax == '1' || this.parax == '2') {
		if (this.chkbox.checked) {
			if (this.parax == '2'){
				CopyAddress2();
			}
			else {
				CopyAddress();
			}
		}
		else {
			document.getElementById("ddlSState").value = document.getElementById("txtSState").value;
			ResetAddress(this.parax);
		}
	  }
   },

   createOptions: function(ajaxResponse) {
      var newOptions = [];
      //alert(ajaxResponse);
      var entries = ajaxResponse.getElementsByTagName('entry');
      
      for ( var i = 0 ; i < entries.length ; i++ ) {
         var text  = this.getElementContent(entries[i],'optionText');
         var value = this.getElementContent(entries[i],'optionValue');
         newOptions.push( new Option(text, value) );
      }
      return newOptions;
   },

   handleError: function(request) {
      if ( this.options.errorHandler )
         this.options.errorHandler(request);
   },

   getElementContent: function(element,tagName) {
      var childElement = element.getElementsByTagName(tagName)[0];
      return childElement.text != undefined ? childElement.text : childElement.textContent;
   }

};

//new for shipping rate
function ComboChange( masterId, slaveId, url, options ) {
   this.master     = document.getElementById(masterId);
   this.slave      = document.getElementById(slaveId);
   this.options    = options;
   this.ajaxHelper = new net.ContentLoader( this, url, "POST", options.requestParameters || [] );

   this.initializeBehavior();
}

ComboChange.prototype = {

   initializeBehavior: function() {
      var oThis = this;
      this.master.onchange = function() { oThis.masterComboChanged(); ShipTypeChanged()};
   },

   masterComboChanged: function() {
      var query = this.master.options[this.master.selectedIndex].value;
      this.ajaxHelper.sendRequest( 'query=' + query );
   },

   ajaxUpdate:  function(request) {
		//alert(request.responseXML);
      var slaveOptions = this.createOptions(request.responseXML.documentElement);
      this.slave.innerText = slaveOptions;
      //CalculateTotal();
   },

   createOptions: function(ajaxResponse) {
      var newOptions;
      //alert(ajaxResponse);
      var entries = ajaxResponse.getElementsByTagName('entry');
      
      for ( var i = 0 ; i < entries.length ; i++ ) {
         var text  = this.getElementContent(entries[i],'optionText');
         var value = this.getElementContent(entries[i],'optionValue');
         newOptions = value;
      }
      return newOptions;
   },

   handleError: function(request) {
      if ( this.options.errorHandler )
         this.options.errorHandler(request);
   },

   getElementContent: function(element,tagName) {
      var childElement = element.getElementsByTagName(tagName)[0];
      return childElement.text != undefined ? childElement.text : childElement.textContent;
   }

};

//new for change Carrier
function CarrierChanged( masterId, slaveId, url, options, rateId, contlId, calcId ) {
   this.master     = document.getElementById(masterId);
   this.slave      = document.getElementById(slaveId);
   this.rate       = document.getElementById(rateId);
   this.contl      = document.getElementById(contlId);
   this.calc       = document.getElementById(calcId);
   this.options    = options;
   this.ajaxHelper = new net.ContentLoader( this, url, "POST", options.requestParameters || [] );

   this.initializeBehavior();
}

CarrierChanged.prototype = {

   initializeBehavior: function() {
      var oThis = this;
      this.master.onchange = function() { oThis.masterComboChanged(); };
   },

   masterComboChanged: function() {
      this.slave.disabled = true;
      this.contl.disabled = true;
      this.slave.style.display = "none";
      this.calc.innerText = "Calculating Shipping Charge, please wait ..."
      var query = this.master.options[this.master.selectedIndex].value;
      this.ajaxHelper.sendRequest( 'query=' + query );
   },

   ajaxUpdate:  function(request) {
		//alert(request.responseXML);
      var slaveOptions = this.createOptions(request.responseXML.documentElement);
      this.slave.length = 0;
      var optionsObj = this.slave.options;
      for ( var i = 0 ; i < slaveOptions.length ; i++ )
         optionsObj.add( slaveOptions[i] );
      this.slave.disabled = false;
      this.contl.disabled = false;
      this.slave.style.display = "";
      this.calc.innerText = "";
      ShipTypeChanged();
   },

   createOptions: function(ajaxResponse) {
      var newOptions = [];
      //alert(ajaxResponse);
      var entries = ajaxResponse.getElementsByTagName('entry');
      
      for ( var i = 0 ; i < entries.length ; i++ ) {
         var text  = this.getElementContent(entries[i],'optionText');
         var value = this.getElementContent(entries[i],'optionValue');
         if (text == "#RateList#"){
			this.rate.value = value;
         }
         else{
			newOptions.push( new Option(text, value) );
		}
      }
      return newOptions;
   },

   handleError: function(request) {
      if ( this.options.errorHandler )
         this.options.errorHandler(request);
   },

   getElementContent: function(element,tagName) {
      var childElement = element.getElementsByTagName(tagName)[0];
      return childElement.text != undefined ? childElement.text : childElement.textContent;
   }

};

//new for check coupon
function CheckCoupon( masterId, valueId, slaveId, url, options, trId, discId, chkId, contlId, subtotId) {
   this.master     = document.getElementById(masterId);
   this.fvalue     = document.getElementById(valueId);
   this.slave      = document.getElementById(slaveId);
   this.disc       = document.getElementById(discId);
   this.trrow      = document.getElementById(trId);
   this.chk        = document.getElementById(chkId);
   this.contl      = document.getElementById(contlId);
   this.subtot     = document.getElementById(subtotId);
   this.options    = options;
   this.ajaxHelper = new net.ContentLoader( this, url, "POST", options.requestParameters || [] );

   this.initializeBehavior();
}

CheckCoupon.prototype = {

   initializeBehavior: function() {
      var oThis = this;
      this.master.onclick = function() { oThis.masterButtonClick(); };
   },

   masterButtonClick: function() {
      //this.slave.disabled = true;
      this.contl.disabled = true;
      //this.slave.style.display = "none";
      //this.chk.innerText = "Checking coupon code, please wait ..."
      this.slave.value = '';
      this.disc.value = '';
      var query = this.fvalue.value;
      this.ajaxHelper.sendRequest( 'query=' + query + '&subtot=' + this.subtot.innerText );
   },

   ajaxUpdate:  function(request) {
		//alert(request.responseXML);
      var slaveOptions = this.createOptions(request.responseXML.documentElement);
      
      //var optionsObj = this.slave.options;
      for ( var i = 0 ; i < slaveOptions.length ; i++ )
      {
         //optionsObj.add( slaveOptions[i] );
         this.slave.value = slaveOptions[i][0];
         this.chk.innerText = slaveOptions[i][1];
         if (this.slave.value != '')
			this.disc.value = slaveOptions[i][2];
      }   
      //this.slave.disabled = false;
      this.contl.disabled = false;
      if (this.slave.value != ''){
		this.trrow.style.display = "";
	  }
	  else{
		this.trrow.style.display = "none";
	  }
      //this.slave.style.display = "";
      //this.calc.innerText = "";
      CouponChecked();
   },

   createOptions: function(ajaxResponse) {
      var newOptions = [];
      //alert(ajaxResponse);
      var entries = ajaxResponse.getElementsByTagName('entry');
      
      for ( var i = 0 ; i < entries.length ; i++ ) {
	     var type  = this.getElementContent(entries[i],'optionType');
         var text  = this.getElementContent(entries[i],'optionText');
         var value = this.getElementContent(entries[i],'optionValue');
         
		newOptions.push( new Array(type, text, value) );

      }
      return newOptions;
   },

   handleError: function(request) {
      if ( this.options.errorHandler )
         this.options.errorHandler(request);
   },

   getElementContent: function(element,tagName) {
      var childElement = element.getElementsByTagName(tagName)[0];
      return childElement.text != undefined ? childElement.text : childElement.textContent;
   }

};

//new for check coupon
function AFAddToCart( masterId, slaveId, valueId, qtyId, url, options) {
   this.master     = document.getElementById(masterId);
   this.fvalue     = valueId;
   this.slave      = document.getElementById(slaveId);
   this.qtyid      = qtyId;
   
   this.options    = options;
   this.ajaxHelper = new net.ContentLoader( this, url, "POST", options.requestParameters || [] );

   this.initializeBehavior();
}

AFAddToCart.prototype = {

   initializeBehavior: function() {
      var oThis = this;
      this.master.onclick = function() { oThis.masterButtonClick(); };
   },

   masterButtonClick: function() {
      //this.slave.disabled = true;
      //this.contl.disabled = true;
      //this.slave.style.display = "none";
      //this.chk.innerText = "Checking coupon code, please wait ..."
      //this.slave.value = '';
      //this.disc.value = '';
      //alert(this.slave.value);
      var query = this.fvalue;
      var uom = this.slave.value;
      var qty = "1";
      if (this.qtyid != "") {
		qty = document.getElementById(this.qtyid).value;
      }
      this.ajaxHelper.sendRequest( 'query=' + query + '&pack=' + uom + '&qty=' + qty );
   },

   ajaxUpdate:  function(request) {
		//alert(request.responseXML);
      var slaveOptions = this.createOptions(request.responseXML.documentElement);
      
      //var optionsObj = this.slave.options;
      var text, camount, ccount, weight, cubic, cases, inners, dozens, pieces;
      for ( var i = 0 ; i < slaveOptions.length ; i++ )
      {
         //optionsObj.add( slaveOptions[i] );
         camount = slaveOptions[i][0];
         text = slaveOptions[i][1];
         //if (this.slave.value != '')
		 ccount = slaveOptions[i][2];
		 
		 weight = slaveOptions[i][3];
		 cubic = slaveOptions[i][4];
		 cases = slaveOptions[i][5];
		 inners = slaveOptions[i][6];
		 dozens = slaveOptions[i][7];
		 pieces = slaveOptions[i][8];
      }   
      //this.slave.disabled = false;
      //alert(document.getElementById("ucTop_lblCount").innerHTML + ' ' + camount);
      if (text != '') {
		if (camount != '')
			document.getElementById("ucTop_lblAmount").innerHTML = camount;
		
		if (ccount != ''){
			document.getElementById("ucTop_lblCount").innerHTML = ccount;
			document.getElementById("ucTop_txtCount").value = ccount;	
		}
		
		if (weight != '' && document.getElementById("ucTop_lblTotWeight"))
			document.getElementById("ucTop_lblTotWeight").innerHTML = weight;
		if (cubic != '' && document.getElementById("ucTop_lblTotCubic"))
			document.getElementById("ucTop_lblTotCubic").innerHTML = cubic;
		if (cases != '' && document.getElementById("ucTop_lblTotCase"))
			document.getElementById("ucTop_lblTotCase").innerHTML = cases;
		if (inners != '' && document.getElementById("ucTop_lblTotInner"))
			document.getElementById("ucTop_lblTotInner").innerHTML = inners;
		if (dozens != '' && document.getElementById("ucTop_lblTotDozen"))
			document.getElementById("ucTop_lblTotDozen").innerHTML = dozens;
		if (pieces != '' && document.getElementById("ucTop_lblTotPiece"))
			document.getElementById("ucTop_lblTotPiece").innerHTML = pieces;
		
		alert(text);
      }
      //this.slave.style.display = "";
      //this.calc.innerText = "";
      //CouponChecked();
   },

   createOptions: function(ajaxResponse) {
      var newOptions = [];
      //alert(ajaxResponse);
      var entries = ajaxResponse.getElementsByTagName('entry');
      
      for ( var i = 0 ; i < entries.length ; i++ ) {
	     var type  = this.getElementContent(entries[i],'optionType');
         var text  = this.getElementContent(entries[i],'optionText');
         var value = this.getElementContent(entries[i],'optionValue');
         var weight = this.getElementContent(entries[i],'optionWeight');
         var cubic = this.getElementContent(entries[i],'optionCubic');
         var cases = this.getElementContent(entries[i],'optionCS');
         var inners = this.getElementContent(entries[i],'optionIN');
         var dozens = this.getElementContent(entries[i],'optionDZ');
         var pieces = this.getElementContent(entries[i],'optionPC');
         
		newOptions.push( new Array(type, text, value, weight, cubic, cases, inners, dozens, pieces) );

      }
      return newOptions;
   },

   handleError: function(request) {
      if ( this.options.errorHandler )
         this.options.errorHandler(request);
   },

   getElementContent: function(element,tagName) {
      var childElement = element.getElementsByTagName(tagName)[0];
      return childElement.text != undefined ? childElement.text : childElement.textContent;
   }

};