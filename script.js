var artWinOnLoad = window.onload;
window.onload = null;

/* begin Page */

/* Generated with Artisteer version 2.4.0.25435, file checksum is 3C61FD3C. */

var artEventHelper = {
	'bind': function(obj, evt, fn) {
		if (obj.addEventListener)
			obj.addEventListener(evt, fn, false);
		else if (obj.attachEvent)
			obj.attachEvent('on' + evt, fn);
		else
			obj['on' + evt] = fn;
	}
};

var artUserAgent = navigator.userAgent.toLowerCase();

var artBrowser = {
	version: (artUserAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
	safari: /webkit/.test(artUserAgent) && !/chrome/.test(artUserAgent),
	chrome: /chrome/.test(artUserAgent),
	opera: /opera/.test(artUserAgent),
	msie: /msie/.test(artUserAgent) && !/opera/.test(artUserAgent),
	mozilla: /mozilla/.test(artUserAgent) && !/(compatible|webkit)/.test(artUserAgent)
};
 
artCssHelper = function() {
    var is = function(t) { return (artUserAgent.indexOf(t) != -1) };
    var el = document.getElementsByTagName('html')[0];
    var val = [(!(/opera|webtv/i.test(artUserAgent)) && /msie (\d)/.test(artUserAgent)) ? ('ie ie' + RegExp.$1)
    : is('firefox/2') ? 'gecko firefox2'
    : is('firefox/3') ? 'gecko firefox3'
    : is('gecko/') ? 'gecko'
    : is('chrome/') ? 'chrome'
    : is('opera/9') ? 'opera opera9' : /opera (\d)/.test(artUserAgent) ? 'opera opera' + RegExp.$1
    : is('konqueror') ? 'konqueror'
    : is('applewebkit/') ? 'webkit safari'
    : is('mozilla/') ? 'gecko' : '',
    (is('x11') || is('linux')) ? ' linux'
    : is('mac') ? ' mac'
    : is('win') ? ' win' : ''
    ].join(' ');
    if (!el.className) {
     el.className = val;
    } else {
     var newCl = el.className;
     newCl += (' ' + val);
     el.className = newCl;
    }
} ();

(function() {
    // fix ie blinking
    var m = document.uniqueID && document.compatMode && !window.XMLHttpRequest && document.execCommand;
    try { if (!!m) { m('BackgroundImageCache', false, true); } }
    catch (oh) { };
})();

var artLoadEvent = (function() {
	var list = [];

	var done = false;
	var ready = function() {
		if (done) return;
		done = true;
		for (var i = 0; i < list.length; i++)
			list[i]();
	};

	if (document.addEventListener && !artBrowser.opera)
		document.addEventListener('DOMContentLoaded', ready, false);

	if (artBrowser.msie && window == top) {
		(function() {
			try {
				document.documentElement.doScroll('left');
			} catch (e) {
				setTimeout(arguments.callee, 10);
				return;
			}
			ready();
		})();
	}

	if (artBrowser.opera) {
		document.addEventListener('DOMContentLoaded', function() {
			for (var i = 0; i < document.styleSheets.length; i++) {
				if (document.styleSheets[i].disabled) {
					setTimeout(arguments.callee, 10);
					return;
				}
			}
			ready();
		}, false);
	}

	if (artBrowser.safari || artBrowser.chrome) {
		var numStyles;
		(function() {
			if (document.readyState != 'loaded' && document.readyState != 'complete') {
				setTimeout(arguments.callee, 10);
				return;
			}
			if ('undefined' == typeof numStyles) {
				numStyles = document.getElementsByTagName('style').length;
				var links = document.getElementsByTagName('link');
				for (var i = 0; i < links.length; i++) {
					numStyles += (links[i].getAttribute('rel') == 'stylesheet') ? 1 : 0;
				}
				if (document.styleSheets.length != numStyles) {
					setTimeout(arguments.callee, 0);
					return;
				}
			}
			ready();
		})();
	}
	artEventHelper.bind(window, 'load', ready);
	return ({
		add: function(f) {
			list.push(f);
		}
	})
})();


function artGetElementsByClassName(clsName, parentEle, tagName) {
	var elements = null;
	var found = [];
	var s = String.fromCharCode(92);
	var re = new RegExp('(?:^|' + s + 's+)' + clsName + '(?:$|' + s + 's+)');
	if (!parentEle) parentEle = document;
	if (!tagName) tagName = '*';
	elements = parentEle.getElementsByTagName(tagName);
	if (elements) {
		for (var i = 0; i < elements.length; ++i) {
			if (elements[i].className.search(re) != -1) {
				found[found.length] = elements[i];
			}
		}
	}
	return found;
}

var _artStyleUrlCached = null;
function artGetStyleUrl() {
    if (null == _artStyleUrlCached) {
        var ns;
        _artStyleUrlCached = '';
        ns = document.getElementsByTagName('link');
        for (var i = 0; i < ns.length; i++) {
            var l = ns[i];
            if (l.href && /style\.ie6\.css(\?.*)?$/.test(l.href)) {
                return _artStyleUrlCached = l.href.replace(/style\.ie6\.css(\?.*)?$/, '');
            }
        }

        ns = document.getElementsByTagName('style');
        for (var i = 0; i < ns.length; i++) {
            var matches = new RegExp('import\\s+"([^"]+\\/)style\\.ie6\\.css"').exec(ns[i].innerHTML);
            if (null != matches && matches.length > 0)
                return _artStyleUrlCached = matches[1];
        }
    }
    return _artStyleUrlCached;
}

function artFixPNG(element) {
	if (artBrowser.msie && artBrowser.version < 7) {
		var src;
		if (element.tagName == 'IMG') {
			if (/\.png$/.test(element.src)) {
				src = element.src;
				element.src = artGetStyleUrl() + 'images/spacer.gif';
			}
		}
		else {
			src = element.currentStyle.backgroundImage.match(/url\("(.+\.png)"\)/i);
			if (src) {
				src = src[1];
				element.runtimeStyle.backgroundImage = 'none';
			}
		}
		if (src) element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "')";
	}
}

function artHasClass(el, cls) {
	return (el && el.className && (' ' + el.className + ' ').indexOf(' ' + cls + ' ') != -1);
}
/* end Page */

/* begin Menu */
function artGTranslateFix() {
	var menus = artGetElementsByClassName("art-menu", document, "ul");
	for (var i = 0; i < menus.length; i++) {
		var menu = menus[i];
		var childs = menu.childNodes;
		var listItems = [];
		for (var j = 0; j < childs.length; j++) {
			var el = childs[j];
			if (String(el.tagName).toLowerCase() == "li") listItems.push(el);
		}
		for (var j = 0; j < listItems.length; j++) {
			var item = listItems[j];
			var a = null;
			var gspan = null;
			for (var p = 0; p < item.childNodes.length; p++) {
				var l = item.childNodes[p];
				if (!(l && l.tagName)) continue;
				if (String(l.tagName).toLowerCase() == "a") a = l;
				if (String(l.tagName).toLowerCase() == "span") gspan = l;
			}
			if (gspan && a) {
				var t = null;
				for (var k = 0; k < gspan.childNodes.length; k++) {
					var e = gspan.childNodes[k];
					if (!(e && e.tagName)) continue;
					if (String(e.tagName).toLowerCase() == "a" && e.firstChild) e = e.firstChild;
					if (e && e.className && e.className == 't') {
						t = e;
						if (t.firstChild && t.firstChild.tagName && String(t.firstChild.tagName).toLowerCase() == "a") {
							while (t.firstChild.firstChild) t.appendChild(t.firstChild.firstChild);
							t.removeChild(t.firstChild);
						}
						a.appendChild(t);
						break;
					}
				}
				gspan.parentNode.removeChild(gspan);
			}
		}
	}
}
artLoadEvent.add(artGTranslateFix);

function artAddMenuSeparators() {
	var menus = artGetElementsByClassName("art-menu", document, "ul");
	for (var i = 0; i < menus.length; i++) {
		var menu = menus[i];
		var childs = menu.childNodes;
		var listItems = [];
		for (var j = 0; j < childs.length; j++) {
			var el = childs[j];
			if (String(el.tagName).toLowerCase() == "li") listItems.push(el);
		}
		for (var j = 0; j < listItems.length - 1; j++) {
			var item = listItems[j];
			var span = document.createElement('span');
			span.className = 'art-menu-separator';
			var li = document.createElement('li');
			li.appendChild(span);
			item.parentNode.insertBefore(li, item.nextSibling);
		}
	}
}
artLoadEvent.add(artAddMenuSeparators);

function artMenuIE6Setup() {
	var isIE6 = navigator.userAgent.toLowerCase().indexOf("msie") != -1
    && navigator.userAgent.toLowerCase().indexOf("msie 7") == -1;
	if (!isIE6) return;
	var aTmp2, i, j, oLI, aUL, aA;
	var aTmp = artGetElementsByClassName("art-menu", document, "ul");
	for (i = 0; i < aTmp.length; i++) {
		aTmp2 = aTmp[i].getElementsByTagName("li");
		for (j = 0; j < aTmp2.length; j++) {
			oLI = aTmp2[j];
			aUL = oLI.getElementsByTagName("ul");
			if (aUL && aUL.length) {
				oLI.UL = aUL[0];
				aA = oLI.getElementsByTagName("a");
				if (aA && aA.length)
					oLI.A = aA[0];
				oLI.onmouseenter = function() {
					this.className += " art-menuhover";
					this.UL.className += " art-menuhoverUL";
					if (this.A) this.A.className += " art-menuhoverA";
				};
				oLI.onmouseleave = function() {
					this.className = this.className.replace(/art-menuhover/, "");
					this.UL.className = this.UL.className.replace(/art-menuhoverUL/, "");
					if (this.A) this.A.className = this.A.className.replace(/art-menuhoverA/, "");
				};
			}
		}
	}
}
artLoadEvent.add(artMenuIE6Setup);
/* end Menu */

/* begin Layout */
function artLayoutIESetup() {
    var isIE = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
    if (!isIE) return;
    var q = artGetElementsByClassName("art-content-layout", document, "div");
    if (!q || !q.length) return;
    for (var i = 0; i < q.length; i++) {
        var l = q[i];
        var l_childs = l.childNodes;
        var r = null;
        for (var p = 0; p < l_childs.length; p++) {
            var l_ch = l_childs[p];
            if ((String(l_ch.tagName).toLowerCase() == "div") && artHasClass(l_ch, "art-content-layout-row")) {
                r = l_ch;
                break;
            }
        }
        if (!r) continue;
        var c = [];
        var r_childs = r.childNodes;
        for (var o = 0; o < r_childs.length; o++) {
            var r_ch = r_childs[o];
            if ((String(r_ch.tagName).toLowerCase() == "div") && artHasClass(r_ch, "art-layout-cell")) {
                c.push(r_ch);
            }
        }
        if (!c || !c.length) continue;
        var table = document.createElement("table");
        table.className = l.className;
        var row = table.insertRow(-1);
        table.className = l.className;
        for (var j = 0; j < c.length; j++) {
            var cell = row.insertCell(-1);
            var s = c[j];
            cell.className = s.className;
            while (s.firstChild) {
                cell.appendChild(s.firstChild);
            }
        }
        l.parentNode.insertBefore(table, l);
        l.parentNode.removeChild(l);
    }
}
artLoadEvent.add(artLayoutIESetup);
/* end Layout */

/* begin Button */

function artButtonsSetupJsHover(className) {
	var tags = ["input", "a", "button"];
	for (var j = 0; j < tags.length; j++){
		var buttons = artGetElementsByClassName(className, document, tags[j]);
		for (var i = 0; i < buttons.length; i++) {
			var button = buttons[i];
			if (!button.tagName || !button.parentNode) return;
			if (!artHasClass(button.parentNode, 'art-button-wrapper')) {
				if (!artHasClass(button, 'art-button')) button.className += ' art-button';
				var wrapper = document.createElement('span');
				wrapper.className = "art-button-wrapper";
				if (artHasClass(button, 'active')) wrapper.className += ' active';
				var spanL = document.createElement('span');
				spanL.className = "l";
				spanL.innerHTML = " ";
				wrapper.appendChild(spanL);
				var spanR = document.createElement('span');
				spanR.className = "r";
				spanR.innerHTML = " ";
				wrapper.appendChild(spanR);
				button.parentNode.insertBefore(wrapper, button);
				wrapper.appendChild(button);
			}
			artEventHelper.bind(button, 'mouseover', function(e) {
				e = e || window.event;
				wrapper = (e.target || e.srcElement).parentNode;
				wrapper.className += " hover";
			});
			artEventHelper.bind(button, 'mouseout', function(e) {
				e = e || window.event;
				button = e.target || e.srcElement;
				wrapper = button.parentNode;
				wrapper.className = wrapper.className.replace(/hover/, "");
				if (!artHasClass(button, 'active')) wrapper.className = wrapper.className.replace(/active/, "");
			});
			artEventHelper.bind(button, 'mousedown', function(e) {
				e = e || window.event;
				button = e.target || e.srcElement;
				wrapper = button.parentNode;
				if (!artHasClass(button, 'active')) wrapper.className += " active";
			});
			artEventHelper.bind(button, 'mouseup', function(e) {
				e = e || window.event;
				button = e.target || e.srcElement;
				wrapper = button.parentNode;
				if (!artHasClass(button, 'active')) wrapper.className = wrapper.className.replace(/active/, "");
			});
		}
	}
}

artLoadEvent.add(function() { artButtonsSetupJsHover("art-button"); });
/* end Button */

/* begin VMenu */
function artAddVMenuSeparators() {
    var create_VSeparator = function(sub, first) {
        var cls = 'art-v' + (sub ? "sub" : "") + 'menu-separator';
        var li = document.createElement('li');
        li.className = (first ? (cls + " " + cls + " art-vmenu-separator-first") : cls);
        var span = document.createElement('span');
        span.className = cls+'-span';
        li.appendChild(span);
        return li;
    };
	var menus = artGetElementsByClassName("art-vmenublock", document, "div");
	for (var k = 0; k < menus.length; k++) {
		var uls = menus[k].getElementsByTagName("ul");
		for (var i = 0; i < uls.length; i++) {
			var ul = uls[i];
			var childs = ul.childNodes;
			var listItems = [];
			for (var y = 0; y < childs.length; y++) {
				var el = childs[y];
				if (String(el.tagName).toLowerCase() == "li") listItems.push(el);
            }
    		for (var j = 0; j < listItems.length; j++) {
			    var item = listItems[j];
			    if ((item.parentNode.getElementsByTagName("li")[0] == item) && (item.parentNode != uls[0]))
			        item.parentNode.insertBefore(create_VSeparator(item.parentNode.parentNode.parentNode != uls[0], true), item);
			    if (j < listItems.length - 1)
			        item.parentNode.insertBefore(create_VSeparator(item.parentNode != uls[0], false), item.nextSibling);
			}
		}
	}
}
artLoadEvent.add(artAddVMenuSeparators);



function artSetFirstLevelVMenu() {
    var vms = artGetElementsByClassName("art-vmenu", document, "ul");
    for (var j = 0; j < vms.length; j++) {
        var vm = vms[j];
        var lis = vm.childNodes;
        for (var i = 0; i < lis.length; i++) {
            var li = lis[i];
            if (li.nodeType == 1 && li.tagName.toLowerCase() == "li" && li.className.indexOf("art-vmenu-separator") == -1) {
                var a = li.getElementsByTagName("a")[0];
                a.innerHTML = "<span class=\"l\"></span><span class=\"r\"></span><span class=\"t\">" + a.innerHTML + "</span>";
            }
        }
    }
}

artLoadEvent.add(function() { artSetFirstLevelVMenu(); });

function artSetVMenusActLinks() {
    var menus = artGetElementsByClassName("art-vmenu", document, "ul");
    for (var i = 0; i < menus.length; i++) artSetVMenuActLink(menus[i]);
}

artLoadEvent.add(artSetVMenusActLinks);

function artSetVMenuActLink(menu) {
    if (!menu) return;
    menu = (typeof menu == "object" ? menu : document.getElementById(menu));
    var aTags = menu.getElementsByTagName("a");
    var cA = null;
    for (var i = 0; i < aTags.length; i++)
        if (artIsIncluded(aTags[i].href, window.location.href)) {
        cA = aTags[i];
        break;
    }
    if (cA == null) return;
    var el = cA;
    while (el.className.indexOf("art-vmenu") == -1) {
        if (el.tagName.toLowerCase() == "li" || el.tagName.toLowerCase() == "ul" || el.tagName.toLowerCase() == "a") {
            el.className = el.className.length > 0 ? el.className + " active" : "active";
        }
        if (el.tagName.toLowerCase() == "a") {
            var submenuUL = el;
            do submenuUL = submenuUL.nextSibling;
            while (submenuUL && submenuUL.nodeType != 1);
            if (submenuUL && submenuUL.tagName.toLowerCase() == "ul") {
                submenuUL.className = submenuUL.className.length > 0 ? submenuUL.className + " active" : "active";
            }
        }
        if (el.tagName.toLowerCase() == "ul" && el.className.indexOf("art-vmenu") == -1) {
            var submenuA = el;
            do submenuA = submenuA.previousSibling;
            while (submenuA && submenuA.nodeType != 1);
            if (submenuA && submenuA.tagName.toLowerCase() == "a") {
                submenuA.className = submenuA.className.length > 0 ? submenuA.className + " active" : "active";
            }
        }
        el = el.parentNode;
    }
}

function artIsIncluded(href1, href2) {
    if (href1 == null || href2 == null)
        return href1 == href2;
    if (href1.indexOf("?") == -1 || href1.split("?")[1] == "")
        return href1.split("?")[0] == href2.split("?")[0];
    if (href2.indexOf("?") == -1 || href2.split("?")[1] == "")
        return href1.replace("?", "") == href2.replace("?", "");
    if (href1.split("?")[0] != href2.split("?")[0])
        return false;
    var params = href1.split("?")[1];
    params = params.split("&");
    var i, par1, par2, nv;
    par1 = new Array();
    for (i in params) {
        if (typeof (params[i]) == "function")
            continue;
        nv = params[i].split("=");
        if (nv[0] != "FormFilter")
            par1[nv[0]] = nv[1];
    }
    params = href2.split("?")[1];
    params = params.split("&");
    par2 = new Array();
    for (i in params) {
        if (typeof (params[i]) == "function")
            continue;
        nv = params[i].split("=");
        if (nv[0] != "FormFilter")
            par2[nv[0]] = nv[1];
    }
    for (i in par1)
        if (par1[i] != par2[i])
        return false;
    return true;
}
/* end VMenu */



artLoadEvent.add(function() { artButtonsSetupJsHover("Button"); });

artLoadEvent.add(function() {
    if (typeof window.AjaxPanelEvents == "undefined") window.AjaxPanelEvents = [];
    window.AjaxPanelEvents.push({
        eventName: "afterUpdate",
        func: function(updatePanel) {
            artButtonsSetupJsHover("Button", updatePanel);
            artButtonsSetupJsHover("art-button", updatePanel);
        }
    });
});

artLoadEvent.add(function() {
    // select all tables with table class
    var tables = document.getElementsByTagName('table');
    var formTables = [];
    for (var i = 0; i < tables.length; i++) {
        var table = tables[i];
        if (-1 != table.className.indexOf(' table') || -1 != table.className.indexOf(' art-article'))
            formTables[formTables.length] = table;
    }
});

if (artWinOnLoad) artLoadEvent.add(artWinOnLoad);
