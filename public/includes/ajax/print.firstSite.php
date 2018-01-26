<?php
$arrMonths = array(
    1  => 'Januar',
    2  => 'Februar',
    3  => 'März',
    4  => 'April',
    5  => 'Mai',
    6  => 'Juni',
    7  => 'Juli',
    8  => 'August',
    9  => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Dezember',
);

$strMonth = $arrMonths[date('n')];
if($arrPost['deckPage'] == 1)
{
    $strStart0 = '<br>
    <div class="firstSite">
        <div class="titleSite">
            <img style="width: 230px; display: block; margin-left: 307px;" src="../../img/pdf-stempel.png"/><br><br>
            <span class="titleText">Bericht über die Qualität der Stellenanzeige<br>
            '.trim($arrPost['title']).'<span>
            <p style="font-size: 14x;">Erstellt am '.date("j.")." $strMonth ".date("Y").'</p><br>
            <p class="companyText">
            '.trim($arrPost['company']).'<br>
            '.trim($arrPost['contact']).'
            </p>
            <br>
        </div>
    </div>';

    $objPDF->WriteHTML($strStart0, 2);
    $objPDF->AddPage();
}

$strStart1 = '<br>
<br>
<br>
<div class="firstSite">
    <h1 style="color: #F00;">Überprüfung der Textqualität Ihrer Stellenanzeige</h1>

    <div>
        <p>Der Pulitzer-Preis ist bei Journalisten und Autoren ebenso begehrt, wie der Oscar in der Filmindustrie. Der Stifter und Namensgeber des Preises war der Journalist, Zeitungsverleger und Herausgeber Joseph Pulitzer. Er bringt für uns die Regeln für gute Texte auf den Punkt:</p>
        <br>
        <p>
            <strong>Schreibe kurz und sie werden es lesen,<br>
                schreibe klar und sie werden es verstehen,<br />
                schreibe anschaulich und sie werden es behalten.</strong>
        </p>
        <br>
        <p>Die nachfolgenden Themenfelder helfen Ihnen, den Rat von Joseph Pulitzer umzusetzen. Der „Text-Optimierer“ kann einen erfahrenen Texter nicht ersetzen. Das Werkzeug unterstützt Texter und leichtert die Arbeit. Die Markierungen zeigen, wo der Text optimiert werden könnte. Nicht mehr, aber auch nicht weniger. Markierungen verstehen Sie bitte nur als Hinweis. Es geht nicht um richtig oder falsch. Jeder Texter entscheidet, wie er mit den Informationen umgeht. Es ist durchaus möglich, dass markierte Formulierungen in bestimmten Branchen oder Berufen üblich sind. Regeln dürfen auch bewusst gebrochen werden. Es bleibt die Aufgabe des Texters, einen informativen und ansprechenden Text zu schreiben.</p>
    </div>

    <h3 class="accordion-title" style="background: #9ce3ff; border: 1px solid #444;">Floskeln</h3>
    <div class="accordion-content">Floskeln sind Worthülsen, Blabla, Gefasel, Geschwafel, Binsenweisheiten, Selbstverständlichkeiten, Umschweife, Gemeinplätze, Banalitäten und somit nur überflüssiges Geschwätz. Verzichten Sie auf langweilige, kraftlose, inhaltsleere und nichtssagende Wörter! Alle Wörter, die wir in Ihrem Text als Floskeln einstufen, werden über den Button „Floskeln“ farblich markiert. Falls Sie das Ergebnis erschreckt, sollten Sie sich beispielhaft mit den Begriffen „flexibel“ und „belastbar“ genau beschäftigen. Umfangreiche Praxistests zeigen, dass Bewerber pro Wort auf mehr als ein Dutzend unterschiedlicher Interpretationen kommen. Die Bandbreite möglicher Auslegungen überrascht die meisten Personaler. Dieses Erlebnis kann helfen, konkreter zu formulieren.</div>
