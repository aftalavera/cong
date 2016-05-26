function ArtMenu_GetElement(e, name)
{
	name = name.toLowerCase();
	for (var n = e.firstChild; null != n; n = n.nextSibling)
		if (1 == n.nodeType && name == n.nodeName.toLowerCase())
			return n;
	return null;
}

function ArtMenu_GetElements(e, name) 
{
	name = name.toLowerCase();
	var elements = [];
	for (var n = e.firstChild; null != n; n = n.nextSibling)
		if (1 == n.nodeType && name == n.nodeName.toLowerCase())
			elements[elements.length] = n;
	return elements;
}

function ArtMenu_SpansSetup(menu)
{
	var menuUL = ArtMenu_GetElement(menu, 'ul');
	if (null == menuUL) return;
	var menuULLI = ArtMenu_GetElements(menuUL, 'li');
	for (var i = 0; i < menuULLI.length; i++) {
		var li = menuULLI[i];
		if ('separator' == li.className) continue;
		var a = ArtMenu_GetElement(li, 'a');
		if (null == a) continue;
		if (isIncluded(a.href, window.location.href)) {
			a.className = 'active';
    }
	  var spant = document.createElement('span');
	  spant.className = 't';
	  while (a.firstChild)
	    spant.appendChild(a.firstChild);
	  a.appendChild(document.createElement('span')).className = 'l';
	  a.appendChild(document.createElement('span')).className = 'r';
	  a.appendChild(spant);
	}
}

function ArtMenu_GetMenuContainer(e)
{
    var container = e;
    while (null == ArtMenu_GetElement(container, 'ul')) {
        container = ArtMenu_GetElement(container, 'div');
        if (null == container) return;
    }
    return container;
}

function load_ADxMenu(sender)
{
	if (sender.id && sender.id != "") {
	    var isIE = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
	    var isIE6 = navigator.userAgent.toLowerCase().indexOf("msie 6.0") != -1;

        var ul = sender.getElementsByTagName("ul");

        if (ul.length == 0) return;

	    if (ul[0].className.indexOf("art-menu") != -1) { /* Artisteer's Menu */
            var container = ArtMenu_GetMenuContainer(sender);
            if (null != container && typeof container.spansExtended == "undefined") {
                ArtMenu_SpansSetup(container);
                container.spansExtended = true;
            }
        } else { /* CCS Menu */
	        CCSMenu_SpansSetup(sender.id);
    	    if (isIE && isIE6) ADxMenu_IESetup(sender.id);
        	CCSMenu_TreeMenuSetup(sender.id);
        	menuMarkActLink(sender.id);
		}
	}
}
