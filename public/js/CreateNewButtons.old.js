/**
 * Fügt die Überschriften für die Buttons hinzu
 */
function tinyMCEafterInit(inst) {
    
    element = $('#mceu_8-body').first();
    element.prepend('<div class="headlines">' +
                                          '<div class="headline_format_text headline_format">Text formatieren</div>' +
                                          '<div class="headline_format_text headline_print">Drucken und Exportieren</div>' +
                                      '</div>');
}
/**
 * 
 * @param {string} textarea_id - ID des Divs in das der Editor eingefuegt wird
 * @returns
 */
function createEditor (textarea_id) {
//  TINYmce Funktion zur Erstellung des Editors
    tinyMCE.init({
        init_instance_callback : "tinyMCEafterInit",
//      Setzt die ID        
        id: textarea_id,
        resize: true,
//      Setzt den Modus
        mode: "textareas",
//      Bindet die Plugins ein
        plugins: 'paste, print',
//		Beim Einfuegen werden Formate geloescht
		paste_auto_cleanup_on_paste: true,
		paste_remove_styles: true,
		paste_strip_class_attributes: true,
        paste_auto_cleanup_on_paste : true,
        paste_postprocess : function(pl, o) {
            // remove extra line breaks
            o.node.innerHTML = o.node.innerHTML.replace(/&nbsp;/ig, " ");
        },
		remove_linebreaks: false,
		paste_word_valid_elements: "p[align],b/strong,ul,ol,li,br,br\/,h2/h1,h2,b/h3,b/h4,b/h5,b/h6,div[align]",
		valid_elements: "p[align],span[class],b/strong,ul,ol,li,br,br\/,h2/h1,h2,b/h3,b/h4,b/h5,b/h6,div[align]",
//      Selektiert Textareas aus denen ein Editor werden soll
        editor_selector: textarea_id,
//      Setzt das ausehen des Editors
        theme: "modern",
        skin:   "flatwhite",
        content_css : "/check/public/css/editor.content.css",
        height: 384,
//      Erstellt die obere Toolbar und fuegt Buttons ein
        toolbar1: "h2 | bold | bullist | alignleft aligncenter alignright | delete_mark",// | text_check_" + textarea_id,

//      Erstellt die untere Toolbar und fuegt Buttons ein
        toolbar2: "print",
//      Entfernt die Menueleiste
        menubar: false,
//      Entfernt die Statusleiste, die anzeigt, wo im DOM man sich befindet
        statusbar: true,
//      Setzt die ID des Bodys innerhalb des iframes
        body_id: textarea_id,
//      Setzt die Klasse des Bodys innerhalb des iframes
        body_class: textarea_id,
        setup: function(ed) {
            /*
//          Erstellt den TextCheck Button
            ed.addButton('text_check_' + textarea_id, {
                onclick: function(){
//                  Prueft, ob IE7 hergenommen wird
                    if(navigator.userAgent.toLowerCase().indexOf('MSIE 7.0'.toLowerCase())>-1){
//                      Selektiert die Klasse des Editors
//                      IE7 kennt das Attribut 'class' nicht und muss 'className' hernehmen
                        var editor_class = ed.getElement().getAttribute('className');
                    }
                    else{
                        var editor_class = ed.getElement().getAttribute('class');
                    }
//                  Prueft, ob das Dropdown zum TextCheck schon erstellt wurde
                    if($('.mce-floatpanel.text_check.' + editor_class).length === 0){
//                      Folgender Code passiert nur beim erstmaligen klicken des TextCheck Buttons:
                        if(navigator.userAgent.toLowerCase().indexOf('MSIE 7.0'.toLowerCase())>-1 || navigator.userAgent.toLowerCase().indexOf('MSIE 8.0'.toLowerCase())>-1){
                            var drop_down_text_check = $(".mce-floatpanel:not([style*='DISPLAY: none'])");
                        }
                        else{
                            var drop_down_text_check = $(".mce-floatpanel:not([style*='display: none'])");
                        }
//                      Selektiert die einzelnen Span Elemente des TextChecks
                        drop_down_text_check.children().children().children('span').each(function(){
//                          Gibt den Spans eine eindeutige ID. zB La_textarea_1 f?r das Label 'Lange S?tze' und den Editor 'textarea_1'
                            $(this).attr('id', $(this).text().substring(0,2) + '_' + editor_class);
//                          Gibt dem Elternelement des Spans die gleiche ID wie dem Span, nur mit einem eingeuegten 'parent'
                            $(this).parent().attr('id', $(this).text().substring(0,2) + '_parent_' + editor_class);
//                          Gibt dem Elternelement die Klasse des Editors
                            $(this).parent().addClass(editor_class);
//                          Gibt dem Dropdown die Klasse des Editors
                            $(this).parent().parent().parent().addClass(editor_class);
//                          Gibt dem Dropdown die Klasse 'TextCheck'
                            $(this).parent().parent().parent().addClass('text_check');
                        });
//                      Gibt dem Elternelement von 'Lange S?tze' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#La_' + editor_class).parent().addClass("drop_down_element group_1 group_2 group_3 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Lange S?tze' schon eingefuegt wurde
                        if($('#La_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#La_' + editor_class).before("<img src='/check/public/img/Yellow.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#La_' + editor_class).parent().children().first().addClass('hide_spinner');
//                          Fuegt einen Trennstrich ein
                            $('#La_' + editor_class).parent().after("<div class='mce-menu-item mce-menu-item-sep mce-menu-item-normal mce-stack-layout-item' tabindex='-1' role='separator'></div>");
                        }

//                      Gibt dem Elternelement von 'Wortl?nge' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#Wo_' + editor_class).parent().addClass("drop_down_element group_1 group_3 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Wortl?nge' schon eingefuegt wurde
                        if($('#Wo_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#Wo_' + editor_class).before("<img src='/check/public/img/Pink.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#Wo_' + editor_class).parent().children().first().addClass('hide_spinner');
                        }


//                      Gibt dem Elternelement von 'Floskeln' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#Fl_' + editor_class).parent().addClass("drop_down_element group_1 group_3 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Floskeln' schon eingefuegt wurde
                        if($('#Fl_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#Fl_' + editor_class).before("<img src='/check/public/img/Blue.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#Fl_' + editor_class).parent().children().first().addClass('hide_spinner');
                        }


//                      Gibt dem Elternelement von 'Textverstaendnis' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#Te_' + editor_class).parent().addClass("drop_down_element group_1 group_3 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Textverstaendnis' schon eingefuegt wurde
                        if($('#Te_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#Te_' + editor_class).before("<img src='/check/public/img/Orange.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#Te_' + editor_class).parent().children().first().addClass('hide_spinner');
                        }


//                      Gibt dem Elternelement von 'Anglizismus' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#An_' + editor_class).parent().addClass("drop_down_element group_1 group_3 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Anglizismus' schon eingefuegt wurde
                        if($('#An_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#An_' + editor_class).before("<img src='/check/public/img/Red.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#An_' + editor_class).parent().children().first().addClass('hide_spinner');
//                          Fuegt einen Trennstrich ein
                            $('#An_' + editor_class).parent().after("<div class='mce-menu-item mce-menu-item-sep mce-menu-item-normal mce-stack-layout-item' tabindex='-1' role='separator'></div>");
                        }


//                      Gibt dem Elternelement von 'Nominalstil' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#No_' + editor_class).parent().addClass("drop_down_element group_1 group_2 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Nominalstil' schon eingefuegt wurde
                        if($('#No_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#No_' + editor_class).before("<img src='/check/public/img/Türkis.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#No_' + editor_class).parent().children().first().addClass('hide_spinner');
                        }


//                      Gibt dem Elternelement von 'AktivPassiv' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#Ak_' + editor_class).parent().addClass("drop_down_element group_1 group_2 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'AktivPassiv' schon eingefuegt wurde
                        if($('#Ak_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#Ak_' + editor_class).prepend("<img src='/check/public/img/Green.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#Ak_' + editor_class).parent().children().first().addClass('hide_spinner');
//                          Fuegt einen Trennstrich ein
                            $('#Ak_' + editor_class).parent().after("<div class='mce-menu-item mce-menu-item-sep mce-menu-item-normal mce-stack-layout-item' tabindex='-1' role='separator'></div>");
                        }

//                      Gibt dem Elternelement von 'Wiederholungen' eine besondere Klasse um bei einer Markierung die richtigen Listenelemente zu deaktivieren
                        $('#Wi_' + editor_class).parent().addClass("drop_down_element group_1 group_2 group_3 text_check_element");
//                      Prueft, ob das Bild fuer die Funktion 'Wiederholungen' schon eingefuegt wurde
                        if($('#Wi_parent_' + editor_class + ' img').length === 0){
//                          Fuegt das Bild ein
                            $('#Wi_' + editor_class).before("<img src='/check/public/img/Plumb.png'>");
//                          Gibt dem Icon, das zum laden verwendet wird die Klasse 'hide_spinner', damit man es nicht sieht
                            $('#Wi_' + editor_class).parent().children().first().addClass('hide_spinner');
//                          Fuegt einen Trennstrich ein
                            $('#Wi_' + editor_class).parent().after("<div class='mce-menu-item mce-menu-item-sep mce-menu-item-normal mce-stack-layout-item' tabindex='-1' role='separator'></div>");
                        }
//                      Gibt dem Listenelement, welches zum Aufheben von Markierungen dient, Klassen
                        $('#Ma_' + editor_class).parent().addClass("drop_down_element text_check_element");
//                      Ruft die Funktion zum binden von Events auf
                        addEventListeners(editor_class);
                    }
                },
//             Setzt den Buttontyp fuer den TextCheck Button
               type: 'menubutton',
//             Setzt den Text
               text: 'Text Check',
//             Setzt den Tooltip, der beim hover angezeigt wird
               title: 'Text checken',
//             Sorgt daf?r dass kein icon angezeigt wird
               icon: false,
//             Erstellt die einzelnen Listenelemente mit den Ladeicons
               menu: [
                   {text: 'Lange Sätze', icon: 'fullpage'},
                   {text: 'Wortlänge', icon: 'fullpage'},
                   {text: 'Floskeln', icon: 'fullpage'},
                   {text: 'Textverständnis', icon: 'fullpage'},
                   {text: 'Anglizismen', icon: 'fullpage'},
                   {text: 'Nominalstil', icon: 'fullpage'},
                   {text: 'Aktiv-Passiv', icon: 'fullpage'},
                   {text: 'Wiederholungen', icon: 'fullpage'},
                   {text: 'Markierungen aufheben'}
               ]
            });
            */
//          Erstellt den Button der Aktionen r?ckg?ngig macht
            ed.addButton('undoo', {
//              Setzt den Buttontyp
                type: 'button',
//              Setzt den Tooltip, der beim hover angezeigt wird
                title: 'Rückgängig',
//              Fuegt das 'Undo'-Icon ein
                icon: 'undo',
//              Bei einem Klick,wird die Funktion zum rueckgaengig machen mit dem Klassennamen des Editors aufgerufen
                onclick: function(){
                    if(navigator.userAgent.toLowerCase().indexOf('MSIE 7.0'.toLowerCase())>-1){
                        undoAction(ed.getElement().getAttribute('className'));
                    }
                    else{
                        undoAction(ed.getElement().getAttribute('class'));
                    }

                }
            });
//          Erstellt den Button der Aktionen wiederholt
            ed.addButton('redoo', {
//              Setzt den Buttontyp
                type: 'button',
//              Setzt den Tooltip, der beim hover angezeigt wird
                title: 'Wiederholen',
//              Fuegt das 'Redo'-Icon ein
                icon: 'redo',
//              Bei einem Klick, wird die Funktion zum wiederholen mit dem Klassenamen des Editors aufgerufen
                onclick: function(){
                    if(navigator.userAgent.toLowerCase().indexOf('MSIE 7.0'.toLowerCase())>-1){
                        redoAction(ed.getElement().getAttribute('className'));
                    }
                    else{
                        redoAction(ed.getElement().getAttribute('class'));
                    }
                }
            });

            ed.addButton("h2", {
                tooltip: "Überschrift",
				icon: 'heading',
                onclick: function() {
                    ed.execCommand('FormatBlock', false, 'h2');
                }
            });
            /*
            ed.addButton("delete_mark",
            {
                tooltip: "Markierungen aufheben",
                text: 'Markierungen aufheben',
                minWidth: 220,
                onclick: function()
                {
//                    Prueft, ob IE7 hergenommen wird
                    if(navigator.userAgent.toLowerCase().indexOf('MSIE 7.0'.toLowerCase())>-1){
//                      Selektiert die Klasse des Editors
//                      IE7 kennt das Attribut 'class' nicht und muss 'className' hernehmen
                        var editor_class = ed.getElement().getAttribute('className');
                    }
                    else{
                        var editor_class = ed.getElement().getAttribute('class');
                    }

                    markierungenAufheben(editor_class, 'Ma_parent_' + editor_class);
                }
            });
            */
//          Wenn alle Elemente des Editors erstellt worden sind...
            ed.on('init', function(){
                $('head').append("<link href='/check/public/css/editor.css' media='screen' rel='stylesheet' type='text/css'>");
                //$('iframe').contents().find('head').append("<link href='/check/public/css/editor.content.css' media='screen' rel='stylesheet' type='text/css'>");
//              L?scht den Inhalt des Editors, da dort normalerweise ein Standardeinleitungstext steht
                //$('iframe').contents().find('#' + textarea_id).html('<p> </p>');

//              Ruft die Funktion zur Vergabe der Klassen auf                
                addClasses(textarea_id);
//              Ruft die Funktion zur Vergabe der Events auf
                initButtonEvents(textarea_id);
            });
        }
    });
}
/**
 * Gibt den Toolbars die Klasse des dazugeh?rigen Editors und Top, f?r die obere Toolbar bzw Bot f?r die untere
 * 
 * @param {string} textarea_id - ID des Editors
 * @returns {Boolean}
 */
