var FoxUIPicker = function(params) {

	var defaults = {
		title: '',
		showCancelButton: true,
		updateValueOnTouch: false,
		inputReadOnly: true,
		valueDivider: ' '
	};
	var self = this;
	self.params = $.extend({}, defaults, params || {});

	self.initValue = function(value) {
		if (typeof(value) === 'string') {
			return value.split(self.params.valueDivider);
		}
		return value;
	}

	if (self.params.value) {
		self.params.value = self.initValue(self.params.value);

	}
	if (self.params.initValue) {
		self.params.initValue = self.initValue(self.params.initValue);

	}
	if (self.value) {
		self.value = self.initValue(self.value);
	}

	if (self.params.valueDivider == '') {
		self.params.valueDivider = ' ';
	}

	self.oldValue = [];
	self.inited = false;
	self.setValue = function(values, transition) {
		var valueIndex = 0;
		var len = self.columns.length;

		for (var i = 0; i < len; i++) {
			if (!self.columns[i].divider) {

				self.columns[i].setValue(values[valueIndex], transition);
				valueIndex++;
			}
		}
	};


	self.updateValue = function(colIndex) {
		var newValue = [];
		var newDisplayValue = [];

		$.each(self.columns, function() {
			if (!this.divider) {
				newValue.push(this.value);
				newDisplayValue.push(this.displayValue);
			}
		});
		if (newValue.indexOf(undefined) >= 0) {
            return;
        }
		  
		self.value = newValue;
		self.displayValue = newDisplayValue;
        
		if (self.params.onChange ) {
		
			self.params.onChange(self, colIndex);
		}
		if (self.params.updateValueOnTouch) {
			if (self.input && self.input.length > 0) {

				var value = self.getValue();
				if (self.params.formatValue) {
					value = self.params.formatValue(value);
				} else {
					value = value.join(self.params.valueDivider);
				}
				self.input.val(value);
				self.input.trigger('change');
			}
		}

	};
	self.getValue = function() {
		var values = [];
		$.each(self.columns, function() {
			if (!this.divider) {
				if(this.value!==undefined && this.values && this.values.length>0){
					values.push(this.value);	
				}
				
			}

		});
		return values;
	}
	self.getValueIndex  = function(colIndex){
		
 
		var len = self.columns.length;
		var valueIndex = 0;
		for(var i=0;i<len;i++){
			if(!self.columns[i].divider){
			    if(colIndex==i) {
			    	break;
			    }
			    valueIndex++;
			}
		}
		return valueIndex;
		
	}
	

	self.getColumnHTML = function(column, onlyItems) {
		var columnItemsHTML = '';
		var columnHTML = '';
		if (column.divider) {
			columnHTML += '<div class="fui-picker-col fui-picker-col-divider ' + (column.align ? 'fui-picker-col-' + column.align : '') + ' ' + (column.css || '') + '">' + (column.content || '') + '</div>';
		} else {
		 
			for (var i = 0; i < column.values.length; i++) {
				var columnValue = column.values[i],text;
				if (typeof(columnValue) == 'object') {
					value = columnValue.value?columnValue.value:columnValue.text;
					text = columnValue.text;
				} else{
					value = text =   columnValue;
				}
				columnItemsHTML += '<div class="fui-picker-item" data-value="' + value + '">' + text + '</div>';
			}
			columnHTML += '<div class="fui-picker-col ' + (column.align ? 'fui-picker-col-' + column.align : '') + ' ' + (column.css || '') + '"><div class="fui-picker-wrapper">' + columnItemsHTML + '</div></div>';
		}
		return onlyItems ? columnItemsHTML : columnHTML;
	};

	self.initColumn = function(colObj, updateItems) {
		var colElement = $(colObj);
		var colIndex = colElement.index();
		var col = self.columns[colIndex];
		if (col.divider) {
			return;
		}


		col.container = colElement;
		col.wrapper = colElement.find('.fui-picker-wrapper');
		col.items = col.wrapper.find('.fui-picker-item');
		 

		var wrapperHeight, itemHeight, itemsHeight, minTranslate, maxTranslate;
		col.resize = function() {
			if(col.items.length<=0){
				return;
			}

			var width = 0,
				height = col.container[0].offsetHeight;
			wrapperHeight = col.wrapper[0].offsetHeight;
			
			itemHeight = col.items[0].offsetHeight;
			itemsHeight = itemHeight * col.items.length;
			minTranslate = height / 2 - itemsHeight + itemHeight / 2;
			maxTranslate = height / 2 - itemHeight / 2;
		};
		col.resize();
        col.wrapper.transform('translate3d(0,' + maxTranslate + 'px,0)').transition(0);
		var activeIndex = 0;
		col.setValue = function(value, transition, updateValue) {

			transition = transition || '';
			if(typeof(value)=='object') {
				value = value.value?value.value:value.text;
			}
	 
			var newItem = col.wrapper.find('.fui-picker-item[data-value="' + value + '"]');
			var newActiveIndex = -1;
			if (newItem.length > 0) {
				newActiveIndex = newItem.index();
			}

			if (newActiveIndex == -1) {
				return;
			}

			var newTranslate = -newActiveIndex * itemHeight + maxTranslate;
			col.wrapper.transition(transition);
			col.wrapper.transform('translate3d(0,' + (newTranslate) + 'px,0)');

			col.updateItems(newActiveIndex, newTranslate, transition, updateValue);
		};

		col.updateItems = function(activeIndex, translate, transition, updateValue) {
			translate = translate || $.getTranslate(col.wrapper[0], 'y');
			activeIndex = activeIndex || -Math.round((translate - maxTranslate) / itemHeight);


			if (activeIndex < 0) {
				activeIndex = 0;
			}
			if (activeIndex >= col.items.length) {
				activeIndex = col.items.length - 1;
			}

			var oldActiveIndex = col.activeIndex;
			col.activeIndex = activeIndex;
			col.wrapper.find('.selected').removeClass('selected');


			if (updateValue || updateValue === undefined) {

				var selectedItem = col.items.eq(activeIndex).addClass('selected').transform('');
				col.value = selectedItem.data('value');
				col.displayValue = col.displayValues ? col.displayValues[activeIndex] : col.value;

				if (oldActiveIndex != activeIndex) {


					if (col.onChange) {
						col.onChange(self, col);
					}
					self.updateValue(colIndex);
				}
			}

		};

		if (updateItems) {
			col.updateItems(0, maxTranslate, 0);
		}

		var allowItemClick = true;
		var isTouched, isMoved;
		var touchStartY, touchCurrentY;
		var touchStartTime, touchEndTime;
		var startTranslate, returnTo, currentTranslate, prevTranslate, velocityTranslate, velocityTime;

		function onColTouchStart(e) {

			if (isMoved || isTouched) {
				return;
			}
	 	    
			isTouched = true;
			touchStartY = touchCurrentY = e.type === 'touchstart' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
			touchStartTime = (new Date()).getTime();

			allowItemClick = true;
			startTranslate = currentTranslate = $.getTranslate(col.wrapper[0], 'y');

		}

		function onColTouchMove(e) {

			if (!isTouched) {
				return;
			}
			e.preventDefault();
			allowItemClick = false;
			touchCurrentY = e.type === 'touchmove' ? e.originalEvent.targetTouches[0].pageY : e.pageY;
			if (!isMoved) {

				isMoved = true;
				startTranslate = currentTranslate = $.getTranslate(col.wrapper[0], 'y');
				col.wrapper.transition(0);
			}
			e.preventDefault();
			
			var diff = touchCurrentY - touchStartY;
			currentTranslate = startTranslate + diff;
			returnTo = undefined;

			if (currentTranslate < minTranslate) {
				currentTranslate = minTranslate - Math.pow(minTranslate - currentTranslate, 0.8);
				returnTo = 'min';
			}
			if (currentTranslate > maxTranslate) {
				currentTranslate = maxTranslate + Math.pow(currentTranslate - maxTranslate, 0.8);
				returnTo = 'max';
			}

			col.wrapper.transform('translate3d(0,' + currentTranslate + 'px,0)');
			col.updateItems(undefined, currentTranslate, 0);

			velocityTranslate = currentTranslate - prevTranslate || currentTranslate;
			velocityTime = (new Date()).getTime();
			prevTranslate = currentTranslate;
		}

		function onColTouchEnd(e) {
		 
			if (!isTouched || !isMoved) {
			 
				isTouched = isMoved = false;
				return;
			}
			
			isTouched = isMoved = false;
			col.wrapper.transition('');
			if (returnTo) { 
				if (returnTo === 'min') {
					col.wrapper.transform('translate3d(0,' + minTranslate + 'px,0)');
				} else {
					col.wrapper.transform('translate3d(0,' + maxTranslate + 'px,0)');
				}
			}
			touchEndTime = new Date().getTime();
		 
			var velocity, newTranslate;
			if (touchEndTime - touchStartTime > 300) {
				newTranslate = currentTranslate; 
			} else {
				allowItemClick = true;
				velocity = Math.abs(velocityTranslate / (touchEndTime - velocityTime));
				newTranslate = currentTranslate + velocityTranslate * 10;
				
			}

			newTranslate = Math.max(Math.min(newTranslate, maxTranslate), minTranslate);

			var activeIndex = -Math.floor((newTranslate - maxTranslate) / itemHeight);
			newTranslate = -activeIndex * itemHeight + maxTranslate;
			col.wrapper.transform('translate3d(0,' + (parseInt(newTranslate, 10)) + 'px,0)');
			col.updateItems(activeIndex, newTranslate, '', true);

	     
			 setTimeout(function() {
				allowItemClick = true;
			}, 100);	
		 
			
		}

		function onColClick(e) {
		 
			if (!allowItemClick) {
				return;
			}
			var value = $(this).data('value');
		 
			col.setValue(value);
		 
		}

		col.initEvents = function(detach) {
			var method = detach ? 'off' : 'on';
			col.container[method]($.touchEvents.start, onColTouchStart);
			col.container[method]($.touchEvents.move, onColTouchMove);
			col.container[method]($.touchEvents.end, onColTouchEnd);
			col.items[method]('click', onColClick);
		};

		col.container[0].destory = function() {

			col.initEvents(true);
		};

		col.replaceValues = function(values) {
			col.initEvents(true);
			 
			col.values = values;
			var newItemsHTML = self.getColumnHTML(col, true);
			 
			col.wrapper.html(newItemsHTML);
			col.items = col.wrapper.find('.fui-picker-item');
			col.resize();
			
			col.setValue(values[0], 0, true);
			col.initEvents();
			
			if(self.params.onReplaceValues){
				self.params.onReplaceValues(self,colIndex);
			}
		};
 
		col.initEvents();

	}
	self.setInputValue = function(value) {



		if (self.input && self.input.length > 0) {
			value = value || self.getValue();
			if (self.params.formatValue) {
				value = self.params.formatValue(value);

			} else {
				value = value.join(self.params.valueDivider);
			}
			 $(self.input).val(value);
			 
			
		}

	}
	self.isshow = false;
	self.show = function() {

		if (self.isshow) {
			return;
		}
		self.columns = [];

		var titleHTML = '';
		var cancelButtonHTML = "<a href='javascript:' class='cancel fui-picker-cancel'>取消</a>";
		titleHTML += "<div class='fui-picker-header'>" +
			"<div class='left '>" + (self.params.showCancelButton ? cancelButtonHTML : '') + "</div>" +
			"<div class='center'>" + (self.params.title || "") + "</div>" +
			"<div class='right'><a href='javascript:' class='success fui-picker-confirm'>确定</a></div>" +
			"</div>";

		var columnsHTML = '';
		$.each(self.params.columns, function() {
			columnsHTML += self.getColumnHTML(this);
			self.columns.push(this);
		});

		self.pickerHTML =
			'<div class="fui-picker ' + (self.params.css || '') + '">' +
			titleHTML +
			'<div class="fui-picker-content">' +
			columnsHTML +
			'<div class="fui-picker-highlight"></div>' +
			'</div>' +
			'</div>';

		self.container = FoxUI.modal.show(self.pickerHTML, false);

		setTimeout(function() {
			self.container.find('.fui-picker').addClass('in');
		}, 0);
		self.isshow = true;
		self.container.find('.fui-picker-col').each(function() {
			var updateItems = true;
			if ((!self.inited && self.params.value) || (self.inited && self.value)) {
				updateItems = false;
			}
			self.initColumn(this, updateItems);
		});
		self.container.find('.fui-picker-confirm').on('click', function() {
			if (!self.params.updateValueOnTouch) {
				self.setInputValue();
			}
			self.close();
		});

		if (self.params.showCancelButton) {
			self.container.find('.fui-picker-cancel').on('click', function() {
				if(self.oldValue!=''){
					 
					self.setInputValue(self.oldValue);	
				}
				self.close();
			});
		}



		if (!self.inited) {
			if (self.params.value) {
				
				self.setValue(self.params.value, 0);
			} else if(self.params.initValue){
				self.setValue(self.params.initValue, 0);
			}
			
		} else {
		 
			if (self.value) {
				 
				self.setValue(self.value, 0);
			}else if(self.initValue){
				self.setValue(self.initValue, 0);
			}
		}
		self.inited = true;
		if (self.params.onShow) {
			self.params.onShow(self);
		}
	}

	if (self.params.input) {

		self.input = $(self.params.input);
		if (self.input.length > 0) {
			
			self.oldValue = self.input.val();
			
			if (self.params.inputReadOnly) {
				self.input.attr('readonly', true);
			}
			self.input.on('click', function(e) {
				e.preventDefault();
				// 安卓微信软键盘问题
				if ($.device.isWeixin && $.device.android && self.params.inputReadOnly) {
					this.focus();
					this.blur();
				}
				if (!self.isshow) {
					self.show();
				}
			});
		}
	}


	self.close = function() {
		if (!self.isshow) {
			return;
		}

		self.container.find('.fui-picker').addClass('out').transitionEnd(function() {
			self.container.find('.fui-picker-col').each(function() {
				if (this.destroy) {
					this.destroy();
				}
			});
			self.container.remove();
		});

		self.isshow = false;
		if (self.params.onClose) {
			self.params.onClose(self);
		}


	};
	self.destroy = function() {

		self.close();
		if (self.input && self.input.length > 0) {
			self.input.off('click');
		}
		$('html').off('click');
		$(window).off('resize');
	}

	$('html').on('click', function(e) {
		if (!self.isshow) {
			return;
		}
		if (self.input && self.input.length > 0) {
			if (e.target === self.input[0]) {
				return;
			}
		}
		if ($(e.target).parents('.fui-picker').length > 0) {
			return;
		}
		self.close();
	});


	function resizeColumns() {
		if (!self.isshow) {
			return;
		}
		$.each(self.columns, function() {
			if (!this.divider) {
				this.resize();
			 
				this.setValue(this.value, 0);
			}
		});
		if(self.params.onResize){
			self.params.onResize(picker);
		}
	}
	$(window).on('resize', resizeColumns);

}

$(function() {

	$.fn.picker = function(params) {
		var args = arguments;
		return this.each(function() {
			if (!this) return;
			var $this = $(this);
			var picker = $this.data("picker");
			if (!picker) {
				params = $.extend({
					input: this,
					value: $this.val() ? $this.val() : ''
				}, params);
				picker = new FoxUIPicker(params);
				$this.data("picker", picker);
			}
			if (typeof params === typeof "a") {
				picker[params].apply(picker, Array.prototype.slice.call(args, 1));
			}
		});
	};

})