<h3 class="accordion-title" style="background: #c7d66f; border: 1px solid #444;">Negative Wörter</h3>
    <div class="accordion-content">Beschreiben Sie Sachverhalte positiv. Sorgen Sie für gute Gefühle bei den Lesern. Verneinungen (nicht, keine, ohne, un...) und Wörter mit negativen Assoziationen führen zu Missklängen. Wir können nicht an etwas nicht denken. Sie kennen vermutlich das Beispiel mit dem rosa Elefanten. Nach der Aufforderung, nicht an einen rosa Elefanten zu denken, sieht praktisch jeder einen rosa Elefanten vor seinem geistigen Auge. Formulieren sie positiv.</div>

    <h3 class="accordion-title" style="background: #ffb979; border: 1px solid #444;">Textverständnis</h3>
    <div class="accordion-content">Ob Leser einen Text verstehen, hängt vom Text, der Bildung des Lesers und weiteren Faktoren abhängig. Vergessen Sie nicht, dass Stellenausschreibungen Werbetexte sind. Wer liest schon Werbetexte mit voller Konzentration? Verwenden Sie daher einfache Worte. Vermeiden Sie Abkürzungen, Fachbegriffe und Fremdwörter wo immer es geht. Als Regel gilt die Empfehlung von Arthur Schopenhauer: <b>„Man gebrauche gewöhnliche Worte und sage ungewöhnliche Dinge.“</b>
        <br><br>
        Als Basis für die Überprüfung des Textverständnisses sind in der Datenbank des „Text-Optimierers“ fast eine Million deutsche Wörter gespeichert. Jedes Wort, das nicht in diesem Grundwortschatz enthalten ist, wird markiert. Prüfen Sie kritisch, ob es ein bedeutungsgleiches oder ähnliches Wort gibt, das leichter verstanden wird.</div>

    ';

$objPDF->WriteHTML($strStart1, 2);
$objPDF->AddPage();

