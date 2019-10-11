$(function() {
	var FoxUIDateTimePicker = function(showDate,showTime) {

		
        var self = this;
        self.showDate = showDate===undefined?true:showDate;
		self.showTime = showTime===undefined?true:showTime;
 
		self.today = new Date();
		self.getDays = function(max) {
			var days = [];
			for (var i = 1; i <= (max || 31); i++) {
				days.push(this.format(i));
			}
			return days;
		};

		self.getDaysByYearAndMonth = function(year, month) {
			var day = new Date(year, parseInt(month) + 1 - 1, 1);
			var max = new Date(day - 1).getDate();
			return self.getDays(max);
		};

		self.format = function(num) {
			return num < 10 ? "0" + num : num
		};

		self.getTodayValue = function() {
			
			var values =[];
			if(self.showDate){
				values.push(self.today.getFullYear());
				values.push(self.format(self.today.getMonth() + 1));
				values.push(self.format(self.today.getDate()));
			}
			if(self.showTime){
				values.push(self.format(self.today.getHours()));
				values.push(self.format(self.today.getMinutes()));
			}
			return values;
		};

		self.params = {

            initValue: self.getTodayValue(),
			onChange: function(picker,colIndex) {
				 
				if(!self.showDate){
					return;
				}
				var daysValue = picker.columns[4].value;
 				var days = self.getDaysByYearAndMonth(picker.columns[0].value,picker.columns[2].value);
 				if(colIndex==0 || colIndex==2 ){
					picker.columns[4].replaceValues(days);
				}
                if(daysValue > days.length) { 
                	daysValue = days.length;
                }
                picker.columns[4].setValue(daysValue);
			},

			formatValue: function(value) {
				var ret ='';
				
				if(self.showDate && self.showTime){
					ret+=value[0] + '-' + value[1] + '-' + value[2] + ' ' + value[3] + ':' + value[4];
				}else if(self.showDate){
					ret+=value[0] + '-' + value[1] + '-' + value[2];
				}else if(self.showTime){
					ret+=value[0] + ':' + value[1];
				}
			 
				return ret;
			},
		 	columns: []
		}
		 
		if( self.showDate){
			self.params.columns.push({
				values: (function() {
					var years = [];
					for (var i = 1950; i <= 2030; i++) {
						years.push(i);
					}
					return years;
				})()
			});
			self.params.columns.push({
					divider: true,
					content: '-'
				});
		 	self.params.columns.push({
				values: (function() {
					var months = [];
					for (var i = 1; i <= 12; i++) {
						months.push( self.format(i) );
					}
					return months;
				})()
			});
			self.params.columns.push({
					divider: true,
					content: '-'
				});
			self.params.columns.push({
				values: self.getDays()
			});
			if(self.showTime){
				self.params.columns.push({
					divider: true,
					content: ' '
				});
			}
		}
		if(self.showTime){
			
			
			self.params.columns.push({
				
				values: (function() {
					var hours = [];
					for (var i = 0; i <= 23; i++) {
			 			hours.push(self.format(i));
					}
					return hours;
				})()
			});
			self.params.columns.push({ 
				divider: true,
				content: ':'
			});
			 
			self.params.columns.push({
				values: (function() {
					var minutes = [];
					for (var i = 0; i <= 59; i++) {
						minutes.push(i < 10 ? '0' + i : i);
					}
					return minutes;
				})()
			});
		}

	}

   var dt1 = new FoxUIDateTimePicker(true,true); 
   var dt2 = new FoxUIDateTimePicker(true,false); 
   var dt3 = new FoxUIDateTimePicker(false,true); 
	
	$.fn.datetimePicker = function(params) {
		
		return this.each(function() {
			if (!this) return;
			params = params || {};
			var p = $.extend(dt1.params, params || {});
			$(this).picker(p);

			if (p.value) {
				$(this).val(p.formatValue(p.value));
			}
		});
	};
	 
	$.fn.datePicker = function(params) {
 
		return this.each(function() {
			if (!this) return;
			params = params || {};
			var p = $.extend(dt2.params, params || {});
			$(this).picker(p);
			if (p.value) {
				$(this).val(p.formatValue(p.value));
			}
		});
	};
	
	$.fn.timePicker = function(params) {
 
		return this.each(function() {
			if (!this) return;
			params = params || {};
			var p = $.extend(dt3.params, params || {});
			$(this).picker(p);
			if (p.value) {
				$(this).val(p.formatValue(p.value));
			}
		});
	};
});