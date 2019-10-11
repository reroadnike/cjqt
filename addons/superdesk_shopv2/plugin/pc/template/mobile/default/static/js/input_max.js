
/*
 * �ı������Ŵ����
 */ 


 function TextMagnifier(options) {

	 this.config = {
		
		inputElem          :     '.inputElem',     // �����Ŀ��Ԫ��
		parentCls          :     '.parentCls',     // Ŀ��Ԫ�صĸ���
		align              :     'right',            // ���뷽ʽ�� ['top','bottom','left','right']���� Ĭ��Ϊtop
		splitType          :     [3,4,4],          // ��ֹ���
		delimiter          :     '-'                // �ָ������Զ���
	 };

	 this.cache = {
		  isFlag  :  false
	 };
	 this.init(options);
 }

 TextMagnifier.prototype = {
	 
	 constructor: TextMagnifier,

	 init: function(options) {
		this.config = $.extend(this.config,options || {});
		var self = this,
			_config = self.config,
			_cache = self.cache;
		
		self._bindEnv();
		
		
	 },
	 /*
	  * ��body��̬���HTML����
	  * @method _appendHTML
	  */
	 _appendHTML: function($this,value) {
		 var self = this,
			 _config = self.config,
			 _cache = self.cache;

		 var html = '',
			 $parent = $($this).closest(_config.parentCls);

			 if($('.js-max-input',$parent).length == 0) {
				html += '<div class="js-max-input"></div>';
				$($parent).append(html);
			 }
			 var value = self._formatStr(value);
			 $('.js-max-input',$parent).html(value);
	 },
	 /*
	  * ��Ŀ��Ԫ�ض�λ
	  * @method _position
	  * @param target
	  */
	 _position: function(target){
		var self = this,
			_config = self.config;
		var elemWidth = $(target).outerWidth(),
			elemHeight = $(target).outerHeight(),
			elemParent = $(target).closest(_config.parentCls),
			containerHeight = $('.js-max-input',elemParent).outerHeight(); 
		
		$(elemParent).css({"position":'relative'});
		
		switch(true){
			
			case _config.align == 'top':
				
				$('.js-max-input',elemParent).css({'position':'absolute','top' :-elemHeight - containerHeight/2,'left':0});
				break;
			
			case _config.align == 'left':

				$('.js-max-input',elemParent).css({'position':'absolute','top' :0,'left':0});
				break;
			
			case _config.align == 'bottom':

				$('.js-max-input',elemParent).css({'position':'absolute','top' :elemHeight + 4 + 'px','left':0});
				break;
			
			case _config.align == 'right':

				$('.js-max-input',elemParent).css({'position':'absolute','top' :0,'left':elemWidth + 2 + 'px'});
				break;
		}
	 },
	 /**
	  * ���¼�
	  * @method _bindEnv
	  */
	 _bindEnv: function(){
		var self = this,
			_config = self.config,
			_cache = self.cache;

		// ʵʱ���������ֵ�ı仯
		$(_config.inputElem).each(function(index,item){

			$(item).keyup(function(e){
				var value = $.trim(e.target.value),
					parent = $(this).closest(_config.parentCls);
				if(value == '') {
					self._hide(parent);
				}else {

					var html = $.trim($('.js-max-input',parent).html());

					if(html != '') {
						self._show(parent);
					}
				}
				self._appendHTML($(this),value);
				self._position($(this));
			});

			$(item).unbind('focusin');
			$(item).bind('focusin',function(){
				var parent = $(this).closest(_config.parentCls),
					html = $.trim($('.js-max-input',parent).html());

				if(html != '') {
					self._show(parent);
				}
			});

			$(item).unbind('focusout');
			$(item).bind('focusout',function(){
				var parent = $(this).closest(_config.parentCls);
				self._hide(parent);
			});
		});
	 },
	 /**
	  * ��ʽ����
	  * @method _formatStr
	  */
	 _formatStr: function(str){
		var self = this,
			_config = self.config,
			_cache = self.cache;
		var count = 0,
			output = [];
		for(var i = 0, ilen = _config.splitType.length; i < ilen; i++){
			var s = str.substr(count,_config.splitType[i]);
			if(s.length > 0){
				output.push(s);
			}
			count+= _config.splitType[i];
		}
		return output.join(_config.delimiter);
	 },
	 /*
	  * ��ʾ �Ŵ�����
	  * @method _show
	  */
	 _show: function(parent) {
		var self = this,
			_config = self.config,
			_cache = self.cache;
		if(!_cache.isFlag) {
			$('.js-max-input',parent).show();
			_cache.isFlag = true;
		}
	 },
	 /*
	  * ���� �Ŵ�����
	  * @method hide
	  * {public}
	  */
	 _hide: function(parent) {
		var self = this,
			_config = self.config,
			_cache = self.cache;
		if(_cache.isFlag) {
			$('.js-max-input',parent).hide();
			_cache.isFlag = false;
		}
	 }
 };
