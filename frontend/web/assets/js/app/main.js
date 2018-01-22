require.config({
	baseUrl : "/assets/js/app",
	paths : {
		jquery : "./lib/jquery",
		
		ebug : "./models/ebug",
		ebugVIndex : "./models/ebugVIndex",
		ebugClick : "./models/ebugClick",
	}
});

require([
         'jquery',
         'ebug',
         'ebugVIndex',
         'ebugClick'
         ],function($,ebug){});