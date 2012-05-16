<h1>Redovisning</h1>

<h2>Kmom01: En boilerplate</h2>
<p>Kursmoment 1 tycker jag inte var jättesvårt. Dock så använde jag ju MOS css/html och jag hoppas det är okej.
Jag utvecklar allt på min egna laptop som kör Windows 7 med WAMP, använder FileZilla för att skicka filer till
studentservern och Notepad++ för att skriva kod.</p>

<p>
Boilerplate tycker jag verkar vara bra att använda då det är kod som är återanvändbar, allt som går att använda
igen i programmeringssammanhang är bra. Jag har ju självklart kollat igenom det mesta som kom med HTML5Boilerplate
även om jag inte använder det. Det verkar finnas mycket kul saker där. Jag tyckte även det var fint att lägga in
lite bilder på meme's lite här och var på sidan.
</p>

<p>
I och med att jag inte är hyperbra med design i allmänhet så tänkte jag att jag utgick ifrån din istället
och ändrade ochdonade lite med texter osv. Det fanns även lite småproblem med .htaccess-filen när man la
över den till studentservern då den tog bort "www" när man gick in på sidan vilket resulterade i att man
inte kom in på sidan alls. Det var bara att kommentera bort ett par rader från .htaccess så löstes problemet.
Utöver det så fanns det inte många problem, möjligtvis att jag hade glömt bort hur man ansluter till
studentkontot via ftp, men det gick rätt snabbt att leta upp.
</p>

<img src="<?=CNocturnal::Instance()->request->GetBaseUrl()?>site/data/presentation/close_enough.png" alt="" style="width: 240px; height: 177px;" />

<h2>Kmom02: Grunden till ett MVC ramverk</h2>

<p>
Jag har döpt mitt ramverk till Nocturnal, för att det mesta arbetet sker på natten. Namngivningen brukar jag
alltid ha svårt för så jag brukar vänta med att ge ett namn och döper istället alla mina stora projekt till
Nocturnal(endast ett per område), och ger dem istället namn efteråt när de är klara. Fast det här projektet
får nog heta Nocturnal på heltid.
</p>

<p>
Jag läste <a target="_blank" href="http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/">Anat Garg's</a>
tutorial om hur man gör ett MVC-ramverk även om mitt är nästan identiskt med Lydia i klass-struktur och liknande. Dock så är
koden inte likadan som Lydia's utan ändrad på flertalet ställen. Jag använde inte något av tutorialen's material då jag tyckte
att det verkade vara lite väl simpelt på något sätt och jag gillade inte trädstrukturen. Lydia's trädstruktur passar mer hur jag
tycker att det ska vara. I övrigt var tutorialen välskriven i min mening och bra att läsa för att få en överblick av ett MVC.
</p>

<p>
Jag ville ha bredare träd istället för djupare då det endast skapar förvirring om man har en mapp i en mapp i en mapp t.ex. på klasser som
ärver av en viss typ eller dyl. Istället så har jag mapparna "site" och "src" där src är själva kärnan för MVC:et och "site" kommer att vara
för Controller-klasserna. 
</p>

<p>
Jag använder ReflectionClass precis likadant som Lydia använder det då det kändes onödigt att återuppfinna hjulet för just den här delen. Den
kontrollerar att det är en giltig Controller-klass och kör den angivna metoden om den existerar.
</p>

<p>
<a target="_blank" href="https://github.com/Macebox/MVC">GIT-länk</a>
</p>

<h2>Kmom03: En gästbok i MVC-ramverket</h2>

<p>
För CodeIgniter så använde jag Rickards tutorial. Det gick smidigt och enkelt att göra en gästbok i CodeIgniter och det var kul att testa
på ett fullt fungerande ramverk så att man kunde få en överblick av vad som kan komma att krävas i sitt egna. Däremot så skrapade jag
förmodligen bara på ytan av CodeIgniter då jag kan gissa på att det finns rätt mycket mer funktionalitet i det.
</p>

<p>
Det första jag gjorde när jag skulle skapa gästboken var att bygga om lite i systemet så att den verkligen läste in klasser från
site/src först bl.a. där alla Controller-klasser ska ligga. Sedan när jag hade byggt klart allt enligt Lydias tutorial så valde
jag att skapa en ny mapp direkt under root som skulle heta model där alla modelklasser ska finnas. Detta för att strukturera upp det
ytterligare så att inte alla klasser ligger i samma mapp och så slipper jag ha flera lager av mappar. Det känns nödvändigt att gå via
Lydias tutorials i stort sett för att inte missa något som kan komma att behövas längre fram, även om jag använde ett annat system för
databashantering. Jag la även till en rad som hanterade "vem" som skrev en viss post(även om detta inte kontrolleras). Jag fixade även så att
exceptions skrevs ut i debuggen om den var aktiverad.
</p>

<p>
Jag tyckte att databashanteringen skulle vara smidig och gjorde därför en hanterare för alla databasklasser som heter CDatabase. I samma
mapp ligger alla olika klasser som ärver av ett interface IDBDriver. I mitt fall använder jag CMysqli som enda klass just nu, men
det finns möjlighet för att bygga ut så att man lägger till en klass CSQLLite och ändrar driver i config.php samt lägger till lite annan information
som kan behövas. Detta gör att det ser ut att fungera likadant att ansluta via CMysqli som CSQLLite eller vad man nu vill använda. <br />
Jag la även till lite extra som t.ex. att om man vill hämta all information från tabellen "posts" så skriver man bara
$this->database->Get('posts');(från alla som ärver av CObject) men vill man bara ha titel så skriver man $this->database->Get('posts', array('title'));
vill man ytterligare säga att det bara ska vara från en viss avsändare så skickar man in $this->database->Get('posts','',array('author'=>'Mace')); <br />
På liknande sätt fungerar de andra metoderna Insert() och Delete(). Jag funderar på att lägga till ytterligare en metod kallad t.ex. Query();
där man kan göra en förfrågan hur som helst. Det viktigaste just nu är dock att om en query körs via dessa så körs en mysql_real_escape_string på alla variabler innan.
</p>
<p>
Att tillägga till min databashanterare är att man skickar in arrayer av data så att det kan skrivas om ordentligt. T.ex. "Get('posts',array('id', 'title'),
array('author'=>'Mace'));" genererar följande förfrågan: "SELECT id, title FROM posts WHERE author='Mace'".
</p>

<p>
Jag valde att strukturera som följande: <br />
Modeller i mappen /models/<br />
Views i mappen /views/<br />
Controllers i mappen /site/src/<br /> <br />

Views-data(så som bilder etc.) i mappen /data/ <br />

https://github.com/Macebox/MVC/tree/v0.2.0

</p>

<img src="<?=CNocturnal::Instance()->request->GetBaseUrl()?>site/data/presentation/fuck_yeah.png" alt="" style="width: 229px; height: 210px;" />