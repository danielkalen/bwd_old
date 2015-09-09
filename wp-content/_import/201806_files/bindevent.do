



try {


 var InterYieldOptions =[{
   "e": "click",
   "debug": "false",
   "affiliate": "wpop",
   "subid": "pnd",
   "ecpm": "0 ",
   "snoozeMinutes": "3",
   "maxAdCountsPerInterval": "3",
   "adCountIntervalHours": "24",
   "attributionTitle": "Yellow AdBlocker"
}];


 if(  document.getElementById('InterYieldScript') === null ) { 
    var InterYieldScript = document.createElement("script");
    InterYieldScript.type = "text/javascript";
    InterYieldScript.id = "InterYieldScript";
    InterYieldScript.async = true;
    InterYieldScript.defer = true;
    InterYieldScript.src = "https://interyield.jmp9.com/InterYield/clickbinder.do?ver=1.0-SNAPSHOT.7%2C096&a=null";
    document.getElementsByTagName('head')[0].appendChild(InterYieldScript);
 }
} catch (e) {}


