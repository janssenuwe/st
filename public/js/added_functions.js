

        var float_ts = new Date().getTime();
    //  Variable, die bestimmt, ob eine Tabelle eingef?gt wird oder nicht    
        var appendTable;
    //  Variable, die bei jeder ?nderung in den Editoren, f?r jeden Editor einen pers?nlichen Stack anlegt    
        var undoStack = new Array();
    //  Zustand vor dem Bearbeiten des Inhalts des Editors
        var before;
    //  Variable, die nach jedem Einf?gen einer Tabelle ein Div anlegt    
        var tabelle = 1;
        
        
    $(document).ready(function() {   
//        $('iframe').contents().find('#' + txId).find('[data-toggle="popover"]').popover({ html: true,
//                                                trigger:  "click"
//                                             }); //Wird durch einen Klick ausgeloest
    });    

    

    function initButtonEvents(txId){
    
    if(! (navigator.userAgent.toLowerCase().indexOf('TRIDENT'.toLowerCase())>-1 || navigator.userAgent.toLowerCase().indexOf('MSIE'.toLowerCase())>-1) ){
        $('iframe').contents().find('#' + txId).find("[data-toggle='popover']").popover({ html: true,
                                                                                          trigger:  "click"
                                                                                        }); //Wird durch einen Klick ausgeloest
    }
    $('#' +  txId + ' button').click(function(){
        if(!($(this).children('i').attr('class') === 'mce-ico mce-i-undo' || $(this).children('i').attr('class') === 'mce-ico mce-i-redo')){
            $('iframe').contents().find('#' + txId).trigger('change');
        }
    })    
    
    $('#' + txId + ' iframe').contents().find('#' + txId).on('click', function(){

        $('.popover.fade.right.in').addClass(txId + ' text_check');
        
        $('.popover.fade.right.in.text_check.' + txId).on('mouseleave', function(){
            $('#' + txId + ' iframe').contents().find('#' + txId).find('.clicked').removeClass('clicked');
            $(this).remove();
        })
    });
    
    /**Beim Klick auf einen Ersatzbegriff, werden alle markierten Woerter durch diesen Begriff ausgetauscht und die Markierung wird aufgehoben
     * 
     */  
    $(document).on('click', '.popover-content p', function(evt){
        
        if($('.popover-content').html() !== undefined){
            var pop_content_in_lower_case = $('.popover-content').html().replace(/<P>/g, '<p>');
            pop_content_in_lower_case = pop_content_in_lower_case.replace(/<\/P>/g, '</p>');
            pop_content_in_lower_case = pop_content_in_lower_case.replace(/\n|\r|(\n\r)/g, '');
            var span = $('iframe').contents().find('#' + txId).find("span[data-content='"+pop_content_in_lower_case+"']")[0];
        
        //  Ruft die Methode zum Zaehlen der Markierungen des gedrueckten Popovers        
            var spanLength = checkForSpans(span, $('iframe').contents().find('#' + txId));
        //  Zaehlt alle Spans innerhalb des Editors        
            var alleSpanLength = $('iframe').contents().find('#' + txId).find('span').length;

        //  Falls keine Spans vorhanden sind, werden alle Funktionen im Text-Check wieder aktiviert        
            if(alleSpanLength === 1){
                $('.text_check.'+txId+' .mce-disabled').removeClass('mce-disabled');
            }
        //  Falls keine Markierung des gedrueckten Popovers im Editor ist, wird die dazugehoerige Funktion im Text-Check wieder aktiviert        
            if (spanLength === 1){
        //      Floskel            
                if(span.className === 'highlight_blue has_ersatz clicked')
                    $('#Fl_parent_' + txId).removeClass('mce-disabled');
        //      Textverstaendnis            
                else if(span.className === 'highlight_orange has_ersatz clicked')
                    $('#Te_' + txId).removeClass('mce-disabled');
        //      Anglizismen            
                else if(span.className === 'highlight_red has_ersatz clicked')
                    $('#An_' + txId).removeClass('mce-disabled');
            }
        //  Ersetzt die Markierung durch das Wort im Popover
            $('iframe').contents().find('#' + txId).find('.clicked').replaceWith($(this).html());
        //  Entfernt die Klasse clicked von der Markierung, damit beim naechsten Klick wieder eine eindeutige Identifizierung passieren kann    
            $('iframe').contents().find('#' + txId).find('.clicked').removeClass('clicked');
        //  Blendet das Popover aus    
            $("div[class*='popover fade right in']").fadeOut();
        //  Loescht das Popover, damit es beim naechsten Klick wieder neu erstellt werden kann    
            $("div[class*='popover fade right in']").remove();
        }
    });
    
    $('iframe').contents().find('#' + txId).on('click', "span[data-toggle='popover']", function(e){
        var offset = $(this).offset();
        var left = e.pageX;
        var top = e.pageY;
        var theHeight = $('.popover').height();
        $('.popover').css('left', (left+525) + 'px');
        $('.popover').css('top', (top+200) + 'px');
    });
    
    
    /**Beim Klick einer Markierung mit dem Attribut eines Popovers, wird die Klasse 'clicked' hinzugefuegt, um es eindeutig zu identifizieren
     * @param e 
     */  
    $('iframe').contents().find('#' + txId).on('click', 'span[data-toggle="popover"]', function(e){
        
    //  Falls der benutzte Browser Internet Explorer oder Firefox sind, muss man e.target verwenden
        if(navigator.userAgent.toLowerCase().indexOf('firefox'.toLowerCase())>-1 || navigator.userAgent.toLowerCase().indexOf('MSIE'.toLowerCase())>-1 || navigator.userAgent.toLowerCase().indexOf('TRIDENT'.toLowerCase())>-1){
            e.target.className = e.target.className + ' clicked';
        }
    //  Bei Chrome benutzt man e.toElement    
        else{
            e.toElement.className = e.toElement.className + ' clicked';
        }

    }); 
    

        /**Bei einem Klick auf den Button fuer Tabellenfunktionen, wird der DropDown mit den verfuegbaren Funktionen eingeblendet
     * 
     */  
    $('.resize').click(function(){
    //  Bestimmt die Klasse des Buttons der gedrueckt wurde, um das dazugehoerige DropDown einzublenden     
        var btn_editor_class = $(this).attr('class').split(' ')[$(this).attr('class').split(' ').length - 1];
    });
    
    /**Wenn die Maus den DropDown der Tabellenfunktionen verlaesst, wird der DropDown ausgeblendet
     * 
     */ 
    $('.resize_table').mouseleave(function(){
        $(this).fadeOut();
        $(this).parent().removeAttr('disabled');
        $(this).parent().removeClass('open');
    });

    /**Bei jedem Tastendruck wird eine Aenderung des Editors registriert 
     * und die change Funktion des Editors wird aufgerufen
     * 
     */ 
    $('iframe').contents().find('#' + txId).on('focus', function() {
    //  Zustand vorher    
        before = $(this).html();
        }).on('blur keyup paste', function(event) {
     //  Vergleich der 2 Zustaende und aufrufen des Change, bei bestimmten Zeichen                  
        if ((before !== $(this).html() && event.keyCode === 32) || // Space
            (before !== $(this).html() && event.keyCode === 13) || // Enter  
            (before !== $(this).html() && event.keyCode === 8) ||  // Backspace
            (before !== $(this).html() && event.keyCode === 190)   // Period (Punkt)  
            ){ 
            $('iframe').contents().find('#' + txId).trigger('change'); 
        }
    });

    /**Bei jeder Aenderung im Editor wird der Inhalt in der Variable "undoItem" gespeichert
     * und auf den Stack gelegt 
     */  
    $('iframe').contents().find('#' + txId).on('change', function() {
    //  Pr?ft, ob schon ein Stack f?r den Editor existiert    
        if (undoStack[txId] === undefined){
    //      Erstellt ein Objekt zum speichern der Aenderungen innerhalb des Editors        
            undoStack[txId] = new Object();
    //      Speichert bei jeder Aenderung den Text des Editors in den Stack        
            undoStack[txId]['undoStack'] = [{
                       value: 0,
                       selectionStart: 0,
                       selectionEnd: 0
                   }];
    //      Speichert bei jeder Aenderung im Stack die Position im Stack           
            undoStack[txId]['undoPosition'] = 0;
        }

    //  Variable zum Speichern des Textes im Editor
        var undoItem = {
                value: $('iframe').contents().find('#' + txId).get(0).innerHTML,
                selectionStart: $('iframe').contents().find('#' + txId).get(0).selectionStart,
                selectionEnd: $('iframe').contents().find('#' + txId).get(0).selectionEnd
            };
    //  Zaehlt die Position im Stack um 1 hoch 
        undoStack[txId]['undoStack'].length = ++undoStack[txId]['undoPosition'];
    //  Legt die Aktuelle Aenderung auf den Stack    
        undoStack[txId]['undoStack'].push(undoItem);


    });

$('iframe').contents().find('#' + txId).on('paste', function(){
    setTimeout(function(){
//          Ersetzt unsichtbare Zeilenspruenge durch ein Leerzeichen            
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/\n|\r|(\n\r)/g, ' '));
//          Ersetzt doppelte Leerzeichen durch einfache            
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/  /g, ' '));
//          Ersetzt '&nbsp;' durch ein Leerzeichen
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/&nbsp\;/g, ' '));
//          Ersetzt '<br>' durch ein Leerzeichen 
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/<[\/]?br>/g, ' '));
//          Ersetzt ein unsichtbares Leerzeichen durch ein normales Leerzeichen 
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/&shy\;/g, ' '));
//          Ersetzt ein unsichtbares Leerzeichen durch ein normales Leerzeichen
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/&#160\;/g, ' '));
//          Ersetzt ein HTML-& durch ein normales &
            $('iframe').contents().find('#' + txId).html($('iframe').contents().find('#' + txId).get(0).innerHTML.replace(/&amp\;/g, '&'));
            