$strStart2 = '<br><br>
    <h3 class="accordion-title" style="background: #ff8c87; border: 1px solid #444;">Anglizismen</h3>
    <div class="accordion-content">Es gibt verschiedene Formen von Anglizismen. Anglizismen, die einem Lehnwort gleich kommen, jedoch nicht phonetisch und grammatisch der deutschen Sprache angepasst sind (z.B. Sport, fair); Anglizismen, die neue Sachverhalte bezeichnen, für die keine deutsche Entsprechung existiert (z.B. E-Mail, Airbag) und Anglizismen die neben existierenden, leicht verständlichen deutschen Bezeichnungen auftreten. Während die ersten beiden Formen kaum ersetzt werden können ohne Verwirrung zu schaffen, erschweren Anglizismen der letzten Form das Textverständnis unnötig. Solche Anglizismen verdrängen oft gute und aussagekräftige deutsche Wörter. Auch international tätige Unternehmen tun gut daran, Bewerber in der Landessprache anzusprechen.</div>
    <h3 class="accordion-title" style="background: #8ffce6; border: 1px solid #444;">Nominalstil</h3>
    <div class="accordion-content">Als Nominalstil bezeichnet man Satzkonstruktionen mit übermäßig vielen Substantiven und Substantivierungen. Der Nominalstil wird hauptsächlich in der Behörden- und Wissenschaftssprache verwendet. Er wirkt schwerfällig und distanziert. Im Gegensatz dazu wirken Verben anschaulich und lebendig. Kein Wunder, denn auch beim Sprechen verwenden wir hauptsächlich den Verbalstil. Der Verbalstil wirkt aktiver und emotionaler. Substantivierungen lassen sich am besten an ihren Endungen (Suffixen) -keit, -heit, -mut, -nis, -schaft, -ung, -tum, -ling, -sal, -tion, -ive, -anz, -tät erkennen. Die markierten Substantivierungen sollten Sie in den Verbalstil ändern.</div>

    <h3 class="accordion-title" style="background: #f8deca; border: 1px solid #444;">Aktiv-Passiv</h3>
    <div class="accordion-content">Das Passiv bezeichnet man auch als die Leideform eines Verbs. Es leiden auch die Leser. Wenn Sie ein treffendes Verb gefunden haben, sollten Sie es nicht in eine Passivkonstruktion pressen. Passivsätze machen jeden Text hölzern und langweilig. Beispiel gefällig?<br>
        Passiv: „Die Ampel wurde von dem Autofahrer übersehen.“<br>
        Aktiv: „Der Autofahrer übersah die Ampel.“<br>
        Passiv: „Das Problem kann von uns gelöst werden“.<br>
        Aktiv: „Wir können das Problem lösen" oder noch besser "Wir lösen das Problem“.<br>
        Die aktive Form eines Verbs klingt nicht nur besser, sie ist auch kürzer.<br>
    </div>

    <h3 class="accordion-title" style="background: #cfc0ff; border: 1px solid #444;">Wiederholungen</h3>
    <div class="accordion-content">Wiederholungen können Stilmittel sein. Beispiele kennen Sie aus Reimen, aus Refrains und natürlich aus der Werbung. Die VW-Werbung "Er läuft und läuft und läuft ..." brannte die Langlebigkeit des Käfers über Jahrzehnte in die Hirne der Autofahrer. In Stellenanzeigen langweilen Wiederholungen praktisch immer. Abwechslung ist gefragt. Das Zauberwort heißt Synonyme. Ausdrücke, die den gleichen oder einen sehr ähnlichen Bedeutungsumfang haben, helfen uns aus der Patsche. Gut gewählte Synonyme machen Texte lebendiger und die Aussagen präziser. Statt zu arbeiten kann jemand aufgehen, sich befassen, beschäftigen, hantieren, etwas herstellen, montieren, nachdenken, etwas schaffen, tüfteln, verändern, wirken oder handeln.<br><br>Der „Text-Optimierer“ markiert für Sie Wiederholungen. In Synonym-Wörterbuchern oder Datenbanken finden Sie schnell andere Begriffe. Gerne empfehlen wir  <a href="http://www.wortschatz.uni-leipzig.de" target="_blank">www.wortschatz.uni-leipzig.de</a>, <a href="http://www.woerterbuch.info" target="_blank">www.woerterbuch.info</a>, <a href="http://www.duden.de/hilfe/synonyme" target="_blank">www.duden.de/hilfe/synonyme</a> und <a href="http://www.openthesaurus.de" target="_blank">www.openthesaurus.de</a></div>';

$objPDF->WriteHTML($strStart2, 2);
$objPDF->AddPage();

$strStart3 = '<br><br>
<h3 class="accordion-title" style="background: #fffacc; border: 1px solid #444;">Wort- und Satzlänge</h3>
    <div class="accordion-content">Wortwahl und Schreibstil sind wichtig. Daneben entscheiden Wort- und Satzlänge, ob Leser den Inhalt sofort verstehen. <b>"Schreibe kurz!"</b>, rät uns Joseph Pulitzer. Die Presseagentur dpa. geht als Obergrenze für "optimale" Verständlichkeit von 9 Wörtern pro Satz aus. Ein Satz in der BILD-Zeitung besteht durchschnittlich aus nur 12 Wörtern. Der „Text-Optimierer“ markiert alle Sätze mit mehr als 15 Wörtern. Mehrere Sprachwissenschaftler halten dies für die Obergrenze leicht verständlicher Texte.<br><br>Verständlich schreiben heißt immer auch Wörter kürzen. Je kürzer ein Wort ist, desto intensiver und klarer ist es meist. "Liebe", "Trauer", "Hass", "Stolz", "Angst" - viele starke Wörter haben nur eine Silbe. Und sind darum viel kraftvoller und leichter verständlich als Umschreibungen wie "emotionale Befindlichkeit". Der „Text-Optimierer“ markiert Wörter mit mehr als 14 Buchstaben.
    </div>
</div>';

$objPDF->WriteHTML($strStart3, 2);
$objPDF->AddPage();
?>