var jien = {};

// prevent console errors on browsers without firebug
if (!window.console) {
    window.console = {};
    console.log = function(){};
}
(function($) {
	jien = {
		ui: {
			growl: function(msg, type){
				var method = '';
				if(!type) type = 'success';
				switch(type){
					default:
						method = 'icon-ok-sign';
					break;

					case 'success':
						method = 'icon-ok-sign';
					break;

					case 'warning':
						method = 'icon-warning-sign';
					break;

					case 'error':
						method = 'icon-warning-sign';
						type = 'warning';
					break;
				}
				$.bootstrapGrowl('<span class="'+method+'"></span> '+msg, { type: type });

			},

			modal: function(title, content, opts){
				var html = '<div id="modal" class="modal hide fade">';
	    			html += '	<div class="modal-header">';
	      			html += '		<a href="#" class="close">&times;</a>';
	      			html += '		<h3>'+title+'</h3>';
	    			html += '	</div>';
	    			html += '	<div class="modal-body">';
	      			html += '	<p>'+content+'</p>';
	    			html += '</div>';
				    /*<div class="modal-footer hide">
				      <a href="#" class="btn primary">Ok</a>
				      <a href="#" class="btn secondary">Cancel</a>
				    </div>*/
				    if($('#modal').length == 0){
				    	$('body').append(html);
				    }
					$('#modal').modal({
						'show': true,
						'backdrop': true,
						'keyboard': true
					});
			}
		},
		util: {
			serializeForm: function(target){
				var post = {};
				var form = $(target).serializeArray();
				$.each(form, function(k,v){
					if(v.value){
                                            post[v.name] = v.value;
					}else{
                                            post[v.name] = '';
                                        }
				});
				return post;
			},
			strip: function(html){
   				var tmp = document.createElement("DIV");
   				tmp.innerHTML = html;
   				return tmp.textContent||tmp.innerText;
			},
			ucwords: function(str) {
    			return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {return $1.toUpperCase();})
			},
			ucfirst: function(string){
    			return string.charAt(0).toUpperCase() + string.slice(1);
			},
			equalHeight: function(target){
				var t=0;
				var t_elem;
				$(target).each(function () {
				    if ( $(this).height() > t ) {
				        t_elem=$(this);
				        t=t_elem.height();
				    }
				});
				$(target).css('height', t+'px');
			},
			shuffle: function(arr) {
				if(arr){
					for(var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);
					return arr;
				}
			},
			extract: function(field, data){
				var arr = new Array();
				if(data != undefined){
					$.each(data, function(k,v){
						arr.push(v[field]);
					});
				}
				return arr;
			},
			suffix: function(n) {
				var d = (n|0)%100;
				var suffix = d > 3 && d < 21 ? 'th' : ['th', 'st', 'nd', 'rd'][d%10] || 'th';
				return n + suffix;
			},
			numberFormat: function( number, decimals, dec_point, thousands_sep ) {
			    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
			    var d = dec_point == undefined ? "," : dec_point;
			    var t = thousands_sep == undefined ? "," : thousands_sep, s = n < 0 ? "-" : "";
			    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
			    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
			},
			parseRel: function(options){
		        var xOptions = options.split('|');
		        var attribs = {};
		        $.each(xOptions,function(k,v){
		        	var xOption = v.split('=');
		        	var name = xOption[0];
		        	var val = xOption[1];
		        	attribs[name] = val;
		        });
		        return attribs;
			},
			timeleft: function(a){
				var hours=Math.floor(a/3600);
				var minutes=Math.floor(a/60)-(hours*60);
				var seconds=a-(hours*3600)-(minutes*60);
				var hs=' hr';var ms=' min';var ss=' sec';
				if (hours!=1) {hs+='s';}
				if (minutes!=1) {ms+='s';}
				if (seconds!=1) {ss+='s';}
				return hours+hs+', '+minutes+ms;
			},
			dateformat: function(dateObj, format){
				return dateObj.format(format);
			},
			strtotime: function(str, now) {
				var i, match, s, strTmp = '', parse = '';
				var regexp = /(\d\d\d\d)(-)?(\d\d)(-)?(\d\d)(T)?(\d\d)(:)?(\d\d)(:)?(\d\d)(\.\d+)?(Z|(-)(\d\d)(:)?(\d\d))/;
				strTmp = str;
				strTmp = strTmp.replace(/\s{2,}|^\s|\s$/g, ' '); // unecessary spaces
				strTmp = strTmp.replace(/[\t\r\n]/g, ''); // unecessary chars
			    if (strTmp == 'now') {
			        return (new Date()).getTime()/1000; // Return seconds, not milli-seconds
			    } else if (!isNaN(parse = Date.parse(strTmp))) {
			        return (parse/1000);
				} else if (strTmp.match(new RegExp(regexp))) {
				// to detect UTC 8601 date format...
				var datum = new Date();

				var d = strTmp.match(new RegExp(regexp));
				var offset = 0;

				datum.setUTCDate(1);
				datum.setUTCFullYear(parseInt(d[1],10));
				datum.setUTCMonth(parseInt(d[3],10) - 1);
				datum.setUTCDate(parseInt(d[5],10));
				datum.setUTCHours(parseInt(d[7],10));
				datum.setUTCMinutes(parseInt(d[9],10));
				datum.setUTCSeconds(parseInt(d[11],10));
				if (d[12]) {
				datum.setUTCMilliseconds(parseFloat(d[12]) * 1000);
				} else {
				datum.setUTCMilliseconds(0);
				}
				if (d[13] != 'Z') {
				offset = (d[15] * 60) + parseInt(d[17],10);
				offset *= ((d[14] == '-') ? -1 : 1);
				datum.setTime(datum.getTime() - offset * 60 * 1000);
				}
				return datum.getTime()/1000;
			    } else if (now) {
			        now = new Date(now*1000); // Accept PHP-style seconds
			    } else {
			        now = new Date();
			    }
			    strTmp = strTmp.toLowerCase();
			    var __is =
			    {
			        day:
			        {
			            'sun': 0,
			            'mon': 1,
			            'tue': 2,
			            'wed': 3,
			            'thu': 4,
			            'fri': 5,
			            'sat': 6
			        },
			        mon:
			        {
			            'jan': 0,
			            'feb': 1,
			            'mar': 2,
			            'apr': 3,
			            'may': 4,
			            'jun': 5,
			            'jul': 6,
			            'aug': 7,
			            'sep': 8,
			            'oct': 9,
			            'nov': 10,
			            'dec': 11
			        }
			    };
			    var process = function (m) {
			        var ago = (m[2] && m[2] == 'ago');
			        var num = (num = m[0] == 'last' ? -1 : 1) * (ago ? -1 : 1);

			        switch (m[0]) {
			            case 'last':
			            case 'next':
			                switch (m[1].substring(0, 3)) {
			                    case 'yea':
			                        now.setFullYear(now.getFullYear() + num);
			                        break;
			                    case 'mon':
			                        now.setMonth(now.getMonth() + num);
			                        break;
			                    case 'wee':
			                        now.setDate(now.getDate() + (num * 7));
			                        break;
			                    case 'day':
			                        now.setDate(now.getDate() + num);
			                        break;
			                    case 'hou':
			                        now.setHours(now.getHours() + num);
			                        break;
			                    case 'min':
			                        now.setMinutes(now.getMinutes() + num);
			                        break;
			                    case 'sec':
			                        now.setSeconds(now.getSeconds() + num);
			                        break;
			                    default:
			                        var day;
			                        if (typeof (day = __is.day[m[1].substring(0, 3)]) != 'undefined') {
			                            var diff = day - now.getDay();
			                            if (diff == 0) {
			                                diff = 7 * num;
			                            } else if (diff > 0) {
			                                if (m[0] == 'last') {diff -= 7;}
			                            } else {
			                                if (m[0] == 'next') {diff += 7;}
			                            }
			                            now.setDate(now.getDate() + diff);
			                        }
			                }
			                break;
			            default:
			                if (/\d+/.test(m[0])) {
			                    num *= parseInt(m[0], 10);
			                    switch (m[1].substring(0, 3)) {
			                        case 'yea':
			                            now.setFullYear(now.getFullYear() + num);
			                            break;
			                        case 'mon':
			                            now.setMonth(now.getMonth() + num);
			                            break;
			                        case 'wee':
			                            now.setDate(now.getDate() + (num * 7));
			                            break;
			                        case 'day':
			                            now.setDate(now.getDate() + num);
			                            break;
			                        case 'hou':
			                            now.setHours(now.getHours() + num);
			                            break;
			                        case 'min':
			                            now.setMinutes(now.getMinutes() + num);
			                            break;
			                        case 'sec':
			                            now.setSeconds(now.getSeconds() + num);
			                            break;
			                    }
			                } else {
			                    return false;
			                }
			                break;
			        }
			        return true;
			    };
			    match = strTmp.match(/^(\d{2,4}-\d{2}-\d{2})(?:\s(\d{1,2}:\d{2}(:\d{2})?)?(?:\.(\d+))?)?$/);
			    if (match != null) {
			        if (!match[2]) {
			            match[2] = '00:00:00';
			        } else if (!match[3]) {
			            match[2] += ':00';
			        }
			        s = match[1].split(/-/g);
			        for (i in __is.mon) {
			            if (__is.mon[i] == s[1] - 1) {
			                s[1] = i;
			            }
			        }
			        s[0] = parseInt(s[0], 10);
			        s[0] = (s[0] >= 0 && s[0] <= 69) ? '20'+(s[0] < 10 ? '0'+s[0] : s[0]+'') : (s[0] >= 70 && s[0] <= 99) ? '19'+s[0] : s[0]+'';
			        return parseInt(this.strtotime(s[2] + ' ' + s[1] + ' ' + s[0] + ' ' + match[2])+(match[4] ? match[4]/1000 : ''), 10);
			    }

			    var regex = '([+-]?\\d+\\s'+
			        '(years?|months?|weeks?|days?|hours?|min|minutes?|sec|seconds?'+
			        '|sun\\.?|sunday|mon\\.?|monday|tue\\.?|tuesday|wed\\.?|wednesday'+
			        '|thu\\.?|thursday|fri\\.?|friday|sat\\.?|saturday)'+
			        '|(last|next)\\s'+
			        '(years?|months?|weeks?|days?|hours?|min|minutes?|sec|seconds?'+
			        '|sun\\.?|sunday|mon\\.?|monday|tue\\.?|tuesday|wed\\.?|wednesday'+
			        '|thu\\.?|thursday|fri\\.?|friday|sat\\.?|saturday))'+
			        '(\\sago)?';
			    match = strTmp.match(new RegExp(regex, 'gi')); // Brett: seems should be case insensitive per docs, so added 'i'
			    if (match == null) {
			        return false;
			    }
			    for (i = 0; i < match.length; i++) {
			        if (!process(match[i].split(' '))) {
			            return false;
			        }
			    }
			    return (now.getTime()/1000);
			}
		},
        escape : function(str){
            return str.replace(/\\/g, '\\\\').
                replace(/\u0008/g, '\\b').
                replace(/\t/g, '\\t').
                replace(/\n/g, '\\n').
                replace(/\f/g, '\\f').
                replace(/\r/g, '\\r').
                replace(/'/g, '\\\'').
                replace(/"/g, '\\"');
        }
	};


})(jQuery);