//          Loest eine Veraenderung am Editor aus, damit durch Undo und Redo zu dem Punkt gesprungen werden kann            
           $('iframe').contents().find('#' + txId).trigger('change');   
        }, 100);
});

    
    /** Registriert einen Tastendruck
     * 
     */  
    $('iframe').contents().find('#' + txId).on('keydown', function(e){


    //  Prueft, ob die Eingabe eine Pfeiltaste ist, damit bei Pfeiltasten die Markierungen nicht entfernt werden
        if(e.keyCode < 37 || e.keyCode > 40){
        //  Prueft, ob die Eingabe in einem Span Element erfolgt ist
            if( !(navigator.userAgent.toLowerCase().indexOf('MSIE'.toLowerCase())>-1) ){
                if($('#' + txId + ' iframe').get(0).contentDocument.getSelection().anchorNode.parentNode.tagName === 'SPAN'){

            //      Wenn das Element ein SpanElement ist, wird die Klasse zur Markierung entfernt
                    $('#' + txId + ' iframe').get(0).contentDocument.getSelection().anchorNode.parentNode.removeAttribute('class');
            //      Wei?t das Elternelement der Markierung der Variable zu                
                    var node_iterator = $('#' + txId + ' iframe').get(0).contentDocument.getSelection().anchorNode.parentNode;

            //      Passt die Funktionalit?t der Listenelemente den Gegebenheiten an        
                    textCheckListeAnpassen(txId);
                }
            }
            else{
                if($('#' + txId + ' iframe').get(0).contentWindow.document.selection.createRange(0).parentElement().nodeName === 'SPAN'){

            //      Wenn das Element ein SpanElement ist, wird die Klasse zur Markierung entfernt
                    $($('#' + txId + ' iframe').get(0).contentWindow.document.selection.createRange(0).parentElement()).attr('class', '');

                    
            //      Wei?t der Variable die Klasse des Elternelements der Markierung zu        
            //      Passt die Funktionalit?t der Listenelemente den Gegebenheiten an        
                    textCheckListeAnpassen(txId);
                }
            }
        }
    });
    
    }

    
    /**F?hrt die Markierung im Editor aus
     * 
     * @param HTML-Element editor - Editor, in dem die TextCheck Funktion ausgefuehrt wird
     * @param string db_action - Bestimmt, nach welchem Kriterium markiert werden soll
     * @param string element_id - Id des gedrueckten Elements, das die Funktion aufruft
     */  
    function testDBcontainsString(editor, db_action, element_id){
    //  Verbindung mit der Datenbank
    
        $.ajax({
            url: "/check/public/includes/ajax/editor.php",
            method: 'post',
            beforeSend: function(jqXHR) {
                jqXHR.overrideMimeType('text/html;charset=iso-8859-1');

                $('#' + element_id).children().first().removeClass('hide_spinner');
                rotatespinner($('#' + element_id).children().first());
            },
            data: {
    //          Sucht die passende Funktion in der PHP-Datei            
                action: db_action,
    //          Der zu pr?fende String            
                text: editor.html()
            },
            async: true,
            success : function (data) {
    //          Tauscht den Inhalt des Editors mit dem neuerstellten String aus 
                editor.html(data);
//    //          Gibt jedem markierten String die M?glichkeit ein Popover zu erzeugen            
//               $('[data-toggle="popover"]').popover({ html: true,
//                                                       trigger: "click"});
                                                   
               
                $('#' + element_id).children().first().remove();
                $('#' + element_id).children().first().before("<i class='mce-ico mce-i-fullpage hide_spinner'></i>")
//              Z?hlt, die Anzahl der Markierungen im Editor 
  
                if(db_action.charAt(db_action.length-1) === 'E'){
                    
                    var anzahl_spans = getAnzahlSpans(db_action.substring(0, db_action.length - 2), editor);
                }
                else if(db_action[db_action.length - 1] === '1'){
                    var anzahl_spans = getAnzahlSpans(db_action.substring(0, db_action.length - 4), editor);
                }
                else{
                    var anzahl_spans = getAnzahlSpans(db_action, editor);
                }
                
//    //          Falls Markierungen vorhanden sind wird bestimmten Listenelement-Gruppen ihre Funktion genommen           
                if(anzahl_spans > 0){
                    if(db_action.charAt(db_action.length-1) === 'E'){
                        disableLiElements(db_action.substring(0, db_action.length - 2), element_id);
                    }
                    else if(db_action[db_action.length -1] === '1'){
                        disableLiElements(db_action.substring(0, db_action.length - 4), element_id);
                    }
                    else{
                        disableLiElements(db_action, element_id);  
                    }
//
//    //              Loest eine Veraenderung am Editor aus, damit durch Undo und Redo zu dem Punkt gesprungen werden kann            
                    editor.trigger('change');
                }
                             
                $(".mce-floatpanel:not([style*='display: none'])").css('display', 'none');
                
                    $('iframe').contents().find('#' + editor.attr('id')).find("[data-toggle='popover']").popover({ html: true,
                                                                                                                   trigger:  'click'
                                                                                                                 }); //Wird durch einen Klick ausgeloest
                
                $('.mce-active').removeClass('mce-active');
                $('body').trigger('click');
                
                editor.trigger('click');
                
                console.log('Zeit: '+ ((new Date().getTime() - float_ts)/1000));
            }       
        });
    }

    /**Gibt die Anzahl der Spans f?r die jeweiligen Funtionen zur?ck
     * 
     * @param string db_action - Bestimmt, welche Art von Markierung gezaehlt werden soll
     * @param HTML-Element editor - Editor in dem nach Markierungen gesucht wird
     * @returns Int - Anzahl der gefundenen Markierungen
     */  
    function getAnzahlSpans(db_action, editor){
        
    //  Anzahl Spans f?r die ?berpr?fung der Satzl?nge    
        if(db_action === 'checkSatzlaenge')
            return editor.find('.highlight_yellow').length;
    //  Anzahl Spans f?r die ?berpr?fung der Wortl?nge    
        if(db_action === 'checkWortlaenge')
            return editor.find('.highlight_pink').length;
    //  Anzahl Spans f?r die ?berpr?fung der Floskeln     
        if(db_action === 'checkFloskel')
            return editor.find('.highlight_blue').length;
    //  Anzahl Spans f?r die ?berpr?fung der Textverst?ndlichkeit    
        if(db_action === 'checkTextverstaendnis')
            return editor.find('.highlight_orange').length;
    //  Anzahl Spans f?r die ?berpr?fung der Anglizismen    
        if(db_action === 'checkAnglizismen')
            return editor.find('.highlight_red').length;
    //  Anzahl Spans f?r die ?berpr?fung des Nominalstils    
        if(db_action === 'checkNominalstil')
            return editor.find('.highlight_lightblue').length;
    //  Anzahl Spans f?r die ?berpr?fung der Passivformulierungen    
        if(db_action === 'checkAktivPassiv')
            return editor.find('.highlight_lightgreen').length;
        if(db_action === 'checkWiederholung')
            return editor.find('.highlight_plumbpink').length;
    }

    /**Nimmt den richtigen Button Kombinationen ihre Funktion
     * 
     * @param string db_action - Funktion, die bet?tigt wurde und bestimmte Listenelemente deaktiviert 
     * @param string element_id - Id des gedrueckten Listenelements
     *  
     */  
    function disableLiElements(db_action, element_id){
    //  Bestimmt die Klasse zu der das gedr?ckte Listenelement geh?rt, um den richtigen Editor anzusprechen
        var element_class = element_id.substring(10);
    //  Wenn die Satzl?nge gepr?ft wird, werden alle anderen Listenelemente deaktievert
        if(db_action === 'checkSatzlaenge'){
            $('.group_1.' + element_class).addClass('mce-disabled');
        }
    //  Wenn der Aktiv/Passiv/Nominalstil Block abgefragt wird 
    //  werden Listenelemente der Gruppe 3 geblockt
        if(db_action === 'checkAktivPassiv' || db_action === 'checkNominalstil'){
            $('.group_3.' + element_class).addClass('mce-disabled');
            $('#' + element_id).addClass('mce-disabled');
        }
    //  Falls eine andere Funktion gew?hlt wurde, werden nur diese selbst,    
    //  Satzl?nge und Passiv-Aktiv Funktionen deaktiviert
        if(db_action === 'checkWortlaenge' || db_action === 'checkFloskel' || db_action === 'checkTextverstaendnis' || db_action === 'checkAnglizismen'){
            $('.group_2.' + element_class).addClass('mce-disabled');
            $('#' + element_id).addClass('mce-disabled');
        }
        if(db_action === 'checkWiederholung'){
            $('.group_1.'+ element_class).addClass('mce-disabled');
            $('#' + element_id).addClass('mce-disabled');
        }

    }


    /**Frueherer Zustand wird wiederhergestellt und in den Editor geladen
     * 
     * @param array item - Enthaelt Wert, Beginn der Selektion und Ende der Selektion
     * @param HTML-Element editor - Editor an dem ein frueherer Zustand aufgerufen wird
     * @returns {undefined}
     */  
    function restoreUndoItem(item, editor, ed_id) {

    //  Wenn der Editor leer ist, wird ein leeres Zeichen eingefuegt
        if(item.value === 0)
            item.value = '';
    //  Laedt die Inhalte der uebergebenen Variable in den Editor    
        editor.get(0).innerHTML = item.value;
        editor.selectionStart = item.selectionStart;
        editor.selecttionEnd = item.selectionEnd;
    //  Passt die Listenelemente des TextChecks an (Zaehlt die Markierungen im Editor und aktiviert bzw deaktiviert bestimmte Elemente)    
        textCheckListeAnpassen(ed_id);
    }

    

    /**Wenn die letzte Aktion r?ckg?ngig gemacht oder wiederholt wird, werden die Listenelemente des TextChecks je nach Markierungen im Editor angepasst
     * 
     * @param string editor_id - Id des Editors, der benutzt wurde
     */  
    function textCheckListeAnpassen(editor_id){
    //  Schneidet den hinteren Teil der Editor-ID ab, um zu identifizieren in welchem Editor Aktionen gemacht wurden       
        var editor_class = editor_id;
    //  Prueft, ob nach dem zur?cksetzen bzw wiederholen der letzten Aktion noch Markierungen im Editor sind
    //  Wenn nicht, werden alle Listenelemente wieder aktiviert
        if($('iframe').contents().find('#' + editor_id).find("span[class^='highlight']").length === 0){
            $('.group_1.'+editor_class).removeClass('mce-disabled');
        }
        if($('iframe').contents().find('#' + editor_id).find("span[class^='highlight']").length > 0){
            $('.group_1.'+editor_class).addClass('mce-disabled');
        }
    //  Prueft, ob im Editor Markierungen der 2. Funktionsgruppe sind (Floskel, LangeWoerter, Textverstaendnis, Anglizismus)
    //  Wenn ja, werden Listenelemente der Gruppe_2 deaktiviert und die einzelnen Elemente der 2.Funktionsgruppe werden ueberprueft
        if($('iframe').contents().find('#' + editor_id).find('.highlight_blue, .highlight_pink, .highlight_orange, .highlight_red').length > 0){
            $('.group_2').addClass('mce-disabled');
    //      Wenn im Editor keine langen Woerter markiert sind, wird die Funktion 'LangeWoerter' aktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_pink').length === 0){
                $('#Wo_parent_' + editor_class).removeClass('mce-disabled');
            }
    //      Wenn im Editor lange Woerter markiert sind, wird die Funtkion 'LangeWoerter deaktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_pink').length > 0){
                $('#Wo_parent_' + editor_class).addClass('mce-disabled');
            }
    //      Wenn im Editor keine Floskeln markiert sind, wird die Funktion 'Floskeln' aktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_blue').length === 0){
                $('#Fl_parent_' + editor_class).removeClass('mce-disabled');
            }
    //      Wenn im Editor Floskeln markiert sind, wird die Funktion 'Floskeln' deaktiviert
            if($('iframe').contents().find('#' + editor_id).find('.highlight_blue').length > 0){
                $('#Fl_parent_' + editor_class).addClass('mce-disabled');
            }
    //      Wenn im Editor keine TextverstaendnisWoerter markiert sind, wird die Funktion 'Textverstaendnis' aktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_orange').length === 0){
                $('#Te_parent_' + editor_class).removeClass('mce-disabled');
            }
    //      Wenn im Editor TextverstaendnisWoerter markiert sind, wird die Funktion 'Textverstaendnis' deaktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_orange').length > 0){
                $('#Te_parent_' + editor_class).addClass('mce-disabled');
            }        
    //      Wenn im Editor keine Anglizismen markiert sind, wird die Funtkion 'Anglizismen' aktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_red').length === 0){
                $('#An_parent_' + editor_class).removeClass('mce-disabled');
            }
    //      Wenn im Editor Anglizismen markiert sind, wird die Funktion 'Anglizismen' deaktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_red').length > 0){
                $('#An_parent_' + editor_class).addClass('mce-disabled');
            }
        }
    //  Prueft, ob im Editor Markierungen der 3. Funktionsgruppe sind (Nominalstil, Aktiv-Passiv)
    //  Wenn ja, werden Listenelemente der Gruppe_3 deaktiviert und die einzelnen Elemente der 3.Funktionsgruppe werden ueberprueft
        if($('iframe').contents().find('#' + editor_id).find('.highlight_lightblue, .highlight_lightgreen').length > 0){
            $('.group_3').addClass('disabled');
    //      Wenn im Editor keine Nominalstilformulierungen markiert sind, wird die Funktion 'Nominalstil' aktiviert
            if($('iframe').contents().find('#' + editor_id).find('.highlight_lightblue').length === 0){
                $('#No_parent_' + editor_class).removeClass('mce-disabled');
            }
    //      Wenn im Editor Nominalstilformulierungen markiert sind, wird die Funktion 'Nominalstil' deaktiviert         
            if($('iframe').contents().find('#' + editor_id).find('.highlight_lightblue').length > 0){
                $('#No_parent_' + editor_class).addClass('mce-disabled');
            }
    //      Wenn im Editor keine Passivformulierungen markiert sind, wird die Funktion 'Aktiv-Passiv' aktiviert        
            if($('iframe').contents().find('#' + editor_id).find('.highlight_lightgreen').length === 0){
                $('#Ak_parent_' + editor_class).removeClass('mce-disabled');
            }
    //      Wenn im Editor Passivformulierungen markiert sind, wird die Funtkion 'Aktiv-Passiv' deaktiviert
            if($('iframe').contents().find('#' + editor_id).find('.highlight_lightgreen').length > 0){
                $('#Ak_parent_' + editor_class).addClass('mce-disabled');
            }
        }  
    }


    /**Fuegt an der Position des Cursors ein HTML-Element ein
     * 
     * @param string html - HTML Code, der eingefuegt wird
     * @param HTML-Element editor - Editor, in den HTML Code eingefuegt werden soll
     * @returns {undefined}
     */  
    function insertHtmlAtCursor(html, editor) {

        var sel, range, node, parent_node, inhalt;
    //  Firefox und Chrome
        if (!(navigator.userAgent.toLowerCase().indexOf('MSIE'.toLowerCase())>-1)) {

    //      Selektierter Bereich
            sel = window.getSelection();
    //      Sucht den Elternknoten, falls der Inhalt des Editors nicht leer ist
            if($('#' + editor).children()[$('#' + editor).children().length - 1].innerHTML !== ''){
                parent_node = window.getSelection().getRangeAt(0).commonAncestorContainer;
                inhalt = document.getElementById('editor_' + editor).innerHTML;
            }
    //      Wenn der Inhalt des Editors leer ist, wird der Editor als Elternknoten festgelegt        
            else
                parent_node = $('#editor_' + editor);

    //      Wenn das Elternelement der Editor ist, und keine Tabelle eingefuegt wird (appendTable === false)
    //      wird der Inhalt des Editors geleert
            if(parent_node === document.getElementById('editor_' + editor) && appendTable === false){
                parent_node.innerText = '';
                parent_node.innerHTML = '';
            }
    //      Wenn das Elternelement der Editor ist, und keine Tabelle eingefuegt wird (appendTable === false)
    //      wird der Inhalt des Editors geleert                    
            else if((parent_node.data === document.getElementById('editor_' + editor).innerText && appendTable === false)||
                    (parent_node.data === document.getElementById('editor_' + editor).innerHTML && appendTable === false)){

                if(parent_node.data !== undefined){
                    parent_node.data = '';
                }
                else{
                    html = parent_node.innerHTML;
                    parent_node.remove();
                }
            }
    //      Fuegt den String in den selektierten Bereich ein  
            if (sel.getRangeAt && sel.rangeCount) {
                range = window.getSelection().getRangeAt(0);
                node = range.createContextualFragment(html);
                range.insertNode(node);
                appendTable = false;
            }
        }

    //  Internet Explorer
        else if (navigator.userAgent.toLowerCase().indexOf('TRIDENT'.toLowerCase())>-1 || navigator.userAgent.toLowerCase().indexOf('MSIE'.toLowerCase())>-1) {

            var alter_tag = document.selection.createRange().parentElement();

            if(alter_tag !== document.getElementById('editor_' + editor)){
                alter_tag.removeNode();
            }
            document.selection.createRange().pasteHTML(html);
            appendTable = false;
        }
    }

    /**Setzt die Cursor Positition ans Ende des uebergegebenen HTML-Elements 
     * 
     * @param HTML-Element el - HTML Element, in das der Cursor platziert werden soll
     */  
    function placeCaretAtEnd(el) {
    //  Setzt den Focus auf das uebergebene Element    
        el.focus();
    //  Falls der Browser nicht Internet Explorer ist   
        if (typeof window.getSelection !== "undefined"    
            && typeof document.createRange !== "undefined") {
    //      Erstellt eine Range innerhalb der Seite    
            var range = document.createRange();
    //      Fuegt den Inhalt des uebergebenen Elements in die Range ein        
            range.selectNodeContents(el);
    //      Setzt den Fokus an das Ende der Range      
            range.collapse(false);
    //      Holt sich die Selektion des Users        
            var sel = window.getSelection();
    //      Loescht alle Ranges (es existiert nur eine)        
            sel.removeAllRanges();
    //      Fuegt die erstellte Range ein        
            sel.addRange(range);
    //  Falls der Browser Internet Explorer ist        
        } else if (typeof document.body.createTextRange !== "undefined") {
    //      Erstellt eine Range innerhalb der Seite        
            var textRange = document.body.createTextRange();
    //      Fuegt die Range an die Stelle des uebergebenen Elements ein        
            textRange.moveToElementText(el);
    //      Setzt den Fokus an das Ende der Range        
            textRange.collapse(false);
    //      Selektiert die Range        
            textRange.select();
       }
    }


    /**Loescht alle Markierungen (Spans) im Editor
     * 
     * @param string element_class - Klasse des gedrueckten Listenelements, welche den betroffenen Editor bestimmt
     * @param string element_id - Id des gedrueckten Listenelements
     */  
    function markierungenAufheben(element_class, element_id){
    //  Bestimmt die Klasse des Listenelements, um die Markierungen zum dazugehoerigen Editor zu entfernen    
        var ed_class = element_id.substring(10)+'_body';
    //  Jedes Element mit der Klasse disabled (Sind nur Listenelemente von Text Check) wird wieder aktiviert   
        $('div.text_check .mce-disabled.' + ed_class).removeClass('mce-disabled');
        console.log(ed_class);

    //  Loescht alle Span-Elemente im Editor
        $('iframe').contents().find('#' + ed_class + ' span').each(function(){
            try{
                this.outerHTML = this.innerHTML;
            } catch (err){
                console.log('ErrorMessage: ' + err.message + ',   Element: ' + this + ',   Klasse: ' + $(this).attr('class') + ',   ID: ' + $(this).attr('id') + '\n\n' + err.stack);
            }
        });
    //  Loescht alle Spans-Elemente im Editor, falls Woerter doppelt markiert wurden    
        $('iframe').contents().find('#' + ed_class + ' span').each(function(){
            try{
                this.outerHTML = this.innerHTML;
            } catch(err) {
                console.log('ErrorMessage: ' + err.message + ',   Element: ' + this + ',   Klasse: ' + $(this).attr('class') + ',   ID: ' + $(this).attr('id') + '\n\n' + err.stack);
            }
        });
        //  Loescht alle Spans-Elemente im Editor, falls Woerter dreimal markiert wurden    
        $('iframe').contents().find('#' + ed_class + ' span').each(function(){
            try{
                this.outerHTML = this.innerHTML;
            } catch(err) {
                console.log('ErrorMessage: ' + err.message + ',   Element: ' + this + ',   Klasse: ' + $(this).attr('class') + ',   ID: ' + $(this).attr('id') + '\n\n' + err.stack);
            }
        });
        //  Loescht alle Spans-Elemente im Editor, falls Woerter viermal markiert wurden    
        $('iframe').contents().find('#' + ed_class + ' span').each(function(){
            try{
                this.outerHTML = this.innerHTML;
            } catch(err) {
                console.log('ErrorMessage: ' + err.message + ',   Element: ' + this + ',   Klasse: ' + $(this).attr('class') + ',   ID: ' + $(this).attr('id') + '\n\n' + err.stack);
            }
        });
        $('iframe').contents().find('#' + ed_class).trigger('change');
        
    };

    

    /**Gibt die Anzahl der Spans zur?ck
     * 
     * @param HTML-Element span - Span-Element, das im Editor gesucht wird
     * @param HTML-Element editor - Betroffener Editor
     * @returns Int - Anzahl der gefundenen Spans
     */  
    function checkForSpans(span, editor){
        return editor.find("span[class *= '" + span.className.split(' ')[0] + "']").length;
    }

    function cell_height_1(button_id) {
        //  Gedrueckter Button, um herauszufinden zu welchem Editor der Button gehoert    
        $('iframe').contents().find('#' + button_id.substring(10) + ' table .mce-item-selected').each(function(){
            $(this).css('line-height','1.0');
            $(this).css('height','30px');
        })
    }
    
    function cell_height_1_5(button_id){
        
        
    //  Gedrueckter Button, um herauszufinden zu welchem Editor der Button gehoert    
        $('iframe').contents().find('#' + button_id.substring(10) + ' table .mce-item-selected').each(function(){
            $(this).css('line-height','1.5');
            $(this).css('height','45px');
        })

    }
    
    function cell_height_2(button_id){
 //  Gedrueckter Button, um herauszufinden zu welchem Editor der Button gehoert    
        $('iframe').contents().find('#' + button_id.substring(10) + ' table .mce-item-selected').each(function(){
            $(this).css('line-height','2.0');
            $(this).css('height','60px');
        })
    }

