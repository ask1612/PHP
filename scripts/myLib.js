/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * Function to request http server.
 */
function requestHttpServer(json_str) {
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        try {
            xmlhttp = new XMLHttpRequest();
        }
        catch (e) {
        }
    }
    else
    {// code for IE6, IE5
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e) {
        }
    }
    xmlhttp.responseType = "text";
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4) {
            if (xmlhttp.status === 200) {
                handlerResponseHttpServer(xmlhttp.responseText);
            }
        }
    }
    var param1 = encodeURIComponent(json_str);
    var post = "json_str=" + param1;
    xmlhttp.open("POST", "handler.php", true);
    //xmlhttp.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(post);
}
/**
 * handlerResponseHttpServer
 * @param {type} responseText
 * @returns {undefined}
 */
function handlerResponseHttpServer(responseText) {
    var json_obj = JSON.parse(responseText);
    if (json_obj.success === "yes") {
        switch (json_obj.head) {
            case 'disabled_enabled':
                if (json_obj.data === "enabled") {
                    document.getElementById("delete").disabled = false;
                    document.getElementById("save").disabled = false;
                } else {
                    document.getElementById("delete").disabled = true;
                    document.getElementById("save").disabled = true;
                }
                break;
            case 'html':
                document.getElementById('txtHint').innerHTML = json_obj.data;
                break;
            case 'delete':
                alert(json_obj.data);
                break;

        }
    } else
        alert(json_obj.msg);
}
/**
 * 
 * @param {type} msg
 * @returns {Boolean}
 */
function empty(msg) {
    // Determine whether a variable is empty
    //   return (msg === "" || msg === 0 || msg === "0" || msg === null || msg === false || (is_array(msg) && msg.length === 0));
    return (msg === '');
}

/**
 *  testRequestHttpServer
 * @param {type} doc_id
 * @returns {undefined}
 */
function doRequestHttpServer(head, doc_id, name_tbl, msg) {
    var json_obj = {
        head: "",
        doc_id: "",
        name_tbl: ""
    };
    json_obj.head = head;
    json_obj.doc_id = doc_id;
    json_obj.name_tbl = name_tbl;
    var json_str = JSON.stringify(json_obj);
    if (!empty(msg))
        result = confirm(msg);
    else
        result = true;
    if (result)
        requestHttpServer(json_str);
}


/**
 * shineLinks
 * @param {type} id
 * @returns {undefined}
 */
function shineLinks(id) {

    try {

        var el = document.getElementById(id).getElementsByTagName('a');

        var url = document.location.href;

        for (var i = 0; i < el.length; i++) {

            if (url == el[i].href) {

                el[i].className = 'active';

            }
            ;

        }
        ;

    } catch (e) {
    }

}

/**
 * Keyboard input only number
 */
function Ftest(obj)
{
    if (this.ST)
        return;
    var ov = obj.value;
    var ovrl = ov.replace(/\d*\.?\d*/, '').length;
    this.ST = true;
    if (ovrl > 0) {
        obj.value = obj.lang;
        Fshowerror(obj);
        return
    }
    obj.lang = obj.value;
    this.ST = null;
}
/**
 * Fshowerror
 * @param {type} obj
 * @returns {Fshowerror}
 */
function Fshowerror(obj)
{
    if (!this.OBJ)
    {
        this.OBJ = obj;
        obj.style.backgroundColor = 'pink';
        this.TIM = setTimeout(Fshowerror, 50)
    }
    else
    {
        this.OBJ.style.backgroundColor = '';
        clearTimeout(this.TIM);
        this.ST = null;
        Ftest(this.OBJ);
        this.OBJ = null
    }
}

/**
 * Hide selected. It is used in the penalty and termination  form
 * @param {type} a
 * @param {type} id
 * @returns {undefined}
 */
