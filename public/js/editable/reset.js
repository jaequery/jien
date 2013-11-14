/*
 * 	beResetCSS 0.1 - jQuery plugin
 *	written by Benjamin Mock
 *	http://benjaminmock.de/jquery-css-reset-plugin/
 *
 *	Copyright (c) 2009 Benjamin Mock (http://benjaminmock.de)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */

(function($) {
	$.fn.beResetCSS = function(){
		resetStyles(this);
		return this;
	};

	function resetStyles( element ) {
		var tagName = $(element)[0].tagName.toLowerCase();

		var elements = new Array();
		var styles = new Array();

		elements[0] = [ 'html', 'body', 'div', 'span', 'applet', 'object', 'iframe',
						'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'blockquote', 'pre',
						'a', 'abbr', 'acronym', 'address', 'big', 'cite', 'code',
						'del', 'dfn', 'em', 'font', 'img', 'ins', 'kbd', 'q', 's', 'samp',
						'small', 'strike', 'strong', 'sub', 'sup', 'tt', 'var',
						'b', 'u', 'i', 'center',
						'dl', 'dt', 'dd', 'ol', 'ul', 'li',
						'fieldset', 'form', 'label', 'legend',
						'table', 'caption', 'tbody', 'tfoot', 'thead', 'tr', 'th', 'td'];

	    var s ={
			margin: '0',
			padding: '0',
			border: '0',
			outline: '0',
			fontSize: '100%',
			verticalAlign: 'baseline',
			background: 'transparent'
		};
		styles[0] = s;

		elements[1] = ['body'];
		s = {
			lineHeight: '1'
		};
		styles[1] = s;

		elements[2] = ['ol', 'ul'];
		s = {
			listStyle: 'none'
		}
		styles[2] = s;

		elements[3] = ['blockquote', 'q'];
		s = {
			quotes: 'none'
		}
		styles[3] = s;

		elements[4] = ['ins'];
		s = {
			textDecoration: 'none'
		}
		styles[4] = s;

		elements[5] = ['del'];
		s = {
			textDecoration: 'line-through'
		}
		styles[5] = s;

		elements[6] = ['table'];
		s = {
			borderCollapse: 'collapse',
			borderSpacing: '0'
		}
		styles[6] = s;

		// resetting styles
		$(elements).each(function(i){
			$(this).each(function(k){
				if(tagName == this){
					addStyles(element, styles[i]);
				}
			});
		});
	}

	function addStyles( element, styles ) {
	    for(key in styles){
	        $(element).css(key, styles[key]);
	    }
	}

})(jQuery);