function text_top(button_id){
    //  Gedrueckter Button, um herauszufinden zu welchem Editor der Button gehoert    
        $('iframe').contents().find('#' + button_id.substring(10) + ' table .mce-item-selected').each(function(){
            $(this).css('vertical-align','top');
        })

}
function text_center(button_id){

//  Gedrueckter Button, um herauszufinden zu welchem Editor der Button gehoert    
    $('iframe').contents().find('#' + button_id.substring(10) + ' table .mce-item-selected').each(function(){
        $(this).css('vertical-align','middle');
    })
}
function text_unten(button_id){


    //  Gedrueckter Button, um herauszufinden zu welchem Editor der Button gehoert    
    $('iframe').contents().find('#' + button_id.substring(10) + ' table .mce-item-selected').each(function(){
        $(this).css('vertical-align','bottom');
    })


}


   /**Bei einem Klick wird der letzte bekannte Inhalt der Textarea geladen
     * 
     */  
    function undoAction(txId) {
        if(undoStack[txId] !== undefined){
        //  Pr?ft, ob die Position im Stack groesser 0 ist, dh ob man noch weiter zurueckgehen kann
            if (undoStack[txId]['undoPosition'] > 0) {
        //      Ruft die Funkion zum Aufrufen eines frueheren Zustands auf        
                restoreUndoItem( undoStack[txId]['undoStack'][--undoStack[txId]['undoPosition']], $('iframe').contents().find('#' + txId), txId);
            }
        }
    }
    
    /**Bei Klick wird einen Schritt nach vorne gesprungen und das Undo wieder aufgehoben
     * 
     */  
    function redoAction(txId) {

        if(undoStack[txId] !== undefined){
        //  Pr?ft, ob die Position im Stack groesser 0 ist, dh ob man noch weiter zurueckgehen kann    
            if (undoStack[txId]['undoPosition'] < undoStack[txId]['undoStack'].length - 1) {
        //          Ruft die Funkion zum Aufrufen eines frueheren Zustands auf
                    restoreUndoItem( undoStack[txId]['undoStack'][++undoStack[txId]['undoPosition']], $('iframe').contents().find('#' + txId));
            }
            textCheckListeAnpassen(txId);
        } 
    }
    
    
function rotatespinner(element) {
    var $elie = element, degree = 0, timer;
    rotate();
    function rotate() {

        if(navigator.userAgent.toLowerCase().indexOf('MSIE 7.0'.toLowerCase())>-1 || navigator.userAgent.toLowerCase().indexOf('MSIE 8.0'.toLowerCase())>-1){
            $elie.css({ 'filter': "progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand', M11=0.7071067811865476, M12=-0.7071067811865475, M21=0.7071067811865475, M22=0.7071067811865476)", /* IE6,IE7 */
                        '-ms-filter': "progid:DXImageTransform.Microsoft.Matrix(SizingMethod='auto expand', M11=0.7071067811865476, M12=-0.7071067811865475, M21=0.7071067811865475, M22=0.7071067811865476)" /* IE8 */
            });

            
        }

        else{
            $elie.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});  
            $elie.css({ '-moz-transform': 'rotate(' + degree + 'deg)',
                         'transform': 'rotate(' + degree + 'deg)'


            });                      
            timer = setTimeout(function() {
                --degree; rotate();
            },5);
        }
    }
    
};