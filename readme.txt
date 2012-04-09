=== Game tabs ===
Initial developer: http://www.biztechconsultancy.com/
Contributors: OiPenguin, Espen Gjester, Jon-Erik Andersgaard
Donate link: https://www.doctorswithoutborders.org/donate/ (Doctors without borders)
Funding: http://www.strommen-if.no
Tags: XML, table, results, fixture, football, norsk, fotball, Tippeligaen, Adeccoligaen, Speaker.no
Requires at least: 3.12
Tested up to: 3.13
Stable tag: 0.4.0
License: GPLv2 or later

Fetch XML-feeds from Speaker (commercially available) and display fixture list, results and league table for the Norwegian football leauge.

== Description ==

(Norwegian translation below.) The purpose of this plugin is to fetch XML-feeds from Speaker.no (commercially available) and display fixture list, results and league table for the Norwegian football leauge.

Norwegian translation of description: Funksjonen til denne pluginen er automatisk visning av resultater, tabell og terminliste for norsk fotball basert på data via XML-feed fra Speaker.no. Disse dataene er (dessverre) kommersielle og må kjøpes fra Speaker.no.

== Installation ==

1. Copy the entire /game-tabs/ directory into your /wp-content/plugins/ directory.
2. Activate the plugin.
3. Locate Team config in the menu
4. Complete XML-configuration with XML-feed from www.Speaker.no
5. Configure the options to taste

== Frequently Asked Questions ==
Is this plugin prepared for localization? Yes.

= Requirements =

CURL must be enabled on the server to proceed. CURL warnings won't get displayed.

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png

== Changelog ==

= 0.4.0 =

ENDRINGER I FORHOLD TIL VERSJON LEVERT FRA INDIA; BÅDE KODEFORBEDRINGER OG CSS (sist korrigert 29.06.2011).
-alle endringene under er gjort i vedlagte versjon av GT
-----------------------------------------------------------

- Lastet opp nytt stilark: style_gt.css og det må refereres til nevnte css-fil i to andre filer:
	- main_page.php; endre sti i heading til riktig stilark (fra example.css til style_gt.css)
	- my-game-tabs.php; endre sti i heading til riktig stilark (fra example.css til style_gt.css)

------------------------------------------------------------
I filen main_page.php:
(scroll til litt over halvveis i filen)

Endre: <div id="container"> til <div id="gt_container"> 
	-Hvorfor: fordi "container" er en så vanlig div-tag at formatering på tema blir feil; hoved-content flyter helt ut og sidebar legger seg under..

-------------------------------------------------------------

I filen my-game-tabs.php:
- i paragrafene til de tre fanene, den første paragrafen starter med "...SHOW_SISTE_GLOBAL ...." de andre heter tilsvarende.

	- fjernet [class="left" ] fra [td]-taggen samt "width=20%" for "away-team"-kolonnen i fanene "Resultat" og "Neste" (ca linje 111, 113, 177, 179)
	- lagt til [align="center"] i [td]-taggen for "Resultat"-kolonnen i fanene "Resultat" og "Neste" (ca linje 125, 127, )
	- lagt til [align="center"] i fanen "Tabell" for kolonnene "Kamper", "Mål+/-" og "Poeng"
	- fjernet kolonnen "time" for resultat-tabb
	- endret peking av "vis alle kamper" fra main_page.php til mainpage-gt.php som er den modifiserte hovedsidefilen


-------------------------------------------------------------
I filen games.php:
scroll til litt over halvveis på siden og se etter [<?php if(GOOGLE_MAP_GLOBAL==1) { ?>]

- rett under [venue]-definisjon skal det legges til [align="center"] i [td]-taggen, slik:
[<td align="center" width="10%">
                         <?php 
                         echo $data['home_goals'] ."-". $data['away_goals'] ;]

------------------------------------------------------------
Fjernet fra toppen av games.php, tabell.php og naste.php fordi det ble duplikat-kode (kalles også mainpage-gt.php som "includes" nevnte filer):

<?php
include_once('../../../wp-config.php');
include_once('../../../wp-includes/wp-db.php');
?>
------------------------------------------------------------

Fiks for feil lasting av mainpage-gt.php (hovedside) i IE:

Noe så enkelt som at "header.php" bør lastes FØR ".js" filen kalles inn var kilden til problemet i IE (Firefox og Safari lastet allikevel riktig).
- flyttet <?php get_header(); ?> fra linje 11 til linje 7 (dvs rett over inkludering av tabber.js....)

-------------------------------------------------------------

Endret i filen "my-game-tabs.php", ca linje 594: 
- endret definering av tittel for plugin i kontrollpanelet fra "Team config" til "Game Tabs"

-------------------------------------------------------------


= 0.3.6 =
* Remove that Tab hader on page
* game is not yet played 0-0 replace with a dash "-" this applies to Page not Widget
* fixed order of the columns and diff of points
* CSS cleaned. All remains of the TwentyTen style sheet is removed

= 0.3.5 =
* No change since version 0.3.4. The sole purpose of version change is to test if upgrade is working.

= 0.3.4 = 
* Plugin is now hopefully upgradeable.
* Changed configuration.php and my-game-tabs.php
* Updated /languages

= 0.3.3 = 
* Updated configuration.php with plugin information

= 0.3.2 = 
* Fixed screenshots

= 0.3 = 
* Modified two classes "content" and "left". 

= 0.2 = 
* Initial version with an updated my-game-tabs.php. Not stable. Not recommended for production sites.

= 0.1 =
* Initial version. Not stable. Not recommended for production sites.

== Upgrade Notice ==

= 0.3 = 
No new features since previous release, but previous version is considered unstable.

