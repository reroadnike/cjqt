var FoxUIRelatePicker = function(params) {

	var self = this;

	self.allValues = [];


	self.setAllValues = function(obj, lastTag) {


		$.each(obj.children, function() {
			var value = this.value ? this.value : this.text;
			if (value === undefined) {
				return false;
			}

			var tag = lastTag + value;

			self.allValues.push({
				tag: tag,
				children: this.children
			});
			self.setAllValues(this, tag);
		});

	}

	self.getChildren = function(value) {
		var children = [];
		$.each(self.allValues, function() {

			if (this.tag == value) {

				children = this.children;
				return false;
			}
		});
		return children;
	}
	self.getNextColIndex = function(picker, colIndex) {
		var nextColIndex = -1;
		$.each(picker.columns, function(i) {
			if (i > colIndex && !this.divider) {
				nextColIndex = i;
				return false;
			}
		});
		return nextColIndex;

	}

	self.getColIndex = function(findValueIndex) {

		var len = self.params.columns.length;
		var valueIndex = 0;
		var locals = [];
		var ret = -1;

		for (var i = 0; i < len; i++) {

			if (!self.params.columns[i].divider) {

				if (valueIndex == findValueIndex) {
					ret = i;
					break;
				}
				valueIndex++;
			}
		}
		return ret;

	}
	self.params = {

		onChange: function(picker, colIndex) {

			if (colIndex >= picker.columns.length - 1) {
				return;
			}

			var selectedValue = [];
			for (var i = 0; i <= colIndex; i++) {
				if (!picker.columns[i].divider) {
					selectedValue.push(picker.columns[i].value);
				}
			}
			selectedValue = selectedValue.join('');

			if (!selectedValue) {
				return;
			}


			var nextColIndex = self.getNextColIndex(picker, colIndex);
			if (nextColIndex != -1) {
				var children = self.getChildren(selectedValue);
				if (children) {
					picker.columns[nextColIndex].replaceValues(children);
				}
				self.params.onChange(picker, nextColIndex);
			}
		},
		onShow: function(picker) {

			//设置默认值
			if (picker.input && picker.input.length > 0) {
				var val = picker.input.val();
				if (val) {
					var values = val.split(self.params.valueDivider);

					$.each(picker.columns, function(i) {
						if (!this.divider) {
							var valueIndex = picker.getValueIndex(i);

							this.setValue(values[valueIndex], 0);
						}
					})
				}
			}
		}
	};
	self.firstValues = [];
	self.setFirstValue = function(obj) {

		var val = obj;
		if (typeof(obj) == 'object') {
			val = obj.value ? obj.value : obj.text;
		}

		self.firstValues.push(val);

		if (typeof(obj) == 'object' && obj.children) {
			self.setFirstValue(obj.children[0]);
		}
	}
	self.init = function(_params, inputValue) {

		self.params = $.extend(self.params, _params || {});

		//初始化所有数据的子数据
		if (!self.allValues || self.allValues.length <= 0) {
			$.each(self.params.columns[0].values, function(i) {
				self.allValues.push({
					tag: this.value ? this.value : this.text,
					children: this.children
				});

				var tag = this.value ? this.value : this.text;
				self.setAllValues(this, tag, i);
			});
		}
		//初始化第一条默认记录
		if (!self.firstValues || self.firstValues.length <= 0) {
			self.setFirstValue(self.params.columns[0].values[0]);
		}

		//初始化每一列的数据
		self.params.valueDivider = self.params.valueDivider || ' ';
		if (inputValue) {
			self.params.value = inputValue.split(self.params.valueDivider);
		}
		if (self.params.value === undefined) {
			self.params.value = self.firstValues;
		}
		
		var maxValueIndex = 0;
		$.each(self.params.columns, function(i) {
			if(!this.divider){
				maxValueIndex++;
			}
		});

		for (var i = 1; i <= maxValueIndex - 1; i++) {
			var a = self.params.value.slice(0, i).join('');
			var colIndex = self.getColIndex(i);
			self.params.columns[colIndex].values = self.getChildren(a);
		}
	};
}


$(function() {

	$.fn.relatePicker = function(params) {

		var relate = new FoxUIRelatePicker(params);


		return this.each(function() {
		
		 
			if (!this) return;
			relate.init(params, $(this).val());
			$(this).picker(relate.params);
		});
	};

});