function addClasses(textarea_id) {
    $('#' + textarea_id + ' .mce-container-body.mce-flow-layout').first().addClass(textarea_id);
    $('#' + textarea_id + ' .mce-container-body.mce-flow-layout').first().addClass('top');
    $('#' + textarea_id + ' .mce-container-body.mce-flow-layout').last().addClass(textarea_id);
    $('#' + textarea_id + ' .mce-container-body.mce-flow-layout').last().addClass('bot');
    return true;
}

/**
 * Gibt den Listenelementen des TextCheck Dropdowns KlickEvents
 * 
 * @param {string} editor_class - Klasse des Editors
 * @returns {undefined}
 */
function addEventListeners(editor_class){

//  Registriert einen Klick auf ein Listenelement    
    $('.'+editor_class + ' .drop_down_element').click(function(){
//      Falls IE kleiner Version 11 verwendet wird        
        if(navigator.userAgent.toLowerCase().indexOf('MSIE'.toLowerCase())>-1 ){
//          Prueft, ob das Listenelement deaktivert ist
            if(!($(this).hasClass('mce-disabled'))){ 
//              Prueft, ob 'Lange S?tze' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'La'){
//                  Ruft die Funktion zum markieren auf                     
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkSatzlaengeIE', 'La_parent_' + editor_class); 
                }
//              Prueft, ob 'Wortl?nge' geklickt wurde                                
                if($(this).attr('id').substring(0,2) === 'Wo'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkWortlaengeIE', 'Wo_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Floskel' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Fl'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkFloskelIE', 'Fl_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Textverst?ndnis' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Te'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkTextverstaendnisIE', 'Te_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Anglizismen' geklickt wurde
                if($(this).attr('id').substring(0,2) === 'An'){
//                  Ruft die Funktion zum markieren auf    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkAnglizismenIE', 'An_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Nominalstil' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'No'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkNominalstilIE', 'No_parent_' + editor_class); 
                }
//              Pr?ft, ob 'AktivPassiv' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Ak'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkAktivPassivIE', 'Ak_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Wiederholungen' geklickt wurde
                if($(this).attr('id').substring(0,2) === 'Wi'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkWiederholungIE', 'Wi_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Markierungen aufheben' gedr?ckt wurde                 
                if($(this).attr('id').substring(0,2) === 'Ma'){
//                  Ruft die Funktion zum aufheben von Markierungen auf                    
                    markierungenAufheben(editor_class, 'Ma_parent_' + editor_class); 
                }
            }
        }
//      Falls IE11 verwendet wird
        else if( navigator.userAgent.toLowerCase().indexOf('TRIDENT'.toLowerCase())>-1 ){
//          Prueft, ob das Listenelement deaktivert ist
            if(!($(this).hasClass('mce-disabled'))){ 
//              Prueft, ob 'Lange S?tze' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'La'){
//                  Ruft die Funktion zum markieren auf                     
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkSatzlaengeIE11', 'La_parent_' + editor_class); 
                }
//              Prueft, ob 'Wortl?nge' geklickt wurde                                
                if($(this).attr('id').substring(0,2) === 'Wo'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkWortlaengeIE11', 'Wo_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Floskel' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Fl'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkFloskelIE11', 'Fl_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Textverst?ndnis' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Te'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkTextverstaendnisIE11', 'Te_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Anglizismen' geklickt wurde
                if($(this).attr('id').substring(0,2) === 'An'){
//                  Ruft die Funktion zum markieren auf    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkAnglizismenIE11', 'An_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Nominalstil' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'No'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkNominalstilIE11', 'No_parent_' + editor_class); 
                }
//              Pr?ft, ob 'AktivPassiv' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Ak'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkAktivPassivIE11', 'Ak_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Wiederholungen' geklickt wurde
                if($(this).attr('id').substring(0,2) === 'Wi'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkWiederholungIE11', 'Wi_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Markierungen aufheben' gedr?ckt wurde                 
                if($(this).attr('id').substring(0,2) === 'Ma'){
//                  Ruft die Funktion zum aufheben von Markierungen auf                    
                    markierungenAufheben(editor_class, 'Ma_parent_' + editor_class); 
                }
            }
        }
//      Falls kein IE verwendet wird        
        else{
//          Prueft, ob das Listenelement deaktivert ist
            if(!($(this).hasClass('mce-disabled'))){ 
//              Prueft, ob 'Lange S?tze' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'La'){
//                  Ruft die Funktion zum markieren auf                     
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkSatzlaenge', 'La_parent_' + editor_class); 
                }
//              Prueft, ob 'Wortl?nge' geklickt wurde                                
                if($(this).attr('id').substring(0,2) === 'Wo'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkWortlaenge', 'Wo_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Floskel' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Fl'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkFloskel', 'Fl_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Textverst?ndnis' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Te'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkTextverstaendnis', 'Te_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Anglizismen' geklickt wurde
                if($(this).attr('id').substring(0,2) === 'An'){
//                  Ruft die Funktion zum markieren auf    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkAnglizismen', 'An_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Nominalstil' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'No'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkNominalstil', 'No_parent_' + editor_class); 
                }
//              Pr?ft, ob 'AktivPassiv' geklickt wurde                
                if($(this).attr('id').substring(0,2) === 'Ak'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkAktivPassiv', 'Ak_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Wiederholungen' geklickt wurde
                if($(this).attr('id').substring(0,2) === 'Wi'){
//                  Ruft die Funktion zum markieren auf                    
                    testDBcontainsString($('iframe').contents().find('#' + editor_class), 'checkWiederholung', 'Wi_parent_' + editor_class); 
                }
//              Pr?ft, ob 'Markierungen aufheben' gedr?ckt wurde                 
                if($(this).attr('id').substring(0,2) === 'Ma'){
//                  Ruft die Funktion zum aufheben von Markierungen auf                    
                    markierungenAufheben(editor_class, 'Ma_parent_' + editor_class); 
                }
            }
        }
    });
}

/**
 * Gibt den Listenelementen der Tabellenfunktionen KlickEvents
 * 
 * @param {string} editor_class
 * @returns {undefined}
 */   
function addEventListenerTable(editor_class){
 
//  Registriert einen Klick auf ein Listenelement   
    $('.'+editor_class + ' .table_func').click(function(){
//      Prueft, ob das Listenelement deaktivert ist        
        if(!($(this).hasClass('mce-disabled'))){ 
//          Pr?ft, ob die Funktion f?r einzeilige Zellen geklickt wurde
            if($(this).attr('id').substring(0,2) === '1z'){       
                cell_height_1($(this).attr('id'));
            }
//          Pr?ft, ob die Funktion f?r 1,5 zeilige Zellen geklickt wurde            
            if($(this).attr('id').substring(0,2) === '5z'){       
                cell_height_1_5($(this).attr('id'));
            }
//          Pr?ft, ob die Funktion f?r 2 zeilige zellen geklickt wurde            
            if($(this).attr('id').substring(0,2) === '2z'){
                cell_height_2($(this).attr('id'));
            }
//          Pr?ft, ob die Funktion f?rs 'vertical-align = top' geklickt wurde            
            if($(this).attr('id').substring(0,2) === 'ob'){
                text_top($(this).attr('id'));
            }
//          Pr?ft, ob die Funktion f?rs 'vertical-align = middle' geklickt wurde            
            if($(this).attr('id').substring(0,2) === 'ce'){
                text_center($(this).attr('id'));
            }
//          Pr?ft, ob die Funktion f?rs 'vertical-align = bottom' geklickt wurde                        
            if($(this).attr('id').substring(0,2) === 'un'){
                text_unten($(this).attr('id'));
            }
        }
    });
}

 