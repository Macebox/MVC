<h1>Redovisning</h1>

<h2>Kmom01: En boilerplate</h2>
<p>Kursmoment 1 tycker jag inte var j�ttesv�rt. Dock s� anv�nde jag ju MOS css/html och jag hoppas det �r okej.
Jag utvecklar allt p� min egna laptop som k�r Windows 7 med WAMP, anv�nder FileZilla f�r att skicka filer till
studentservern och Notepad++ f�r att skriva kod.</p>

<p>
Boilerplate tycker jag verkar vara bra att anv�nda d� det �r kod som �r �teranv�ndbar, allt som g�r att anv�nda
igen i programmeringssammanhang �r bra. Jag har ju sj�lvklart kollat igenom det mesta som kom med HTML5Boilerplate
�ven om jag inte anv�nder det. Det verkar finnas mycket kul saker d�r. Jag tyckte �ven det var fint att l�gga in
lite bilder p� meme's lite h�r och var p� sidan.
</p>

<p>
I och med att jag inte �r hyperbra med design i allm�nhet s� t�nkte jag att jag utgick ifr�n din ist�llet
och �ndrade ochdonade lite med texter osv. Det fanns �ven lite sm�problem med .htaccess-filen n�r man la
�ver den till studentservern d� den tog bort "www" n�r man gick in p� sidan vilket resulterade i att man
inte kom in p� sidan alls. Det var bara att kommentera bort ett par rader fr�n .htaccess s� l�stes problemet.
Ut�ver det s� fanns det inte m�nga problem, m�jligtvis att jag hade gl�mt bort hur man ansluter till
studentkontot via ftp, men det gick r�tt snabbt att leta upp.
</p>

<img src="<?=CNocturnal::Instance()->request->GetBaseUrl()?>site/data/presentation/close_enough.png" alt="" style="width: 240px; height: 177px;" />

<h2>Kmom02: Grunden till ett MVC ramverk</h2>

<p>
Jag har d�pt mitt ramverk till Nocturnal, f�r att det mesta arbetet sker p� natten. Namngivningen brukar jag
alltid ha sv�rt f�r s� jag brukar v�nta med att ge ett namn och d�per ist�llet alla mina stora projekt till
Nocturnal(endast ett per omr�de), och ger dem ist�llet namn efter�t n�r de �r klara. Fast det h�r projektet
f�r nog heta Nocturnal p� heltid.
</p>

<p>
Jag l�ste <a target="_blank" href="http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/">Anat Garg's</a>
tutorial om hur man g�r ett MVC-ramverk �ven om mitt �r n�stan identiskt med Lydia i klass-struktur och liknande. Dock s� �r
koden inte likadan som Lydia's utan �ndrad p� flertalet st�llen. Jag anv�nde inte n�got av tutorialen's material d� jag tyckte
att det verkade vara lite v�l simpelt p� n�got s�tt och jag gillade inte tr�dstrukturen. Lydia's tr�dstruktur passar mer hur jag
tycker att det ska vara. I �vrigt var tutorialen v�lskriven i min mening och bra att l�sa f�r att f� en �verblick av ett MVC.
</p>

<p>
Jag ville ha bredare tr�d ist�llet f�r djupare d� det endast skapar f�rvirring om man har en mapp i en mapp i en mapp t.ex. p� klasser som
�rver av en viss typ eller dyl. Ist�llet s� har jag mapparna "site" och "src" d�r src �r sj�lva k�rnan f�r MVC:et och "site" kommer att vara
f�r Controller-klasserna. 
</p>

<p>
Jag anv�nder ReflectionClass precis likadant som Lydia anv�nder det d� det k�ndes on�digt att �teruppfinna hjulet f�r just den h�r delen. Den
kontrollerar att det �r en giltig Controller-klass och k�r den angivna metoden om den existerar.
</p>

<p>
<a target="_blank" href="https://github.com/Macebox/MVC">GIT-l�nk</a>
</p>

