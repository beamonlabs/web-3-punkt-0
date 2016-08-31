# web-3-punkt-0
beamonpeople.se 3.0

Två olika widget areas har skapats och används för plugins
- "Start page" för widgets på startsidan
- "Other page" för widgets på andra sidor

Dessa plugins fungerar både på "Start page" och "Other page" widget areas:
Project, Colleagues, News samt Expertises. Man behöver alltså lägga till dessa på båda areas. 

Plugin Consultant (Konsulten har ordet) fungerar endast på "Start page".  

Plugin Nutshell (Beamon i ett nötskal) fungerar endast på "Other page".

I admin gränssnittet kan man specificera för vilken sida pluginen ska synnas.

Det finns sju olika typer av kategorier att välja på när man skapar ett inlägg: "News" (Aktuellt), "Jobs" (Jobb), "Expertises" (Vad vi gör), "Colleagues" (Beamon People), "Projects" (Projekt), "Consultant" (Konsulten har ordet) samt "Nutshell" (Beamon i ett nötskal). 
Beroende på kategori som inlägget har är det synligt på olika platser. "Nutshell", "Projects" samt "Colleagues" hämtar poster slumpmässigt.

Lite att fixa:
- Data för Beamon People och Projekt ska hämtas dynamiskt från Google Spreadsheets
- Widget admin settings dynamiska beroende på vilken widget area som widgeten är placerad i
- Viss text/länkar är i nuläget statiska
- Responsiv design
- Valbar bild i jobb inlägg
- Sidomeny i nyheterna så man enkelt kan gå till annan nyhet
- Välja Beam/Projekt att läsa om på Beamon People/Projekt sidan med hjälp av Knockout.js?
- Visa bild för inläggen på sidan Vad vi gör
- Ändra Konsulten har ordet enligt kravbeskrivning
- Se kravbeskrivning

