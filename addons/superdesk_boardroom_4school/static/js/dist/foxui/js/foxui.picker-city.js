var relate = new FoxUIRelatePicker();
$(function() {

	$.fn.cityPicker = function(params) {
		return this.each(function() {
			 

			if (!this) return;
			params = params || {};
			relate.params = {
			   columns: [{align:'center', values:FoxUICityPickerData}]	
			}
		    var showCity =params.showCity===undefined?true:params.showCity;
		    var showArea = params.showArea===undefined?true:params.showArea;
		    
		    if(showCity){
		    	relate.params.columns.push( {align:'center'} );
		    	if(showArea){
		    		relate.params.columns.push( {align:'center'} );	
		    	}
		    }
			relate.init(params, $(this).val());
			$(this).relatePicker(relate.params);
		});
	};
 
}); 