<h2>Kmom03: En g�stbok i MVC-ramverket</h2>

<p>
F�r CodeIgniter s� anv�nde jag Rickards tutorial. Det gick smidigt och enkelt att g�ra en g�stbok i CodeIgniter och det var kul att testa
p� ett fullt fungerande ramverk s� att man kunde f� en �verblick av vad som kan komma att kr�vas i sitt egna. D�remot s� skrapade jag
f�rmodligen bara p� ytan av CodeIgniter d� jag kan gissa p� att det finns r�tt mycket mer funktionalitet i det.
</p>

<p>
Det f�rsta jag gjorde n�r jag skulle skapa g�stboken var att bygga om lite i systemet s� att den verkligen l�ste in klasser fr�n
site/src f�rst bl.a. d�r alla Controller-klasser ska ligga. Sedan n�r jag hade byggt klart allt enligt Lydias tutorial s� valde
jag att skapa en ny mapp direkt under root som skulle heta model d�r alla modelklasser ska finnas. Detta f�r att strukturera upp det
ytterligare s� att inte alla klasser ligger i samma mapp och s� slipper jag ha flera lager av mappar. Det k�nns n�dv�ndigt att g� via
Lydias tutorials i stort sett f�r att inte missa n�got som kan komma att beh�vas l�ngre fram, �ven om jag anv�nde ett annat system f�r
databashantering. Jag la �ven till en rad som hanterade "vem" som skrev en viss post(�ven om detta inte kontrolleras). Jag fixade �ven s� att
exceptions skrevs ut i debuggen om den var aktiverad.
</p>

<p>
Jag tyckte att databashanteringen skulle vara smidig och gjorde d�rf�r en hanterare f�r alla databasklasser som heter CDatabase. I samma
mapp ligger alla olika klasser som �rver av ett interface IDBDriver. I mitt fall anv�nder jag CMysqli som enda klass just nu, men
det finns m�jlighet f�r att bygga ut s� att man l�gger till en klass CSQLLite och �ndrar driver i config.php samt l�gger till lite annan information
som kan beh�vas. Detta g�r att det ser ut att fungera likadant att ansluta via CMysqli som CSQLLite eller vad man nu vill anv�nda. <br />
Jag la �ven till lite extra som t.ex. att om man vill h�mta all information fr�n tabellen "posts" s� skriver man bara
$this->database->Get('posts');(fr�n alla som �rver av CObject) men vill man bara ha titel s� skriver man $this->database->Get('posts', array('title'));
vill man ytterligare s�ga att det bara ska vara fr�n en viss avs�ndare s� skickar man in $this->database->Get('posts','',array('author'=>'Mace')); <br />
P� liknande s�tt fungerar de andra metoderna Insert() och Delete(). Jag funderar p� att l�gga till ytterligare en metod kallad t.ex. Query();
d�r man kan g�ra en f�rfr�gan hur som helst. Det viktigaste just nu �r dock att om en query k�rs via dessa s� k�rs en mysql_real_escape_string p� alla variabler innan.
</p>
<p>
Att till�gga till min databashanterare �r att man skickar in arrayer av data s� att det kan skrivas om ordentligt. T.ex. "Get('posts',array('id', 'title'),
array('author'=>'Mace'));" genererar f�ljande f�rfr�gan: "SELECT id, title FROM posts WHERE author='Mace'".
</p>

<p>
Jag valde att strukturera som f�ljande: <br />
Modeller i mappen /models/<br />
Views i mappen /views/<br />
Controllers i mappen /site/src/<br /> <br />

Views-data(s� som bilder etc.) i mappen /data/ <br />

https://github.com/Macebox/MVC/tree/v0.2.0

</p>

<img src="<?=CNocturnal::Instance()->request->GetBaseUrl()?>site/data/presentation/fuck_yeah.png" alt="" style="width: 229px; height: 210px;" />