function Selected(a, id) {

    var label = a;
    var tid = id;
    var br = navigator.userAgent;
    if (br.search(/MSIE/) > -1) {
        // code for IE6, IE5
        var block = 'block';
    } else {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var block = 'table-cell';
    }
    if (tid == 'inp1')
    {
        if (label == 0) {
            document.getElementById("fio").style.display = 'none';
            document.getElementById("sel_fio").style.display = 'none';
            document.getElementById("name").style.display = 'none';
            document.getElementById("in_name").style.display = 'none';
            document.getElementById("err").style.display = 'none';
            document.getElementById('td0').rowSpan = 1;
            document.getElementById('td0').colSpan = 1;
        } else if (label == 1) {
            document.getElementById("fio").style.display = block;
            document.getElementById("sel_fio").style.display = block;
            document.getElementById("name").style.display = 'none';
            document.getElementById("in_name").style.display = 'none';
            document.getElementById("err").style.display = block;
            document.getElementById('td0').rowSpan = 2;
            document.getElementById('td0').colSpan = 1;

        } else if (label == 2) {
            document.getElementById("fio").style.display = 'none';
            document.getElementById("sel_fio").style.display = 'none';
            document.getElementById("name").style.display = block;
            document.getElementById("in_name").style.display = block;
            document.getElementById("err").style.display = block;
            document.getElementById('td0').rowSpan = 2;
            document.getElementById('td0').colSpan = 1;

        }

    }
    if (tid == 'inp6')
    {
        if (label == 0) {
            document.getElementById("td6").style.display = 'none';
            document.getElementById("td7").style.display = 'none';
            document.getElementById("td8").style.display = 'none';
            document.getElementById('td5').rowSpan = 2;
        } else if (label == 1) {
            document.getElementById("td6").style.display = block;
            document.getElementById("td7").style.display = block;
            document.getElementById("td8").style.display = block;
            document.getElementById('td5').rowSpan = 3;

        } else if (label == 2) {
            document.getElementById("td6").style.display = 'none';
            document.getElementById("td7").style.display = 'none';
            document.getElementById("td8").style.display = 'none';
            document.getElementById('td5').rowSpan = 2;
        }

    }
    if (tid == 'inp9')
    {
        if (label == 0) {
            document.getElementById("summa").style.display = 'none';
            document.getElementById("in_summa").style.display = 'none';
            document.getElementById("er_summa").style.display = 'none';
        } else if (label == 1) {
            document.getElementById("summa").style.display = block;
            document.getElementById("in_summa").style.display = block;
            document.getElementById("er_summa").style.display = block;

        } else if (label == 2) {
            document.getElementById("summa").style.display = 'none';
            document.getElementById("in_summa").style.display = 'none';
            document.getElementById("er_summa").style.display = 'none';
        }

    }

}
/**
 * 
 * @returns {undefined}
 */
function doSelectPenalty() {
    var e = document.getElementById("inp1");
    var selIndex = e.options[e.selectedIndex].value;
    Selected(selIndex, 'inp1');
    e = document.getElementById("inp6");
    selIndex = e.options[e.selectedIndex].value;
    Selected(selIndex, 'inp6');
    e = document.getElementById("inp9");
    selIndex = e.options[e.selectedIndex].value;
    Selected(selIndex, 'inp9');

}
/**
 * Hide selected. It is used in the termination form
 */

function doSelectTerm() {
    var e = document.getElementById("inp1");
    var selIndex = e.options[e.selectedIndex].value;
    Selected(selIndex, 'inp1');
    e = document.getElementById("inp6");
    selIndex = e.options[e.selectedIndex].value;
    Selected(selIndex, 'inp6');
}
/**
 * doOnLoadPenalty
 * @param {type} head
 * @param {type} doc_id
 * @param {type} name_tbl
 * @param {type} msg
 * @returns {undefined}
 */
function doOnLoadPenalty(head, doc_id, name_tbl, msg) {
    doSelectPenalty();
    doRequestHttpServer(head, doc_id, name_tbl, msg);
}

/**
 * doOnLoadTerm
 * @param {type} head
 * @param {type} doc_id
 * @param {type} name_tbl
 * @param {type} msg
 * @returns {undefined}
 */
function doOnLoadTerm(head, doc_id, name_tbl, msg) {
    doSelectTerm();
    doRequestHttpServer(head, doc_id, name_tbl, msg);
}
